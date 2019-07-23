@extends('layouts.app')

@section('title', __('stop.detail'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('stop.detail') }}</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <tr><td>{{ __('stop.name') }}</td><td>{{ $stop->name }}</td></tr>
                        <tr><td>{{ __('stop.latitude') }}</td><td>{{ $stop->latitude }}</td></tr>
                        <tr><td>{{ __('stop.longitude') }}</td><td>{{ $stop->longitude }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                    <a href="{{ route('stops.edit', $stop) }}" id="edit-outlet-{{ $stop->id }}" class="btn btn-warning">{{ __('stop.edit') }}</a>
                <a href="{{ route('stops.index') }}" class="btn btn-link">{{ __('stop.back_to_index') }}</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ trans('stop.location') }}</div>
            @if ($stop->longitude)
            <div class="card-body" id="mapid"></div>
            @else
            <div class="card-body">{{ __('stop.no_coordinate') }}</div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
    integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
    crossorigin=""/>

<style>
    #mapid { height: 400px; }
</style>
@endsection
@push('scripts')
<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
    integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
    crossorigin=""></script>

<script>
    var map = L.map('mapid').setView([{{ $stop->latitude }}, {{ $stop->longitude }}], {{ config('leaflet.detail_zoom_level') }});

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.marker([{{ $stop->latitude }}, {{ $stop->longitude }}]).addTo(map)
        .bindPopup('{!! $stop->map_popup_content !!}');
</script>
@endpush
