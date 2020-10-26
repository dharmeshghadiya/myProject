<?php

namespace App;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class VehicleNotAvailable extends Model
{
    protected $table = 'vehicle_not_availability';
    protected $guarded = [];

    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle', 'vehicle_id');
    }
}
