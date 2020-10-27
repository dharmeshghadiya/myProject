<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;

    protected $guarded = [];


    public function companies()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function companyAddress()
    {
        return $this->belongsTo('App\Models\CompanyAddress', 'company_address_id');
    }

    public function ryde()
    {
        return $this->belongsTo('App\Models\Ryde', 'ryde_id');
    }

    public function insurances()
    {
        return $this->belongsTo('App\Models\Insurance', 'insurance_id');
    }

    public function vehicleExtra()
    {
        return $this->hasMany('App\Models\VehicleExtra', 'vehicle_id');
    }

    public function vehicleFeature()
    {
        return $this->hasMany('App\Models\VehicleFeature', 'vehicle_id');
    }

    public function categoryVehicle()
    {
        return $this->hasMany('App\Models\CategoryVehicle', 'vehicle_id');
    }

    public function engine()
    {
        return $this->belongsTo('App\Models\Engine', 'engine_id');
    }

    public function fuel()
    {
        return $this->belongsTo('App\Models\Fuel', 'fuel_id');
    }

    public function gearbox()
    {
        return $this->belongsTo('App\Models\Gearbox', 'gearbox_id');
    }


    public function vehicleOptions()
    {
        return $this->hasMany('App\Models\VehicleOption', 'vehicles_id');
    }

}
