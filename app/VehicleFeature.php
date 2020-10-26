<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class VehicleFeature extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function feature()
    {
        return $this->belongsTo('App\Feature', 'feature_id');
    }
}
