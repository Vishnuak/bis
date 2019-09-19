<?php

namespace App\Http\Controllers\Api;

use App\Outlet;
use App\Trip;
use App\Stop;
use App\LiveData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Outlet as OutletResource;
use \stdClass;
use Illuminate\Support\Collection;

class OutletController extends Controller
{
    /**
     * Get outlet listing on Leaflet JS geoJSON data structure.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $from = date('H:i:s');
        //$stops = Trip::whereBetween('start', ['00:00:000', $from])->orWhereBetween('start', [$from, '23:59:59'])->get(); 
        $outlets = Trip::leftJoin('stops', function($join) {
                      $join->on('trips.start_lat', '=', 'stops.latitude');
                      $join->on('trips.start_long', '=', 'stops.longitude');
                    })->leftJoin('stops as s', function($join) {
                      $join->on('trips.end_lat', '=', 's.latitude');
                      $join->on('trips.end_long', '=', 's.longitude');
                    })->whereTime('start', '<=', $from)
                    ->WhereTime('end', '>=', $from)->get(['trips.*', 'stops.name as origin', 's.name as destination']); 

        $collection = new Collection();
        $final = new stdClass();

        foreach ($outlets as $key => $value) {

            $stops = json_decode($value->stops_details);

            foreach ($stops as $keyy => $val) {

                if(strtotime($keyy) >= strtotime($from)) {

                    $final->latitude = $val[0];
                    $final->longitude = $val[1];
                    $value->time = date('h:i:s a', strtotime($keyy));
                    $value->name = Stop::where('latitude', $val[0])->where('longitude', $val[1])->first();

                    $final->features = new OutletResource($value);
                    $collection[$key] = $final;
                    $collection->push($final);
                    $final = new stdClass();
                    break;
                }
            }
        }

        $geoJSONdata = $collection->map(function ($stop) {
            return [
                'type'       => 'Feature',
                'properties' => $stop->features,
                'geometry'   => [
                    'type'        => 'Point',
                    'coordinates' => [
                        $stop->longitude,
                        $stop->latitude,
                    ],
                ],
            ];
        });


        return response()->json([
            'type'     => 'FeatureCollection',
            'features' => $geoJSONdata
        ]);
    }

    public function stops($id)
    {
        
        $trip = Trip::find($id);
        $stops = json_decode($trip->stops_details);
        $live_data = LiveData::where('trip_id', $id)->latest()
                ->first();

        $collection = new Collection();

        $final = new stdClass();
        $i = 0;

        foreach ($stops as $key => $val) {
            $final->latitude = $val[0];
            $final->longitude = $val[1];
            
            $trip->time = date('h:i:s a', strtotime($key));
            $trip->name = Stop::where('latitude', $val[0])->where('longitude', $val[1])->first()->name;
            if(isset($live_data->latitude) && (null !== $live_data->latitude)){
                if ($live_data->latitude == $final->latitude && $live_data->longitude == $final->longitude) {
                    $final->last_updated =  '<tr><td>Arrived at '.$trip->name.' at '.date('h:i a', strtotime($live_data->time)).'</td></tr>';
                }
                else {
                    $final->last_updated = '';
                }
            }
            else {
                    $final->last_updated = '';
                }
            
            $final->features = '<div><strong>'.$trip->name.':</strong><br>'.$trip->time.'</div>';
            $final->details = '<tr><td>'.$trip->name.'</td><td>'.$trip->time.'</td></tr>';
            $collection[$i] = $final;
            $collection->push($final);

            $final = new stdClass();
            $i++;
        }

        $geoJSONdata = $collection->map(function ($stop) {
            return [
                'type'       => 'Feature',
                'properties' => $stop->features,
                'details'    => $stop->details,
                'last_updated'    => $stop->last_updated,
                'geometry'   => [
                    'type'        => 'Point',
                    'coordinates' => [
                        $stop->longitude,
                        $stop->latitude,
                    ],
                ],
            ];
        });
        return response()->json([
            'type'     => 'FeatureCollection',
            'features' => $geoJSONdata,
        ]);
    }


    public function stopslatlng($id)
    {
        
        $trip = Trip::find($id);
        $stops = json_decode($trip->stops_details);
        

        $collection = new Collection();

        $final = new stdClass();
        $i = 0;

        foreach ($stops as $key => $val) {
            $final->latitude = $val[0];
            $final->longitude = $val[1];
            
            $collection->push($final);

            $final = new stdClass();
            $i++;
        }

        return response()->json([
            'features' => $collection,
        ]);
    }

    public function updateLocation(Request $request)
    {

        /*request()->validate([
            'stopLat' => ['required'],
            'stopLat' => ['required'],
            'tripId' => ['required', 'integer'],
            'time' => ['required'],
        ]);*/

        $data = array();
        $data['latitude'] = request()->stopLat;
        $data['longitude'] = request()->stopLong;
        $data['trip_id'] = request()->trip;
        $data['time'] = date('y-m-d h:i:s', strtotime(request()->time));
        $data['accuracy'] = request()->accuracy;

        $liv_data = LiveData::create($data);
        return $liv_data;

        //return json_encode($data);
        /*var_dump($stopLat);
        echo "long";
        var_dump($stopLong);
        echo "tripid";
        var_dump($tripId);
        var_dump($time);
        echo "accuracy";
        dd($accuracy);*/


        //find stop id from stop lat and long


        //update live_data table

        //return true
    }


    public function bck(Request $request)
    {
        $outlets = Outlet::all();
        $geoJSONdata = $outlets->map(function ($outlet) {
            return [
                'type'       => 'Feature',
                'properties' => new OutletResource($outlet),
                'geometry'   => [
                    'type'        => 'Point',
                    'coordinates' => [
                        $outlet->longitude,
                        $outlet->latitude,
                    ],
                ],
            ];
        });
        return response()->json([
            'type'     => 'FeatureCollection',
            'features' => $geoJSONdata,
        ]);
    }
}
