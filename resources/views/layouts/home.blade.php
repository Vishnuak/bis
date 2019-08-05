<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BIS') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
<style>
    #mapid { min-height: 400px; }
    .green-gradient {
            background-image: linear-gradient(60deg, rgba(167, 251, 188, 0.78) 37%, #cdf283 100%);
    }

    .dusty-green-gradient {
            background-image: linear-gradient(120deg, #c9f763 0%, #43ff5d 100%);
    }
    .btn-group-sm>.btn, .btn-sm {
            border-radius: 0.9rem;
    font-size: medium;
    }
</style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel" style="    background-image: linear-gradient(120deg, #d4fc79 0%, #96e6a1 100%);">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'BIS') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        {{--<li class="nav-item"><a href="https://github.com/vishnuak/bis" class="btn btn-outline-primary btn-sm" target="_blank">Source code</a></li>--}}
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        {{--<li class="nav-item"><a class="nav-link" href="{{ route('outlet_map.index') }}">{{ __('menu.stops') }}</a></li>--}}
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                @if (Route::has('register'))
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                @endif
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('stops.index') }}">{{ __('stop.list') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('buses.index') }}">{{ __('bus.list') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('trips.index') }}">{{ __('trip.list') }}</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="nav-scroller bg-white shadow-sm">
            <div class="container p-3">
                <div class="col-xs-12" style="    margin: 0 auto;
    width: 50%;">
            <form class="form-inline" >

  <label class="sr-only" for="inlineFormInputName2">Stops</label>
  <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="Stops">

  <label class="sr-only" for="inlineFormInputGroupUsername2">Stops</label>
  <div class="input-group mb-2 mr-sm-2">
    <input type="text" class="form-control" id="inlineFormInputGroupUsername2" placeholder="Stops">
  </div>

<button type="submit" class="btn btn-outline-success mb-2">Find Bus</button>
</form></div>
</div>
        </div>

        <main class="py-4 container-fluid">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="card p-2 shadow-sm p-3 mb-5 bg-white rounded">
                            <div class="card-body text-center">
                                <h4><span class="float-left"> Origin </span><span class="text-muted h6">To</span><span class="float-right"> Destination</span></h4>
                                <h5 class="pb-4" style="border-bottom: 1px solid #c7b6b6;"><span class="float-left">Time </span> <span class="float-right"> End time</span></h5>
                                <button type="button" class="btn btn-outline-success btn-round-sm btn-sm float-right">View Stops</button>
                                <span class="float-left"> Live stop : <span class="text-success"> NYY</span> </span>

                            </div>
                        </div>
                        <div class="card p-2 shadow-sm p-3 mb-5 bg-white rounded">
                            <div class="card-body text-center">
                                <h4><span class="float-left"> Origin </span><span class="text-muted h6">To</span><span class="float-right"> Destination</span></h4>
                                <h5 class="pb-4" style="border-bottom: 1px solid #c7b6b6;"><span class="float-left">Time </span> <span class="float-right"> End time</span></h5>
                                <button type="button" class="btn btn-outline-success btn-round-sm btn-sm float-right">View Stops</button>
                                <span class="float-left"> Live stop : <span class="text-success"> NYY</span> </span>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7 bg-white">
                     <div class="card-body" id="mapid"></div>
                    </div>
                </div>
            </div>
         
        </main>
        @include('layouts.partials.footer')
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
    <script type="text/javascript">
        var map = L.map('mapid').setView([{{ config('leaflet.map_center_latitude') }}, {{ config('leaflet.map_center_longitude') }}], {{ config('leaflet.zoom_level') }});
    var baseUrl = "{{ url('/') }}";

    L.tileLayer('http://{s}.www.toolserver.org/tiles/bw-mapnik/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
            maxZoom: 28
        }).addTo(map);
    cor = [];
axios.get('/api/stops/1')
            .then(function (result) {
                var a = result.data.features;


    //console.log(a.features);
a.forEach(function(entry, index) {
        console.log(entry.geometry.coordinates[0]);
        cor[index] = L.latLng(entry.geometry.coordinates[1], entry.geometry.coordinates[0]);


});
console.log(cor[0]);
  /* L.Routing.control({
  waypoints: [L.latLng(cor[1].lng, cor[1].lat), L.latLng(cor[4].lng, cor[4].lat)]
}).addTo(map);*/

   
               /*     L.Routing.control({
                    waypoints: cor
                    }).addTo(map);  
                    /*console.log(extractedPoints[j-1], extractedPoints[j]),L.latLng(extractedPoints[j+1], extractedPoints[j+2]);*/
                   /* map.setZoom(14);**/
                
   /*L.Routing.control({
  waypoints: [
        L.latLng(8.3979189, 77.087573),
        L.latLng(8.3891062, 77.1048201)
    ],
}).addTo(map);*/
});

            
    </script>
</body>
</html>
