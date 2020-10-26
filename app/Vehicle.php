<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;

    protected $guarded = [];


    public function companies()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }

    public function companyAddress()
    {
        return $this->belongsTo('App\CompanyAddress', 'company_address_id');
    }

    public function ryde()
    {
        return $this->belongsTo('App\Ryde', 'ryde_id');
    }

    public function insurances()
    {
        return $this->belongsTo('App\Insurance', 'insurance_id');
    }

    public function vehicleExtra()
    {
        return $this->hasMany('App\VehicleExtra', 'vehicle_id');
    }

    public function vehicleFeature()
    {
        return $this->hasMany('App\VehicleFeature', 'vehicle_id');
    }

    public function categoryVehicle()
    {
        return $this->hasMany('App\CategoryVehicle', 'vehicle_id');
    }

    public function engine()
    {
        return $this->belongsTo('App\Engine', 'engine_id');
    }

    public function fuel()
    {
        return $this->belongsTo('App\Fuel', 'fuel_id');
    }

    public function gearbox()
    {
        return $this->belongsTo('App\Gearbox', 'gearbox_id');
    }


    public function vehicleOptions()
    {
        return $this->hasMany('App\VehicleOption', 'vehicles_id');
    }

}
