@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Manage Users</div>
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Create User
                    </a>

                </div>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
