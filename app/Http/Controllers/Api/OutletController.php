<?php

namespace App\Http\Controllers\Api;

use App\Outlet;
use App\Trip;
use App\Stop;
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
        $outlets = Trip::whereTime('start', '<=', $from)
                    ->WhereTime('end', '>=', $from)->get(); 
                    //dd($outlets);
        //dd($outlets);
        //$to = date('Y-m-d');
        //$outlets = Outlet::all();
        $collection = new Collection();
        //$collection = array();
        $final = new stdClass();

        foreach ($outlets as $key => $value) {
            //dd($value->stops_details);
            $stops = json_decode($value->stops_details);
            //  var_dump($stops); exit();
            foreach ($stops as $keyy => $val) {
                //dd($keyy);
                //
                if(strtotime($keyy) >= strtotime($from)) {
                   /* $final = [
                        'latitude' => $val[0],
                        'longitude' => $val[1]];*/
                    $final->latitude = $val[0];
                    $final->longitude = $val[1];
                    $value->time = date('h:i:s a', strtotime($keyy));
                    $value->name = Stop::where('latitude', $val[0])->where('longitude', $val[1])->first();
                    //dd($value->name);
                    $final->features = new OutletResource($value);
                    //var_dump($final);
                    $collection[$key] = $final;
                    $collection->push($final);
                    $final = new stdClass();
                    break;
                }
            }
        }
        //dd($collection); 
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
        

        $collection = new Collection();

        $final = new stdClass();
        $i = 0;

        foreach ($stops as $key => $val) {
            $final->latitude = $val[0];
            $final->longitude = $val[1];
            $trip->time = date('h:i:s a', strtotime($key));
            $trip->name = Stop::where('latitude', $val[0])->where('longitude', $val[1])->first()->name;
            $final->features = '<div class="my-2"><strong>'.$trip->name.':</strong><br>'.$trip->time.'</div>';

            $collection[$i] = $final;
            $collection->push($final);

            $final = new stdClass();
            $i++;
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
            'features' => $geoJSONdata,
        ]);
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
