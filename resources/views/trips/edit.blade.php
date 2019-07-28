@extends('layouts.app')

@section('title', __('trip.edit'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        @php $count = 0; @endphp
        @if (request('action') == 'delete' && $trip)

            <div class="card">
                <div class="card-header">{{ __('trip.delete') }}</div>
                <div class="card-body">
                    <label class="control-label text-primary">{{ __('trip.bus') }}</label>
                    <p>{{ $trip->name }}</p>
                    <label class="control-label text-primary">{{ __('trip.start') }}</label>
                    <p>{{date('h:i:s a', strtotime($trip->start))}}</p>
                    <label class="control-label text-primary">{{ __('trip.end') }}</label>
                    <p>{{date('h:i:s a', strtotime($trip->end))}}</p>
                    <label class="control-label text-primary">{{ __('trip.type') }}</label>
                    <p>{{ $trip->type }}</p>
                    <label class="control-label text-primary">{{ __('trip.from') }}</label>
                    <p>{{ $trip->origin }}</p>
                    <label class="control-label text-primary">{{ __('trip.to') }}</label>
                    <p>{{ $trip->destination }}</p>
                    {!! $errors->first('bus_id', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="card-body text-danger">{{ __('trip.delete_confirm') }}</div>
                <div class="card-footer">
                    <form method="POST" action="{{ route('trips.destroy', $trip) }}" accept-charset="UTF-8" onsubmit="return confirm(&quot;{{ __('app.delete_confirm') }}&quot;)" class="del-form float-right" style="display: inline;">
                        {{ csrf_field() }} {{ method_field('delete') }}
                        <input name="Trip_id" type="hidden" value="{{ $trip->id }}">
                        <button type="submit" class="btn btn-danger">{{ __('app.delete_confirm_button') }}</button>
                    </form>
                    <a href="{{ route('trips.edit', $trip) }}" class="btn btn-link">{{ __('app.cancel') }}</a>
                </div>
            </div>

        @else
        <div class="card">
            <div class="card-header">{{ __('trip.edit') }}</div>
            <form method="POST" action="{{ route('trips.update', $trip) }}" accept-charset="UTF-8">
                {{ csrf_field() }} {{ method_field('patch') }}
                <div class="card-body">
                    <div class="form-group">
                        <label for="bus_id" class="control-label">{{ __('trip.bus') }}</label>
                        <select id="bus_id" class="form-control{{ $errors->has('bus_id') ? ' is-invalid' : '' }}" name="bus_id" required>
                            @foreach($buses as $bus)
                            <option value="{{$bus->id}}" @if(old('bus_id', $trip->bus_id) == $bus->id) selected="" @endif>{{$bus->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="start" class="control-label">{{ __('trip.start') }}</label>
                        <input id="start" type="text" class="form-control{{ $errors->has('start') ? ' is-invalid' : '' }}" name="start" value="{{ old('start', $trip->start) }}" required>
                        {!! $errors->first('start', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label for="end" class="control-label">{{ __('trip.end') }}</label>
                        <input id="end" type="text" class="form-control{{ $errors->has('owner') ? ' is-invalid' : '' }}" name="end" value="{{ old('end', $trip->end) }}" required>
                        {!! $errors->first('end', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                    </div>

                    <div class="form-group">
                        <label for="from" class="control-label">{{ __('trip.from') }}</label>
                        <select id="from" class="form-control{{ $errors->has('from') ? ' is-invalid' : '' }}" name="from" required>
                            <option value=''>select...</option>
                            @foreach($stops as $stop)
                            <option value="{{$stop->id}}" @if(old('from', $trip->originid) == $stop->id) selected="" @endif>{{$stop->name}}</option>
                            @endforeach
                        </select>
                        
                        {!! $errors->first('from', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label for="to" class="control-label">{{ __('trip.to') }}</label>
                        <select id="to" class="form-control{{ $errors->has('to') ? ' is-invalid' : '' }}" name="to" required>
                            <option value=''>select...</option>
                            @foreach($stops as $stop)
                            <option value="{{$stop->id}}" @if(old('to', $trip->destid) == $stop->id) selected="" @endif>{{$stop->name}}</option>
                            @endforeach
                        </select>
                        
                        {!! $errors->first('to', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                    </div>
                    
                    
                    <!--<div class="input-group form-group">
                        <div class="input-group-prepend">
                        </div>
                        
                        <input autocomplete="off" class="form-control" id="field1" name="prof1" type="text" placeholder="Type something" data-items="8"/>

                    </div>-->
                    <div id="field">
                        
                        @isset($trip->stopsdet)
                            @foreach($trip->stopsdet as $key => $stopDetail)
                                <div class="form-row" id="field{{$loop->iteration}}">

                                    <div class="form-group col-md-6">
                                        <label for="inputZip">Time</label>
                                        <div class="input-group">
                                            @if(!$loop->first)
                                            <div class="input-group-prepend">
                                              <button id="remove{{$loop->iteration}}" class="btn btn-danger remove-mee">-</button>
                                            </div>
                                            @endif
                                            <input type="text" class="form-control" id="inlineFormInputGroupTme{{$loop->iteration}}" placeholder="time" value="{{ $key }}" name="stime[]">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                      <label for="inputState">Stop</label>
                                      <select id="inputState{{$loop->iteration}}" class="form-control" name="sstop[]">

                                        @foreach($stops as $stop)
                                            <option value="{{$stop->id}}" @if(($stopDetail[0] == $stop->latitude) && ($stopDetail[1] == $stop->longitude)) selected="" @endif>{{$stop->name}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                </div>
                                @php $count++; @endphp
                            @endforeach
                        @endisset
                    </div>

                    <button type="button" class="btn btn-lg btn-block add-more">+</button>
                    <div id="mapid"></div>
                </div>
                <div class="card-footer">
                    <input type="submit" value="{{ __('trip.update') }}" class="btn btn-success">
                    <a href="{{ route('trips.show', $trip) }}" class="btn btn-link">{{ __('app.cancel') }}</a>
                    
                        <a href="{{ route('trips.edit', [$trip, 'action' => 'delete']) }}" id="del-bus-{{ $trip->id }}" class="btn btn-danger float-right">{{ __('app.delete') }}</a>
                    
                </div>
            </form>
        </div>
    </div>
</div>
@endif
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
    var next = {{$count}};
    $(".add-more").click(function(e){
        e.preventDefault();
         var addto = "#field";
        var addRemove = "#field" + (next);
        next = next + 1;
        //var newIn = '<div class="input-group form-group"><div class="input-group-prepend"><button id="remove' + (next - 1) + '" class="btn btn-danger remove-me" >-</button></div><input autocomplete="off" class="form-control" id="field' + next + '" name="field' + next + '" type="text"></div>';
        var newIn = '<div class="form-row" id="field' + next + '"> <div class="form-group col-md-6"> <label for="inputZip">Time</label> <div class="input-group"> <div class="input-group-prepend"> <button id="remove' + (next) + '" class="btn btn-danger remove-me">-</button> </div> <input type="text" class="form-control" id="inlineFormInputGrouptime' + next + '" placeholder="time" name="stime[]"> </div> </div> <div class="form-group col-md-6"> <label for="inputState">Stop</label> <select id="inputStop' + next + '" class="form-control" name="sstop[]"> @foreach($stops as $stop)<option value="{{$stop->id}}">{{$stop->name}}</option> @endforeach </select> </div> </div>';
        var newInput = $(newIn);
        //var removeBtn = '<div class="input-group-prepend"><button id="remove' + (next - 1) + '" class="btn btn-danger remove-me" >-</button></div>';
        //var removeButton = $(removeBtn);
        $(addto).append(newInput);
        //$(addRemove).after(removeButton);
        $("#field" + next).attr('data-source',$(addto).attr('data-source'));
        $("#count").val(next);  
        
            $('.remove-me').click(function(e){
                e.preventDefault();
                var fieldNum = this.id.charAt(this.id.length-1);
                var fieldID = "#field" + fieldNum;
                $(this).remove();
                $(fieldID).remove();
            });
    });
    

    $('.remove-mee').click(function(e){
                e.preventDefault();
                var fieldNum = this.id.charAt(this.id.length-1);
                var fieldID = "#field" + fieldNum;
                $(this).remove();
                $(fieldID).remove();
            });
});


</script>
@endsection


