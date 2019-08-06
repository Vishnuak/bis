@extends('layouts.home')

@section('content')
  <div class="nav-scroller bg-white shadow-sm">
    <div class="container p-3">
        <div class="col-xs-12" style="margin: 0 auto;width: 50%;">
            <form class="form-inline" action="{{url("/")}}" method="POST">
              {{ csrf_field() }}

              <label class="sr-only" for="inlineFormInputName2">Stops</label>
              <select id="from" name="from" class="form-control mb-2 mr-sm-2" onchange="hideTo(this.value)">
                <option value="" disabled selected>From</option>
                @foreach($stops as $stop)
                  <option value="{{$stop->id}}" class="{{$stop->id}}">{{$stop->name}}</option>
                @endforeach
              </select>

              <label class="sr-only" for="inlineFormInputGroupUsername2">Stops</label>
              <div class="input-group mb-2 mr-sm-2" onchange="hideFrom(this.value)">
                <select id="to" name="to" class="form-control">
                  <option value="" disabled selected>To</option>
                  @foreach($stops as $stop)
                    <option value="{{$stop->id}}" class="{{$stop->id}}">{{$stop->name}}</option>
                  @endforeach
                </select>
              </div>

                <input type="submit" value="Find Bus" name="listbus" class="btn btn-outline-success mb-2">
            </form>
        </div>
    </div>
  </div>

  <main class="py-4 container-fluid">
    @if ($errors->any() || Session::has('warning'))
    <div class="card">
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      @if(Session::has('warning')) 
        <div class="alert alert-danger">
          <ul>
            <li>{{Session::get('warning')}}</li>
        </ul>
      </div>
      @endif
    </div>
    @endif

    <div class="container-fluid">
      <div class="row">
          <div class="col-sm-12 col-md-5" style="max-height: 500px;overflow-y: scroll;">
            @isset($trips)
              <div class="card p-2 shadow-sm p-1 mb-1 bg-white rounded">
                @if(count($trips) > 0)<h5 class="card-header">Buses between <strong>{{$trips->frominput}}</strong> and<strong> {{$trips->toinput}}</strong></h5>@else<h5 class="card-header">No buses running between <strong>{{$trips->frominput}}</strong> and<strong> {{$trips->toinput}}</strong></h5>@endif
              
                @foreach($trips as $trip)
                  <div class="card p-2 shadow-sm p-1 mb-1 bg-white rounded">
                      <div class="card-body text-center">
                          <h5><span class="float-left"> {{$trip->origin}} </span><span class="text-muted h6">To</span><span class="float-right"> {{$trip->destination}}</span></h5>
                          <h6 class="pb-4" style="border-bottom: 1px solid #c7b6b6;"><span class="float-left">{{date('h:i:s a', strtotime($trip->start))}} </span> <span class="float-right"> {{date('h:i:s a', strtotime($trip->end))}}</span></h6>
                          <button type="button" class="btn btn-outline-success btn-round-sm btn-sm float-right" onclick="listStops({{$trip->id}})">View Stops</button>
                          <!--only applicable to time based query (listing live buses)-->
                          {{--<span class="float-left"> Live stop : <span class="text-success"> NYY</span> </span>--}}

                      </div>
                  </div>
                @endforeach
              
              </div>
            @endisset
          </div>
          <div class="col-sm-12 col-md-7 bg-white">
            <div class="card-body" id="mapid"></div>
          </div>
      </div>
    </div>
   
  </main>

    
@endsection

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
    integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
    crossorigin=""/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />

<style>
    #mapid { min-height: 500px; }
</style>
@endsection
@push('scripts')
<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
    integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
    crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

<script>

    var map = L.map('mapid').setView([{{ config('leaflet.map_center_latitude') }}, {{ config('leaflet.map_center_longitude') }}], {{ config('leaflet.zoom_level') }});
    var baseUrl = "{{ url('/') }}";

    L.tileLayer('http://{s}.www.toolserver.org/tiles/bw-mapnik/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
            maxZoom: 28
        }).addTo(map);
var markerGroup = L.layerGroup().addTo(map);
    axios.get('{{ route('api.outlets.index') }}')
    .then(function (response) {
        console.log(response.data);
        L.geoJSON(response.data, {
            pointToLayer: function(geoJsonPoint, latlng) {
                return L.marker(latlng).addTo(markerGroup);
            }
        })
        .bindPopup(function (layer) {
            console.log(layer.feature.properties);
            var mpopup = '<div class="my-2"><strong>'+layer.feature.properties.name.name+':</strong><br>'+layer.feature.properties.time+'</div>'
            return mpopup;
        }).addTo(map);
    })
    .catch(function (error) {
        console.log(error);
    });
