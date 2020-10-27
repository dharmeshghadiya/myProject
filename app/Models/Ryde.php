<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ryde extends Model
{
    use Translatable;
    use SoftDeletes;

    public $translatedAttributes = ['name'];

    protected $guarded = [];

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'brand_id');
    }

    public function modelYear()
    {
        return $this->belongsTo('App\Models\ModelYear', 'model_year_id');
    }

    public function color()
    {
        return $this->belongsTo('App\Models\Color', 'color_id');
    }

    public function rydeInstance()
    {
        return $this->hasOne('App\Models\RydeInstance', 'ryde_id');
    }
    public function vehicleOptions()
    {
        return $this->hasMany('App\Models\VehicleOption','vehicles_id');
    }

}
