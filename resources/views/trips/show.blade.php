@extends('layouts.app')

@section('title', __('trip.detail'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('trip.detail') }}</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        
                        <tr><td>{{ __('bus.name') }}</td><td>{{ $trip->name }}</td></tr>
                        <tr><td>{{ __('bus.owner') }}</td><td>{{ $trip->owner }}</td></tr>
                        <tr><td>{{ __('bus.number') }}</td><td>{{ $trip->number }}</td></tr>
                        <tr><td>{{ __('bus.type') }}</td><td>{{ $trip->type }}</td></tr>
                        <tr><td>{{ __('trip.start') }}</td><td>{{ $trip->start }}</td></tr>
                        <tr><td>{{ __('trip.end') }}</td><td>{{ $trip->end }}</td></tr>
                        <tr><td>{{ __('trip.from') }}</td><td>{{ $trip->origin }}</td></tr>
                        <tr><td>{{ __('trip.to') }}</td><td>{{ $trip->destination }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                    <a href="{{ route('trips.edit', $trip) }}" id="edit-outlet-{{ $trip->id }}" class="btn btn-warning">{{ __('trip.edit') }}</a>
                <a href="{{ route('trips.index') }}" class="btn btn-link">{{ __('trip.back_to_index') }}</a>
            </div>
        </div>
    </div>
    {{--<div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ trans('trip.location') }}</div>
            @if ($trip->longitude)
            <div class="card-body" id="mapid"></div>
            @else
            <div class="card-body">{{ __('trip.no_coordinate') }}</div>
            @endif
        </div>
    </div>--}}
</div>
@endsection

@section('styles')


<style>
    #mapid { height: 400px; }
</style>
@endsection

