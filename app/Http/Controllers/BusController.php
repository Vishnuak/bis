<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bus;

class BusController extends Controller
{
    public function __construct() {

    	$this->middleware('auth');

    }

    public function index()
    {
        $busQuery = Bus::query();
        $busQuery->where('name', 'like', '%'.request('q').'%');
        $buses = $busQuery->paginate(25);
        return view('buses.list', [
            'buses' => $buses
        ]);
    }

    public function create()
    {
        return view('buses.create');
    }

    public function store(Request $request)
    {

        $newBus = $request->validate([
            'name'      => 'required|max:191',
            'owner'     => 'required|max:191',
            'number'    => 'max:191',
            'type'      => 'required|max:191',
        ]);

        $bus = Bus::create($newBus);

        return redirect('/buses');
    }

    public function show(Bus $bus)
    {
        return view('buses.show', compact('bus'));
    }

    public function edit($bus)
    {
        $bus = Bus::find($bus);

        return view('buses.edit', [
            'bus' => $bus
        ]);
    }

    public function update(Request $request, Bus $bus)
    {

        $busData = $request->validate([
            'name'      => 'required|max:191',
            'owner'     => 'required|max:191',
            'number'    => 'max:191',
            'type'      => 'required|max:191',
        ]);
        $bus->update($busData);

        return redirect('/buses');
    }

    public function destroy(Request $request, Bus $bus)
    {

        $request->validate(['bus_id' => 'required']);

        if ($request->get('bus_id') == $bus->id && $bus->delete()) {
            return redirect()->route('buses.index');
        }

        return back();
    }
}
