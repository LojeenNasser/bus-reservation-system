<?php

namespace App\DataTables\Scopes\Bookings;

use App\BookingStatus;
use Yajra\DataTables\Contracts\DataTableScope;

class CancelledBookings implements DataTableScope
{
    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function apply($query)
    {
         return $query->where('status', BookingStatus::CANCELLED);
    }
}
