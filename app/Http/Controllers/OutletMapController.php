<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stop;
use App\Trip;

class OutletMapController extends Controller
{
    /**
     * Show the outlet listing in LeafletJS map.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $stops = Stop::orderBy('name')->get();
        $trips = null;
        if(isset($request->listbus)) {
            request()->validate([
                'from' => ['required', 'integer'],
                'to' => ['required', 'integer'],
            ]);
            if(request()->from !== request()->to) {
                $trips = Trip::where(function ($q) use ($stops) {
                foreach ($stops as $key => $stop) {
                    if(($stop->id == request()->from) OR ($stop->id == request()->to)) {
                        $q->where('trips.stops_details', 'like', "%{$stop->latitude}%");
                        $q->where('trips.stops_details', 'like', "%{$stop->longitude}%");
                    }
                }
            })->leftJoin('stops', function($join) {
                  $join->on('trips.start_lat', '=', 'stops.latitude');
                  $join->on('trips.start_long', '=', 'stops.longitude');
                })->leftJoin('stops as s', function($join) {
                  $join->on('trips.end_lat', '=', 's.latitude');
                  $join->on('trips.end_long', '=', 's.longitude');
                })->leftJoin('buses as b', 'b.id', '=', 'trips.bus_id')->orderBy('trips.start')->get(['trips.*', 'b.name', 'b.owner', 'b.number', 'b.type', 'stops.name as origin', 's.name as destination']);
                foreach ($stops as $key => $stop) {
                  if(($stop->id == request()->from)) {
                    $trips->frominput = $stop->name;
                  }
                  if(($stop->id == request()->to)) {
                    $trips->toinput = $stop->name;
                  }
                }
            }
            else {
                request()->session()->flash('warning', 'From and To cannot be same');
            }
        }

        return view('outlets.map', [
            'stops' => $stops,
            'trips' => $trips
        ]);
    }

    public function routing()
    {
        return view('layouts.home');
    }
}
