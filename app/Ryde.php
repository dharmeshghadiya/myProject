<?php

namespace App;

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
        return $this->belongsTo('App\Brand', 'brand_id');
    }

    public function modelYear()
    {
        return $this->belongsTo('App\ModelYear', 'model_year_id');
    }

    public function color()
    {
        return $this->belongsTo('App\Color', 'color_id');
    }

    public function rydeInstance()
    {
        return $this->hasOne('App\RydeInstance', 'ryde_id');
    }
    public function vehicleOptions()
    {
        return $this->hasMany('App\VehicleOption','vehicles_id');
    }

}
