<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'latitude', 'longitude',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    /*public $appends = [
        'coordinate', 'map_popup_content',
    ];*/
}
