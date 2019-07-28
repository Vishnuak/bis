@extends('layouts.app')

@section('title', __('trip.create'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('trip.create') }}</div>
            <form method="POST" action="{{ route('trips.store') }}" accept-charset="UTF-8">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="form-group">
                        <label for="bus_id" class="control-label">{{ __('trip.bus') }}</label>
                        <select id="bus_id" class="form-control{{ $errors->has('bus_id') ? ' is-invalid' : '' }}" name="bus_id" required>
                            @foreach($buses as $bus)
                            <option value="{{$bus->id}}" @if(old('bus_id', request('bus_id')) == $bus->id) selected="" @endif>{{$bus->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name" class="control-label">{{ __('trip.start') }} (format hh:mm:ss    eg: 19:00:00.000000)</label>
                        <input id="start" type="text" class="form-control{{ $errors->has('start') ? ' is-invalid' : '' }}" name="start" value="{{ old('start', '19:00:00.000000') }}" required>
                        {!! $errors->first('start', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label for="name" class="control-label">{{ __('trip.end') }} (format hh:mm:ss    eg: 19:00:00.000000)</label>
                        <input id="end" type="text" class="timepicker form-control{{ $errors->has('end') ? ' is-invalid' : '' }}" name="end" value="{{ old('end', '20:00:00.000000') }}" required>
                        {!! $errors->first('end', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label for="from" class="control-label">{{ __('trip.from') }}</label>
                        <select id="from" class="form-control{{ $errors->has('from') ? ' is-invalid' : '' }}" name="from" required>
                            @foreach($stops as $stop)
                            <option value="{{$stop->id}}" @if(old('from', request('from')) == $stop->id) selected="" @endif>{{$stop->name}}</option>
                            @endforeach
                        </select>
                        
                        {!! $errors->first('from', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label for="to" class="control-label">{{ __('trip.to') }}</label>
                        <select id="to" class="form-control{{ $errors->has('to') ? ' is-invalid' : '' }}" name="to" required>
                            @foreach($stops as $stop)
                            <option value="{{$stop->id}}" @if(old('to', request('to')) == $stop->id) selected="" @endif>{{$stop->name}}</option>
                            @endforeach
                        </select>
                        
                        {!! $errors->first('to', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                    </div>
                    
                    <div id="mapid"></div>
                </div>
                <div class="card-footer">
                    <input type="submit" value="{{ __('trip.create') }}" class="btn btn-success">
                    <a href="{{ route('trips.index') }}" class="btn btn-link">{{ __('app.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
{{--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>  

  <script type="text/javascript">
    $(function(){
    $('.timepicker').datetimepicker({
        format: 'HH:mm:ss'
    });
    });
</script>
--}}
@endsection

@push('scripts')
{{--<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>        
<script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.4.0/lang/en-gb.js"></script>                
<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.0.0/js/bootstrap-datetimepicker.min.js"></script>

<script>
jQuery(document).ready(function () {

    jQuery('.timepicker').datetimepicker({ format: 'LT' });
    //jQuery('.timepicker').datetimepicker();

});
</script>--}}
@endpush
