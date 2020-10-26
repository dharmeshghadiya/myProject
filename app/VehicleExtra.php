<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class VehicleExtra extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function extra()
    {
        return $this->belongsTo('App\GlobalExtra', 'global_extra_id');
    }
}
