<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDetails extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function vehicleExtra()
    {
        return $this->belongsTo('App\Models\VehicleExtra', 'extra_service_id');
    }
}
