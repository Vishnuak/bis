<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiveData extends Model
{
    protected $fillable = [
        'latitude', 'longitude', 'trip_id', 'time', 'accuracy',
    ];
}
