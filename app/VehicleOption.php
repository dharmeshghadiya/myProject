<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleOption extends Model
{
    protected $guarded = [];

    public function option()
    {
        return $this->belongsTo('App\Option', 'option_id');
    }
}
