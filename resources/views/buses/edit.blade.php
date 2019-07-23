@extends('layouts.app')

@section('title', __('bus.edit'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        @if (request('action') == 'delete' && $bus)

            <div class="card">
                <div class="card-header">{{ __('bus.delete') }}</div>
                <div class="card-body">
                    <label class="control-label text-primary">{{ __('bus.name') }}</label>
                    <p>{{ $bus->name }}</p>
                    <label class="control-label text-primary">{{ __('bus.owner') }}</label>
                    <p>{{ $bus->owner }}</p>
                    <label class="control-label text-primary">{{ __('bus.number') }}</label>
                    <p>{{ $bus->number }}</p>
                    <label class="control-label text-primary">{{ __('bus.type') }}</label>
                    <p>{{ $bus->type }}</p>
                    {!! $errors->first('bus_id', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="card-body text-danger">{{ __('bus.delete_confirm') }}</div>
                <div class="card-footer">
                    <form method="POST" action="{{ route('buses.destroy', $bus) }}" accept-charset="UTF-8" onsubmit="return confirm(&quot;{{ __('app.delete_confirm') }}&quot;)" class="del-form float-right" style="display: inline;">
                        {{ csrf_field() }} {{ method_field('delete') }}
                        <input name="bus_id" type="hidden" value="{{ $bus->id }}">
                        <button type="submit" class="btn btn-danger">{{ __('app.delete_confirm_button') }}</button>
                    </form>
                    <a href="{{ route('buses.edit', $bus) }}" class="btn btn-link">{{ __('app.cancel') }}</a>
                </div>
            </div>

        @else
        <div class="card">
            <div class="card-header">{{ __('bus.edit') }}</div>
            <form method="POST" action="{{ route('buses.update', $bus) }}" accept-charset="UTF-8">
                {{ csrf_field() }} {{ method_field('patch') }}
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="control-label">{{ __('bus.name') }}</label>
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $bus->name) }}" required>
                        {!! $errors->first('name', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label for="owner" class="control-label">{{ __('bus.owner') }}</label>
                        <input id="owner" type="text" class="form-control{{ $errors->has('owner') ? ' is-invalid' : '' }}" name="owner" value="{{ old('owner', $bus->owner) }}" required>
                        {!! $errors->first('owner', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="number" class="control-label">{{ __('bus.number') }}</label>
                                <input id="number" type="text" class="form-control{{ $errors->has('number') ? ' is-invalid' : '' }}" name="number" value="{{ old('number', $bus->number) }}" required>
                                {!! $errors->first('number', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type" class="control-label">{{ __('bus.type') }}</label>
                                <select id="type" class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}" name="type" required>
                                    <option value="ORD" @if(old('type', $bus->type) == 'ORD') selected="" @endif>ORD</option>
                                    <option value="LF" @if(old('type', $bus->type) == 'LF') selected="" @endif>LF</option>
                                    <option value="LS" @if(old('type', $bus->type) == 'LS') selected="" @endif>LS</option>
                                    <option value="FP" @if(old('type', $bus->type) == 'FP') selected="" @endif>FP</option>
                                    <option value="SFP" @if(old('type', $bus->type) == 'SFP') selected="" @endif>SFP</option>
                                </select>
                                
                                {!! $errors->first('type', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div id="mapid"></div>
                </div>
                <div class="card-footer">
                    <input type="submit" value="{{ __('bus.update') }}" class="btn btn-success">
                    <a href="{{ route('buses.show', $bus) }}" class="btn btn-link">{{ __('app.cancel') }}</a>
                    
                        <a href="{{ route('buses.edit', [$bus, 'action' => 'delete']) }}" id="del-bus-{{ $bus->id }}" class="btn btn-danger float-right">{{ __('app.delete') }}</a>
                    
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection


