<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleOption extends Model
{
    protected $guarded = [];

    public function option()
    {
        return $this->belongsTo('App\Models\Option', 'option_id');
    }
}
