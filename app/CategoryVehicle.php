<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryVehicle extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function companies()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }

    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle', 'vehicle_id');
    }

    public function companyAddress()
    {
        return $this->belongsTo('App\CompanyAddress', 'company_address_id');
    }
}
