@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Buses Manager</div>
            <div class="card-body">
                <div class="card-header d-flex justify-content-between">
                    <a href="{{ route('admin.buses.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Create Bus
                    </a>
                </div>
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
