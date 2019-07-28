@extends('layouts.app')

@section('title', __('trip.list'))

@section('content')
<div class="mb-3">
    <div class="float-right">
            <a href="{{ route('trips.create') }}" class="btn btn-success">{{ __('trip.create') }}</a>
    </div>
    <h1 class="page-title">{{ __('trip.list') }} <small>{{ __('app.total') }} : {{ $trips->total() }} {{ __('trip.trip') }}</small></h1>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                {{--<form method="GET" action="" accept-charset="UTF-8" class="form-inline">
                    <div class="form-group">
                        <label for="q" class="control-label">{{ __('trip.search') }}</label>
                        <input placeholder="{{ __('trip.search_text') }}" name="q" type="text" id="q" class="form-control mx-sm-2" value="{{ request('q') }}">
                    </div>
                    <input type="submit" value="{{ __('trip.search') }}" class="btn btn-secondary">
                    <a href="{{ route('trips.index') }}" class="btn btn-link">{{ __('app.reset') }}</a>
                </form>--}}
            </div>
            <table class="table table-sm table-responsive-sm">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('trip.start') }}</th>
                        <th>{{ __('trip.end') }}</th>
                        <th>{{ __('trip.from') }}</th>
                        <th>{{ __('trip.to') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trips as $key => $trip)
                    <tr>
                        <td class="text-center">{{ $trips->firstItem() + $key }}</td>
                        <td>{{date('h:i:s a', strtotime($trip->start))}}</td>
                        <td>{{date('h:i:s a', strtotime($trip->end))}}</td>
                        <td>{{ $trip->origin }}</td>
                        <td>{{ $trip->destination }}</td>
                        <td class="text-center">
                            <a href="{{ route('trips.show', $trip) }}" id="show-outlet-{{ $trip->id }}">{{ __('app.show') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-body">{{ $trips->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
</div>
@endsection
