@extends('layouts.app')


@section('content_header')
    <h1>Rides</h1>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card card-outline card-indigo elevation-2">
                    <div class="card-header">
                        <h4>Rides manager</h4>
                    </div>

                    <div class="card-body">


                        <ul class="nav nav-tabs justify-content-center">

                        </ul>
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h4>Rides Table</h4>
                                <a href="{{ route('admin.rides.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Create Ride
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive mt-2">
                            {{ $dataTable->table() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
