@extends('layouts.app')

@section('title', __('stop.list'))

@section('content')
<div class="mb-3">
    <div class="float-right">
            <a href="{{ route('stops.create') }}" class="btn btn-success">{{ __('stop.create') }}</a>
    </div>
    <h1 class="page-title">{{ __('stop.list') }} <small>{{ __('app.total') }} : {{ $stops->total() }} {{ __('stop.stop') }}</small></h1>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <form method="GET" action="" accept-charset="UTF-8" class="form-inline">
                    <div class="form-group">
                        <label for="q" class="control-label">{{ __('stop.search') }}</label>
                        <input placeholder="{{ __('stop.search_text') }}" name="q" type="text" id="q" class="form-control mx-sm-2" value="{{ request('q') }}">
                    </div>
                    <input type="submit" value="{{ __('stop.search') }}" class="btn btn-secondary">
                    <a href="{{ route('outlets.index') }}" class="btn btn-link">{{ __('app.reset') }}</a>
                </form>
            </div>
            <table class="table table-sm table-responsive-sm">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('stop.name') }}</th>
                        <th>{{ __('stop.latitude') }}</th>
                        <th>{{ __('stop.longitude') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stops as $key => $stop)
                    <tr>
                        <td class="text-center">{{ $stops->firstItem() + $key }}</td>
                        <td>{!! $stop->name !!}</td>
                        <td>{{ $stop->latitude }}</td>
                        <td>{{ $stop->longitude }}</td>
                        <td class="text-center">
                            <a href="{{ route('stops.show', $stop) }}" id="show-outlet-{{ $stop->id }}">{{ __('app.show') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-body">{{ $stops->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
</div>
@endsection
