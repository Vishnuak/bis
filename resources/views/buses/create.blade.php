@extends('layouts.app')

@section('title', __('bus.create'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('bus.create') }}</div>
            <form method="POST" action="{{ route('buses.store') }}" accept-charset="UTF-8">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="control-label">{{ __('bus.name') }}</label>
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required>
                        {!! $errors->first('name', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label for="owner" class="control-label">{{ __('bus.owner') }}</label>
                        <input id="owner" type="text" class="form-control{{ $errors->has('owner') ? ' is-invalid' : '' }}" name="owner" value="{{ old('owner') }}" required>
                        {!! $errors->first('owner', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="number" class="control-label">{{ __('bus.number') }}</label>
                                <input id="number" type="text" class="form-control{{ $errors->has('number') ? ' is-invalid' : '' }}" name="number" value="{{ old('number', request('number')) }}">
                                {!! $errors->first('number', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type" class="control-label">{{ __('bus.type') }}</label>
                                <select id="type" class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}" name="type" required>
                                    <option value="ORD" @if(old('type', request('type')) == 'ORD') selected="" @endif>ORD</option>
                                    <option value="LF" @if(old('type', request('type')) == 'LF') selected="" @endif>LF</option>
                                    <option value="LS" @if(old('type', request('type')) == 'LS') selected="" @endif>LS</option>
                                    <option value="FP" @if(old('type', request('type')) == 'FP') selected="" @endif>FP</option>
                                    <option value="SFP" @if(old('type', request('type')) == 'SFP') selected="" @endif>SFP</option>
                                </select>
                                
                                {!! $errors->first('type', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div id="mapid"></div>
                </div>
                <div class="card-footer">
                    <input type="submit" value="{{ __('bus.create') }}" class="btn btn-success">
                    <a href="{{ route('buses.index') }}" class="btn btn-link">{{ __('app.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection




