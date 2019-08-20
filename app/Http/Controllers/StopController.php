<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stop;

class StopController extends Controller
{
    public function __construct() {

    	$this->middleware('auth');

    }

    public function index()
    {
        $stopQuery = Stop::query();
        $stopQuery->where('name', 'like', '%'.request('q').'%');
        $stops = $stopQuery->paginate(25);
        
        //$stops = Stop::paginate(10);
        return view('stops.list', [
            'stops' => $stops
        ]);
    }

    public function create()
    {
        return view('stops.create');
    }

    public function store(Request $request)
    {

        $newStop = $request->validate([
            'name'      => 'required|max:60',
            'latitude'  => 'nullable|required_with:longitude|max:15',
            'longitude' => 'nullable|required_with:latitude|max:15',
        ]);
        //$newStop['creator_id'] = auth()->id();

        $stop = Stop::create($newStop);

        return redirect('/stops');
    }

    public function show(Stop $stop)
    {
        return view('stops.show', compact('stop'));
    }

    public function edit($stop)
    {
        $stop = Stop::find($stop);


        return view('stops.edit', [
            'stop' => $stop
        ]);
    }

    public function update(Request $request, Stop $stop)
    {

        $stopData = $request->validate([
            'name'      => 'required|max:60',
            'latitude'  => 'nullable|required_with:longitude|max:15',
            'longitude' => 'nullable|required_with:latitude|max:15',
        ]);
        $stop->update($stopData);

        return redirect('/stops');
    }

    public function destroy(Request $request, Stop $stop)
    {

        $request->validate(['stop_id' => 'required']);

        if ($request->get('stop_id') == $stop->id && $stop->delete()) {
            return redirect()->route('stops.index');
        }

        return back();
    }
}
