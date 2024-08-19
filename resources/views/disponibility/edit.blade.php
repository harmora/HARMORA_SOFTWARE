@extends('layout')

@section('title')
    {{ get_label('update_reservation', 'Update Reservation') }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ url('/disponibility') }}">{{ get_label('reservations', 'Reservations') }}</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ get_label('update', 'Update') }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('/disponibility/update/' . $reservation->id) }}" method="POST" class="form-submit-event">
                @method('PUT')
                @csrf
                <input type="hidden" name="redirect_url" value="/disponibility">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="activity_name" class="form-label">{{ get_label('activity_name', 'Activity Name') }} <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="activity_name" name="activity_name" placeholder="{{ get_label('please_enter_activity_name', 'Please enter activity name') }}" value="{{ old('activity_name', $reservation->activity_name) }}" required>
                        @if ($errors->has('activity_name'))
                            <span class="text-danger">{{ $errors->first('activity_name') }}</span>
                        @endif
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="details" class="form-label">{{ get_label('description', 'Description') }}</label>
                        <textarea class="form-control" id="details" name="details" placeholder="{{ get_label('please_enter_description', 'Please enter description') }}">{{ old('details', $reservation->details) }}</textarea>
                        @if ($errors->has('details'))
                            <span class="text-danger">{{ $errors->first('details') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="start_date_event" class="form-label">{{ get_label('starts_at', 'Starts at') }} <span class="asterisk">*</span></label>
                        <input type="date" id="start_date_event" name="start_date_event" class="form-control" value="{{ old('start_date_event', \Carbon\Carbon::parse($reservation->start_date_time)->format('Y-m-d')) }}" required>
                        @if ($errors->has('start_date_event'))
                            <span class="text-danger">{{ $errors->first('start_date_event') }}</span>
                        @endif
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="start_time" class="form-label">{{ get_label('time', 'Time') }} <span class="asterisk">*</span></label>
                        <input type="time" id="start_time" name="start_time" class="form-control" value="{{ old('start_time', \Carbon\Carbon::parse($reservation->start_date_time)->format('H:i')) }}" required>
                        @if ($errors->has('start_time'))
                            <span class="text-danger">{{ $errors->first('start_time') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="end_date_event" class="form-label">{{ get_label('ends_at', 'Ends at') }} <span class="asterisk">*</span></label>
                        <input type="date" id="end_date_event" name="end_date_event" class="form-control" value="{{ old('end_date_event', \Carbon\Carbon::parse($reservation->end_date_time)->format('Y-m-d')) }}" required>
                        @if ($errors->has('end_date_event'))
                            <span class="text-danger">{{ $errors->first('end_date_event') }}</span>
                        @endif
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="end_time" class="form-label">{{ get_label('time', 'Time') }} <span class="asterisk">*</span></label>
                        <input type="time" id="end_time" name="end_time" class="form-control" value="{{ old('end_time', \Carbon\Carbon::parse($reservation->end_date_time)->format('H:i')) }}" required>
                        @if ($errors->has('end_time'))
                            <span class="text-danger">{{ $errors->first('end_time') }}</span>
                        @endif
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2" id="submit_btn">{{ get_label('update', 'Update') }}</button>
                    <a href="{{ url('/reservations') }}" class="btn btn-outline-secondary">{{ get_label('cancel', 'Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
