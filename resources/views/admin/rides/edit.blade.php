@extends('layouts.app')

@section('title', 'Edit ride')

@section('content_header')
    <h1>Rides</h1>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-outline card-primary elevation-2">
                    <div class="card-header">
                        <h4>Edit ride</h4>
                    </div>

                    <form action="{{ route('admin.rides.update', $ride) }}" method="POST">
                        <div class="card-body">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="route">Route</label>
                                <select name="route_id" id="route" required
                                        class="custom-select @error('route_id') is-invalid @enderror">
                                    <option value="" hidden>Choose route</option>
                                    @foreach($routes as $route)
                                        <option
                                            value="{{ $route->id }}" {{ old('route_id', $ride->route->id) == $route->id ? "selected" : ""}}>
                                            {{ $route->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('route_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="bus">Bus</label>
                                <select name="bus_id" id="bus" required
                                        class="custom-select @error('bus_id') is-invalid @enderror">
                                    <option value="" hidden>Choose bus</option>
                                    @foreach($buses as $bus)
                                        <option
                                            value="{{ $bus->id }}" {{ old('bus_id', $ride->bus->id) == $bus->id ? "selected" : ""}}>
                                            {{ $bus->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('bus_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="departure-time">Departure time</label>
                                <input type="time" class="form-control @error('bus_id') is-invalid @enderror" required
                                       name="departure_time" id="departure-time"
                                       value="{{ old('departure_time', ($ride->departure_time instanceof \DateTime) ? $ride->departure_time->format('H:i') : '') }}">

                                @error('departure_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input class="custom-control-input" type="checkbox"
                                           name="auto_confirm" value="1" id="auto-confirm"
                                        {{ old('auto_confirm', $ride->auto_confirm) ? "checked" : "" }}>

                                    <label class="custom-control-label" for="auto-confirm">
                                        Automatic booking confirmation
                                    </label>
                                </div>

                                @error('auto_confirm')
                                    <span class="invalid-feedback" style="display: block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- المزيد من الحقول ... -->

                        </div>

                        <div class="card-footer row justify-content-center">
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

