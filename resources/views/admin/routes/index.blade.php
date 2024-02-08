@extends('layouts.app')

@section('title', 'Admin - Routes')



@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="card card-outline card-primary elevation-2">
                    <div class="card-header">
                        <h4>Routes Table</h4>
                    </div>
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.routes.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Create Route
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
