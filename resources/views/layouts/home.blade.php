<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NEYYARTC') }}</title>

    <link href="https://getbootstrap.com/docs/4.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <style>
        #mapid { min-height: 800px; }
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
        .leaflet-control-container .leaflet-routing-container-hide {
            display: none;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel" style="    background-image: linear-gradient(120deg, rgb(140, 224, 104) 25%, rgb(150, 230, 161) 50%);">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}" style="color: white;font-size: x-large;">
                    {{ config('app.name', 'NEYYARTC') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        {{--<li class="nav-item"><a class="nav-link" href="{{ route('outlet_map.index') }}">{{ __('menu.stops') }}</a></li>--}}
                        @guest
                           <!-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                @if (Route::has('register'))
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                @endif
                            </li>-->
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

        @yield('content')
        
        @include('layouts.partials.footer')
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
    {{--<script type="text/javascript">
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

            
    </script>--}}
</body>
</html>
