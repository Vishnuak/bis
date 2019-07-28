<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bus;
use App\Stop;
use App\Trip;

class TripController extends Controller
{
    public function __construct() {

    	$this->middleware('auth');

    }

    public function index()
    {
        /*$tripQuery = Trip::query();
        $tripQuery->where('name', 'like', '%'.request('q').'%');
        $trips = $tripQuery->paginate(25);*/
        $stops = Stop::all();
        $trips = Trip::leftJoin('stops', function($join) {
                  $join->on('trips.start_lat', '=', 'stops.latitude');
                  $join->on('trips.start_long', '=', 'stops.longitude');
                })->leftJoin('stops as s', function($join) {
                  $join->on('trips.end_lat', '=', 's.latitude');
                  $join->on('trips.end_long', '=', 's.longitude');
                })->leftJoin('buses as b', 'b.id', '=', 'trips.bus_id')->paginate(25, ['trips.*', 'b.name', 'b.owner', 'b.number', 'b.type', 'stops.name as origin', 's.name as destination']);
            //dd($trips);
        return view('trips.list', [
            'trips' => $trips
        ]);
    }

    public function create()
    {
    	$stops = Stop::all();
    	$buses = Bus::all();
        return view('trips.create', [
        	'stops' => $stops ,
        	'buses' => $buses 
        ]);
    }

    public function store(Request $request)
    {

        $newTrip = $request->validate([
        	'bus_id'     => 'required|integer',
            'start'      => 'required|max:191',
            'end'     	 => 'required|max:191',
            'from'     	 => 'required|max:191',
            'to'     	 => 'required|max:191',
        ]);

        $origin = Stop::find($request->from);
        $destination = Stop::find($request->to);

        $newTrip['start_lat'] = $origin->latitude;
        $newTrip['start_long'] = $origin->longitude;

		$newTrip['end_lat'] = $destination->latitude;
        $newTrip['end_long'] = $destination->longitude;        

        //$newTrip['bus_id'] = 1;
        $stopDetails = [];
        $stopDetails[$request->start][] = $origin->latitude;
        $stopDetails[$request->start][] = $origin->longitude;
        $newTrip['stops_details'] = json_encode($stopDetails);

        //$request->start_lat = $origin

        $trip = Trip::create($newTrip);

        return redirect('/trips');
    }

    public function show($trip)
    {
    	$trip = Trip::leftJoin('stops', function($join) {
                  $join->on('trips.start_lat', '=', 'stops.latitude');
                  $join->on('trips.start_long', '=', 'stops.longitude');
                })->leftJoin('stops as s', function($join) {
                  $join->on('trips.end_lat', '=', 's.latitude');
                  $join->on('trips.end_long', '=', 's.longitude');
                })->leftJoin('buses as b', 'b.id', '=', 'trips.bus_id')->where('trips.id', $trip)->get(['trips.*', 'b.name', 'b.owner', 'b.number', 'b.type', 'stops.name as origin', 's.name as destination'])->first();

        return view('trips.show', compact('trip'));
    }

    public function edit($trip)
    {
        $trip = Trip::leftJoin('stops', function($join) {
                  $join->on('trips.start_lat', '=', 'stops.latitude');
                  $join->on('trips.start_long', '=', 'stops.longitude');
                })->leftJoin('stops as s', function($join) {
                  $join->on('trips.end_lat', '=', 's.latitude');
                  $join->on('trips.end_long', '=', 's.longitude');
                })->leftJoin('buses as b', 'b.id', '=', 'trips.bus_id')->where('trips.id', $trip)->get(['trips.*', 'b.name', 'b.owner', 'b.number', 'b.type', 'stops.name as origin', 's.name as destination', 'stops.id as originid', 's.id as destid'])->first();

        $trip->stopsdet = json_decode($trip->stops_details);

        $stops = Stop::all();
        $buses = Bus::all();

        return view('trips.edit', [
            'trip' 	=> $trip,
            'buses' => $buses,
            'stops' => $stops
        ]);
    }

    public function update(Request $request, Trip $trip)
    {

    	//dd($request->sstop);
        $tripData = $request->validate([
        	'bus_id'     => 'required|integer',
            'start'      => 'required|max:191',
            'end'     	 => 'required|max:191',
            'from'     	 => 'required|max:191',
            'to'     	 => 'required|max:191',
        ]);

        $origin = Stop::find($request->from);
        $destination = Stop::find($request->to);

        $tripData['start_lat'] = $origin->latitude;
        $tripData['start_long'] = $origin->longitude;

		$tripData['end_lat'] = $destination->latitude;
        $tripData['end_long'] = $destination->longitude;        

        //$tripData['bus_id'] = 1;
        $stopDetails = [];

        $stops = Stop::all()->keyBy('id');

        if(count($request->stime)>0) {
        	foreach ($request->stime as $key => $value) {
	        	/*echo $value . " => " . $stops[$request->sstop[$key]]->latitude . ' ('. $request->sstop[$key] .')';
	        	echo PHP_EOL;*/
	        	$stopDetails[$value][] =  $stops[$request->sstop[$key]]->latitude;
	        	$stopDetails[$value][] =  $stops[$request->sstop[$key]]->longitude;
	        }
        }
        else {

        	$stopDetails[$request->start][] = $origin->latitude;
        	$stopDetails[$request->start][] = $origin->longitude;

        }

        $tripData['stops_details'] = json_encode($stopDetails);
		
		$trip->update($tripData);

        return redirect('/trips');
    }

    public function destroy(Request $request, Trip $trip)
    {

        $request->validate(['Trip_id' => 'required']);

        if ($request->get('Trip_id') == $trip->id && $trip->delete()) {
            return redirect()->route('trips.index');
        }

        return back();
    }
}
