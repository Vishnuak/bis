<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bus_id', 'start', 'end', 'start_lat', 'start_long', 'end_lat', 'end_long', 'stops_details',
    ];

    /*public $appends = [
        'start_lat', 'start_long', 'end_lat', 'end_long', 'stops_details',
    ];

    public function setStartLatAttribute()
    {
        $this->attributes['start_lat'] = $this->origin->latitude;
    }

    public function setStartLongAttribute()
    {
        $this->attributes['start_long'] = $this->origin->longitude;
    }

    public function setEndLatAttribute()
    {
        $this->attributes['end_lat'] = $this->destination->latitude;
    }

    public function setEndLongAttribute()
    {
        $this->attributes['end_long'] = $this->destination->longitude;
    }

    public function setStopsDetailsAttribute()
    {
        $this->attributes['stops_details'] = 'sdfsdf';
    }*/
}
