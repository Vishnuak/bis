@extends('layouts.app')

@section('title', __('bus.detail'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('bus.detail') }}</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <tr><td>{{ __('bus.name') }}</td><td>{{ $bus->name }}</td></tr>
                        <tr><td>{{ __('bus.owner') }}</td><td>{{ $bus->owner }}</td></tr>
                        <tr><td>{{ __('bus.number') }}</td><td>{{ $bus->number }}</td></tr>
                        <tr><td>{{ __('bus.type') }}</td><td>{{ $bus->type }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                    <a href="{{ route('buses.edit', $bus) }}" id="edit-outlet-{{ $bus->id }}" class="btn btn-warning">{{ __('bus.edit') }}</a>
                <a href="{{ route('buses.index') }}" class="btn btn-link">{{ __('bus.back_to_index') }}</a>
            </div>
        </div>
    </div>
    {{--<div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ trans('bus.location') }}</div>
            @if ($bus->longitude)
            <div class="card-body" id="mapid"></div>
            @else
            <div class="card-body">{{ __('bus.no_coordinate') }}</div>
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

