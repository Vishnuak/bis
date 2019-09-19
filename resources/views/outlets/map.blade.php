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
              <div class="input-group mb-2 mr-sm-2">
                <select id="to" name="to" class="form-control"  onchange="hideFrom(this.value)">
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
                          <h6 class="pb-4" style="clear: both;border-bottom: 1px solid #c7b6b6;"><span class="float-left">{{date('h:i:s a', strtotime($trip->start))}} </span> <span class="float-right"> {{date('h:i:s a', strtotime($trip->end))}}</span></h6>
                          <div id="{{$trip->id}}" class="collapse">
                            
                          </div>

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
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
      integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
      crossorigin=""/>
      <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />

  <style>
      #mapid { min-height: 500px; }
</style>
@endsection
@push('scripts')
  <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
    integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
    crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

  <script>

    var map = L.map('mapid').setView([{{ config('leaflet.map_center_latitude') }}, {{ config('leaflet.map_center_longitude') }}], {{ config('leaflet.zoom_level') }});
    var baseUrl = "{{ url('/') }}";

    

    var googleTraffic = L.tileLayer('https://{s}.google.com/vt/lyrs=m@221097413,traffic&x={x}&y={y}&z={z}', 
      {
        maxZoom: 20,
        minZoom: 2,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
      }).addTo(map);


    var markerGroup = L.layerGroup().addTo(map);
    var busIcon = L.icon({
        iconUrl: '/icons/920352.png',
        iconSize: [40,40],
        popupAnchor: [10, 0],
      shadowSize: [0, 0],
      //className: 'animated-icon my-icon-id' 
      });




    axios.get('{{ route('api.outlets.index') }}')
    .then(function (response) {
        console.log(response.data);
        L.geoJSON(response.data, {
            pointToLayer: function(geoJsonPoint, latlng) {
                return L.marker(latlng,{icon: busIcon}).addTo(markerGroup);
            }
        })
        .bindPopup(function (layer) {
            console.log(layer.feature.properties);
            var mpopup = '<div class="my-2"><strong><u>'+layer.feature.properties.origin+' To '+layer.feature.properties.destination+'</u> </strong><br><strong>'+layer.feature.properties.name.name+':</strong><br>'+layer.feature.properties.time+'</div>'
            return mpopup;
        }).addTo(map);
    })
    .catch(function (error) {
        console.log(error);
    });

    /*--- start user location ----*/
    /*var watchID = navigator.geolocation.watchPosition(function(position) {   
      L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
    });*/
    /*--- end user location ----*/

    var legend = L.control({position: 'topright'});
    legend.onAdd = function (map) {
      var div = L.DomUtil.create('div', 'info legend');

      return div;
    };
    legend.addTo(map);

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
/*jQuery('button').click( function(e) {
    jQuery('.collapse > :not(#'+x')').collapse('hide');
});*/
    function listStops(x) {
      $('#'+x).collapse('show');
      jQuery('.collapse').not('#' + x).collapse('hide');
      var dff = document.getElementById(x);
dff.innerHTML = '<div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>';
        var datat = '';

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
//const div = document.createElement('tr');
                  //htmlposition.innerHTML += geoJsonPoint.details;
                  //document.getElementById(x).appendChild(div);

                    

                      
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
var htmlposition = document.getElementById(x);
htmlposition.innerHTML = '<div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>';
var datasd = '';
                a.forEach(function(entry, index) {
       // console.log(entry.geometry.coordinates[0]);
       if (index == 0) {
                        
datasd += '<table style="width: 100%;text-align: left;" id=""><thead style="font-weight: bold;"><td>Stop</td><td>Time</td></thead><tbody>';
       }
       datasd += entry.details;
       if (entry.last_updated !== '') {
        last_updated = entry.last_updated;
       }
        cor[index] = L.latLng(entry.geometry.coordinates[1], entry.geometry.coordinates[0]);


});
                if (typeof last_updated !== 'undefined') {
                datasd += last_updated;
               }
                datasd += '<tr class="p-4 border-top"><td>Are you inside this bus? </td><td><button type="button" class="btn btn-outline-danger btn-round-sm btn-sm" onclick="trackLocation('+x+')">No</button><button type="button" class="btn btn-outline-success btn-round-sm btn-sm m-2" onclick="trackLocation('+x+')">Yes</button></td></tr></tbody></table>';
                htmlposition.innerHTML = datasd;
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
//var htmlposition = document.getElementById(x);
//htmlposition.innerHTML += '</tbody></table>';

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

  <script type="text/javascript">
    function trackLocation(tripId) 
    {
        axios.get('/api/stopslatlng/'+tripId)
            .then(function (result) {
              navigator.geolocation.getCurrentPosition(function(position) {
                console.log("success");
              var userlat = position.coords.latitude;
              var userlong = position.coords.longitude;
              var accuracy = position.coords.accuracy;
              for(var entry of result.data.features) {
                console.log(entry.latitude);
              }
              //result.data.features.forEach(function(entry, index) {
                for(var entry of result.data.features) {
                //console.log(entry.latitude, entry.longitude);
                //console.log(userlat, userlong);

                console.log (L.latLng([entry.latitude, entry.longitude]).distanceTo([userlat, userlong]));
                if(L.latLng([entry.latitude, entry.longitude]).distanceTo([userlat, userlong]) < 500){
                    alert('near');
                    $.ajaxSetup({
                      headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      }
                    });
                    var stopLat = entry.latitude;
                    var stopLong = entry.longitude;
                    var trip = tripId;
                    var time = new Date().toLocaleString();
                    var token = $('input[name=_token]').val();
                    $.ajax({
                      url:"/api/updatelocation",
                      type: "post",
                      data: {_token: token, stopLat: stopLat, stopLong: stopLong, trip: trip, time: time, accuracy: accuracy},
                      success:function(result){
                        console.log(result);

                        /*if (result == true) {
                          alert('success');
                          return true;
                        }
                        else
                        {
                          alert('failed');
                          return true;
                        }*/
                      }
                    });
                    break;
                  }
              };
            });

            });
     // if (isNumber(tripId)) {
        //alert(a);
        //get location latlng
        /*navigator.geolocation.getCurrentPosition(function(position) {
          do_something(position.coords.latitude, position.coords.longitude);
        });*/
        //check distance with stops latlng


        //comapre the distance with defined buffer zone radius


        //send data to server if user is in the buffer zone


      //}
      
    }
  </script>



@endpush
