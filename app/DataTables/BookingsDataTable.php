<?php

namespace App\DataTables;

use App\Booking;
use App\User;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BookingsDataTable extends DataTable
{
    protected const TABLE_ID = 'bookings-table';

    private Booking $bookingModel;

    public function __construct(Booking $bookingModel)
    {
        $this->bookingModel = $bookingModel;
    }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('start_location', fn (Booking $booking) => $booking->startLocation->name)
            ->editColumn('end_location', fn (Booking $booking) => $booking->endLocation->name)
            ->editColumn('status', fn (Booking $booking) => ucfirst($booking->status))
            ->editColumn('travel_date', function (Booking $booking) {
                return is_string($booking->travel_date)
                    ? $booking->travel_date
                    : $booking->travel_date->format('Y-m-d');
            })
            ->editColumn('created_at', fn (Booking $booking) => $booking->created_at)
            ->editColumn('updated_at', fn (Booking $booking) => $booking->updated_at)
            ->editColumn('ride', function (Booking $booking) {
                $route = route('admin.rides.show', $booking->ride->id);
                return "<a href='{$route}'>{$booking->ride->id}</a>";
            })
            ->rawColumns(['ride'])
            ->addColumn('ride', fn (Booking $booking) => $booking->ride->id)
            ->addColumn('actions', function (Booking $booking) {
                return view('admin.partials.datatable-action-buttons', [
                    'id' => $booking->id,
                    'itemName' => 'booking',
                    'editRoute' => route('admin.bookings.edit', $booking),
                    'destroyRoute' => route('admin.bookings.destroy', $booking),
                ]);
            })
            ->filterColumn('user', function ($query, $keyword) {
                $query->where(function ($query) {
                    $query->selectRaw("LOWER(CONCAT(first_name, ' ', last_name))")
                        ->from('users')
                        ->whereColumn('id', 'bookings.user_id')
                        ->limit(1);
                }, 'LIKE', "%{$keyword}%");
            });
    }

    public function query(Booking $model)
    {
        return $model->newQuery()->with('ride', 'startLocation', 'endLocation')
            ->addSelect(['user' => User::selectRaw("CONCAT(first_name, ' ', last_name)")
                ->whereColumn('id', 'bookings.user_id')
                ->limit(1)]);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId(self::TABLE_ID)
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(8)
            ->addTableClass('table-hover')
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::make('id')->width('5%'),
            Column::make('ride')->width('5%')->name('ride.id'),
            Column::make('user')->width('15%'),
            Column::make('travel_date')->width('10%'),
            Column::make('start_location')->width('10%')->name('startLocation.name'),
            Column::make('end_location')->width('10%')->name('endLocation.name'),
            Column::make('seats')->width('5%')->searchable(false),
            Column::make('status')->width('5%'),
            Column::make('created_at')->width('10%')->title('Made At'),
            Column::make('updated_at')->width('10%'),
            Column::computed('actions')->width('15%')->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Bookings_' . date('YmdHis');
    }
}
