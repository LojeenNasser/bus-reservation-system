@extends('layouts.app')

@section('title', 'Locations')

@section('content_header')
    <h1>Locations</h1>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="card card-outline card-primary elevation-2">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4>Locations Table</h4>
                            <a href="{{ route('admin.locations.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Create Location
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        {!! $dataTable->table() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush
