<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingDetails extends Model
{
    public $timestamps = false;
    
    protected $guarded = [];

    public function vehicleExtra()
    {
        return $this->belongsTo('App\VehicleExtra', 'extra_service_id');
    }
}
