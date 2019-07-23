@extends('layouts.app')

@section('content')
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
    <div class="card-body" id="mapid"></div>

</div>
    @isset($trips)

        <div class="container pt-2">
    <div class="row">
        <table class="table table-bordered dt-responsive nowrap" id="table"  style="width:100%">


            <thead>
                <tr>
                  <th scope="col">Bus</th>
                  <th scope="col">Owned By</th>
                  <th scope="col">Number</th>
                  <th scope="col">Type</th>
                  <th scope="col">Time</th>
                  <th scope="col">Time</th>
                  <th scope="col">From</th>
                  <th scope="col">To</th>
                  <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                    @foreach($trips as $trip)
                        <tr>
                            <td> {{$trip->name}}</td>
                            <td> {{$trip->owner}}</td>
                            <td> {{$trip->number}}</td>
                            <td> {{$trip->type}}</td>
                          <td> {{date('h:i:s a', strtotime($trip->start))}}</td>
                          <td>{{date('h:i:s a', strtotime($trip->end))}}</td>
                          <td>{{$trip->start_lat . ', ' .  $trip->start_long}}</td>
                          <td>{{$trip->end_lat . ', ' .  $trip->end_long}}</td>
                          <td><button onclick="listStops({{$trip->id}})">view stops</button></td>
                        </tr>
                    @endforeach

            </tbody>
        </table>
    </div>
</div>


    @endisset
@endsection

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
    integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
    crossorigin=""/>

<style>
    #mapid { min-height: 500px; }
</style>
@endsection
@push('scripts')
<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
    integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
    crossorigin=""></script>

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
    div.innerHTML = '<form action="{{url("/")}}" method="POST">{{ csrf_field() }}<select name="from"><option value="" disabled selected>From</option>@foreach($stops as $stop)<option value="{{$stop->id}}">{{$stop->name}}</option>@endforeach</select><select name="to"><option value="" disabled selected>To</option>@foreach($stops as $stop)<option value="{{$stop->id}}">{{$stop->name}}</option>@endforeach</select><input type="submit" value="submit" name="listbus"></form>';
    div.firstChild.onmousedown = div.firstChild.ondblclick = L.DomEvent.stopPropagation;
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
        popupContent += '<br><a href="{{ route('outlets.create') }}?latitude=' + latitude + '&longitude=' + longitude + '">Add new outlet here</a>';

        theMarker = L.marker([latitude, longitude]).addTo(map);
        theMarker.bindPopup(popupContent)
        .openPopup();
    });
    @endcan
</script>

<script type="text/javascript">
    function listStops(x) {

        if(isNumber(x)) {

            axios.get('/api/stops/'+x)
            .then(function (result) {
                console.log(result.data);
                markerGroup.clearLayers();
                L.geoJSON(result.data, {
                    pointToLayer: function(geoJsonPoint, latlng) {
                        return L.marker(latlng).addTo(markerGroup);
                    }
                })
                .bindPopup(function (result) {
                    console.log(result.feature.properties);
                    var mpopup = result.feature.properties;
                    return mpopup;
                }).addTo(map);
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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css">

    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script type="text/javascript">
        
        $(document).ready(function() {
            var table = $('#table').DataTable( {
                fixedHeader: true
            } );
        } );
    </script>

<script src="https://unpkg.com/leaflet-ant-path" type="text/javascript"></script>
    <script type="text/javascript">

        /*var pointA = new L.LatLng(28.635308, 77.22496);
var pointB = new L.LatLng(28.984461, 77.70641);
var pointList = [pointA, pointB];

var firstpolyline = new L.Polyline(pointList, {
    color: 'red',
    weight: 3,
    opacity: 0.5,
    smoothFactor: 1
});
firstpolyline.addTo(map);
 //This is a example, the JSON can come from any place

const path = new L.Polyline.AntPath([
  [8.3943892454993, 77.104067802429],
  [8.4027131026976, 77.086064815521]], {
  "delay": 400,
  "dashArray": [
    10,
    20
  ],
  "weight": 5,
  "color": "#0000FF",
  "pulseColor": "#FFFFFF",
  "paused": false,
  "reverse": false,
  "hardwareAccelerated": true
});


map.addLayer(path);
map.fitBounds(path.getBounds());*/
    </script>
@endpush
