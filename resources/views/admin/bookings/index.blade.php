@extends('layouts.app')
@section('title', 'Bookings')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Bookings Manager
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link {{ !request()->has('status') ? 'active' : '' }}"
                           href="{{ route('admin.bookings.index') }}">
                            All
                        </a>
                    </li>
                    @foreach($bookingStatuses as $status)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->get('status') == $status ? 'active' : '' }}"
                               href="{{ route('admin.bookings.index', ['status' => $status]) }}">
                                {{ ucfirst($status) }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="table-responsive mt-2">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
