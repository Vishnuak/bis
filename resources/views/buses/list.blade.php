@extends('layouts.app')

@section('title', __('bus.list'))

@section('content')
<div class="mb-3">
    <div class="float-right">
            <a href="{{ route('buses.create') }}" class="btn btn-success">{{ __('bus.create') }}</a>
    </div>
    <h1 class="page-title">{{ __('bus.list') }} <small>{{ __('app.total') }} : {{ $buses->total() }} {{ __('bus.bus') }}</small></h1>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <form method="GET" action="" accept-charset="UTF-8" class="form-inline">
                    <div class="form-group">
                        <label for="q" class="control-label">{{ __('bus.search') }}</label>
                        <input placeholder="{{ __('bus.search_text') }}" name="q" type="text" id="q" class="form-control mx-sm-2" value="{{ request('q') }}">
                    </div>
                    <input type="submit" value="{{ __('bus.search') }}" class="btn btn-secondary">
                    <a href="{{ route('buses.index') }}" class="btn btn-link">{{ __('app.reset') }}</a>
                </form>
            </div>
            <table class="table table-sm table-responsive-sm">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('bus.name') }}</th>
                        <th>{{ __('bus.owner') }}</th>
                        <th>{{ __('bus.number') }}</th>
                        <th>{{ __('bus.type') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($buses as $key => $bus)
                    <tr>
                        <td class="text-center">{{ $buses->firstItem() + $key }}</td>
                        <td>{!! $bus->name !!}</td>
                        <td>{{ $bus->owner }}</td>
                        <td>{{ $bus->number }}</td>
                        <td>{{ $bus->type }}</td>
                        <td class="text-center">
                            <a href="{{ route('buses.show', $bus) }}" id="show-outlet-{{ $bus->id }}">{{ __('app.show') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-body">{{ $buses->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
</div>
@endsection
