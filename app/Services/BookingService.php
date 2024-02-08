<?php

namespace App\Services;

use App\Booking;
use App\BookingStatus;
use App\Events\BookingStatusChanged;
use App\Exceptions\NotEnoughSeatsAvailableException;
use App\Ride;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class BookingService
{
    public function create(array $data): Booking
    {
        $ride = Ride::find($data['ride_id']);
        $rideDepartureTime = Carbon::parse($ride->departure_time);
        $travelStartTime = Carbon::parse($ride->departure_time)
            ->addMinutes($ride->route->locations
                ->where('id', $data['start_location_id'])
                ->first()
                ->pivot->minutes_from_departure);

        $rideStartDate = Carbon::parse($data['travel_date']);
        if (!$rideDepartureTime->isSameDay($travelStartTime)) {
            $rideStartDate->subDay();
        }
        $data['ride_start_date'] = $rideStartDate->toDateString();

        $bookedSeats = $this->getBookedSeatsSum(
            $ride,
            $data['start_location_id'],
            $data['end_location_id'],
            $data['travel_date']
        );

        $availableSeats = $ride->bus->seats - $bookedSeats;

        if ($data['seats'] > $availableSeats) {
            throw new NotEnoughSeatsAvailableException('Not enough seats are available for this ride');
        }

        return auth()->user()->bookings()->create($data);
    }

    public function updateStatus(Booking $booking, string $status)
    {
        if ($status == BookingStatus::CONFIRMED) {
            $bookedSeats = $this->getBookedSeatsSum(
                $booking->ride,
                $booking->startLocation->id,
                $booking->endLocation->id,
                $booking->travel_date
            );
            $availableSeats = $booking->ride->bus->seats - $bookedSeats;

            if ($booking->seats > $availableSeats) {
                throw new NotEnoughSeatsAvailableException('Not enough seats are available for this ride');
            }
        }

        $booking->update([
            'status' => $status
        ]);

        if ($status != BookingStatus::NEW) {
            event(new BookingStatusChanged($booking));
        }
    }

    public function getBookedSeatsSum(Ride $ride, int $startLocationId, int $endLocationId, string $date): int
    {
        $startLocation = $ride->route->locations->where('id', $startLocationId)->first();
        $endLocation = $ride->route->locations->where('id', $endLocationId)->first();

        $calculatedDepartureTime = 'rides.departure_time + INTERVAL(?) MINUTE';
        $minutesFromDep = $startLocation->pivot->minutes_from_departure;
        $dayBefore = Carbon::parse($date)->subDay()->toDateString();

        return Booking::join('rides', 'ride_id', 'rides.id')
            ->join('location_route as start_location', function ($join) {
                $join->on('rides.route_id', 'start_location.route_id')
                    ->on('start_location_id', 'start_location.location_id');
            })->join('location_route as end_location', function ($join) {
                $join->on('rides.route_id', 'end_location.route_id')
                    ->on('end_location_id', 'end_location.location_id');
            })->where([
                ['ride_id', '=', $ride->id],
                ['start_location.order', '<', $endLocation->pivot->order],
                ['end_location.order', '>', $startLocation->pivot->order],
                ['status', '=', BookingStatus::CONFIRMED]
            ])->where(function (Builder $query) use ($calculatedDepartureTime, $minutesFromDep, $dayBefore, $date) {
                $query->where(function (Builder $query) use ($calculatedDepartureTime, $minutesFromDep, $dayBefore) {
                    $query->whereRaw("$calculatedDepartureTime > '23:59:59'", [$minutesFromDep])
                        ->where('ride_start_date', Carbon::parse($dayBefore));
                })->orWhere(function (Builder $query) use ($calculatedDepartureTime, $minutesFromDep, $date) {
                    $query->whereRaw("$calculatedDepartureTime <= '23:59:59'", [$minutesFromDep])
                        ->where('ride_start_date', Carbon::parse($date));
                });
            })->sum('seats');
    }

    public function lastMonthsCount(int $months): array
    {
        $lastFiveMonths = collect();
        $lastFiveMonthsBookingsCount = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $lastFiveMonths->push([
                'month' => $date->month,
                'year' => $date->year,
                'monthName' => $date->monthName
            ]);
        }

        $lastFiveMonths->each(function ($item) use (&$lastFiveMonthsBookingsCount) {
            $lastFiveMonthsBookingsCount[$item['monthName']] = $this->countInMonth($item['month'], $item['year']);
        });

        return $lastFiveMonthsBookingsCount;
    }

    public function countInMonth(int $month, int $year): int
    {
        return Booking::whereMonth('travel_date', $month)
            ->whereYear('travel_date', $year)
            ->count();
    }
}