var legend = L.control({position: 'topright'});
legend.onAdd = function (map) {
    var div = L.DomUtil.create('div', 'info legend');
    /*div.innerHTML = '<form action="{{url("/")}}" method="POST">{{ csrf_field() }}<select name="from"><option value="" disabled selected>From</option>@foreach($stops as $stop)<option value="{{$stop->id}}">{{$stop->name}}</option>@endforeach</select><select name="to"><option value="" disabled selected>To</option>@foreach($stops as $stop)<option value="{{$stop->id}}">{{$stop->name}}</option>@endforeach</select><input type="submit" value="submit" name="listbus"></form>';*/
    //div.firstChild.onmousedown = div.firstChild.ondblclick = L.DomEvent.stopPropagation;
    return div;
};
legend.addTo(map);
/*$('select').change(function(){
    alert('changed');
});*/
    @can('create', new App\Outlet)
    var theMarker;

    map.on('click', function(e) {
        let latitude = e.latlng.lat.toString().substring(0, 15);
        let longitude = e.latlng.lng.toString().substring(0, 15);

        if (theMarker != undefined) {
            map.removeLayer(theMarker);
        };

        var popupContent = "Your location : " + latitude + ", " + longitude + ".";
        popupContent += '<br><a href="{{ route('stops.create') }}?latitude=' + latitude + '&longitude=' + longitude + '">Add new stop here</a>';

        theMarker = L.marker([latitude, longitude]).addTo(map);
        theMarker.bindPopup(popupContent)
        .openPopup();
    });
    @endcan
</script>

<script type="text/javascript">
var route = null;
var size = null;
    function listStops(x) {

        if(isNumber(x)) {
/*route = L.Routing.control({
                    waypoints: []
                    });
map.removeControl(route);*/
            axios.get('/api/stops/'+x)
            .then(function (result) {
                console.log(result.data);
                markerGroup.clearLayers();
                    cor = [];
                    
                var a = result.data.features;
                L.geoJSON(result.data, {
                    pointToLayer: function(geoJsonPoint, latlng) {
                      /*waypointss = L.Routing.waypoint(geoJsonPoint.geometry.coordinates[0], geoJsonPoint.geometry.coordinates[1]);*/

//console.log(geoJsonPoint.geometry.coordinates[0]);

                    

                      
                        return L.marker(latlng).addTo(markerGroup);
                    }
                })
                .bindPopup(function (result) {
                    //console.log(result.feature.properties);
                    //
                    //waypoints[] = L.latLng(result.feature.geometry.coordinates[0]
                    var mpopup = result.feature.properties;
                    return mpopup;
                }).addTo(map);
                a.forEach(function(entry, index) {
       // console.log(entry.geometry.coordinates[0]);
        cor[index] = L.latLng(entry.geometry.coordinates[1], entry.geometry.coordinates[0]);


});
            /*for(var j = 1; j < cor.length; j+=2){
                    L.Routing.control({
                    waypoints: [L.latLng(cor[j-1].lng, cor[j-1].lat),L.latLng(cor[j+1].lng, cor[j+1].lat)],show: false, createMarker: function() { return null; }}).addTo(map);  
                    /*console.log(extractedPoints[j-1], extractedPoints[j]),L.latLng(extractedPoints[j+1], extractedPoints[j+2]);*/
                    /*map.setZoom(14);
                }*/
                
                if (route != null) {
                  //alert(size);
                  route.spliceWaypoints(0, size);
                }
                 route = L.Routing.control({
                    waypoints: cor,
                    show: false, createMarker: function() { return null; }
                    }).addTo(map);  
                    /*console.log(extractedPoints[j-1], extractedPoints[j]),L.latLng(extractedPoints[j+1], extractedPoints[j+2]);*/
                    map.setZoom(12);
                   size = cor.length;
            })
            .catch(function (error) {
                console.log(error);
            });


        }
    }

    //check numeric
    function isNumber(n) {
      return !isNaN(parseFloat(n)) && isFinite(n);
    }
</script>

  <script type="text/javascript">
    function hideFrom(y) 
    {
      $("#from option").show();
      $("#from option[value=" + y + "]").hide();
      /*$("."+y).css("display","block");*/
      var dropDown = document.getElementById("from");
      if (dropDown.selectedIndex == y) {
        dropDown.selectedIndex = 0;
      }
      
    }
  </script>
  <script type="text/javascript">
    function hideTo(y) 
    {
      $("#to option").show();
      $("#to option[value=" + y + "]").hide();
      /*$("."+y).css("display","block");*/
      var dropDown = document.getElementById("to");
      if (dropDown.selectedIndex == y) {
        dropDown.selectedIndex = 0;
      }
      
    }
  </script>



@endpush
