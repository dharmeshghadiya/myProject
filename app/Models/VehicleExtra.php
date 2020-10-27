<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class VehicleExtra extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function extra()
    {
        return $this->belongsTo('App\Models\GlobalExtra', 'global_extra_id');
    }
}
