<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryVehicle extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function companies()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function vehicle()
    {
        return $this->belongsTo('App\Models\Vehicle', 'vehicle_id');
    }

    public function companyAddress()
    {
        return $this->belongsTo('App\Models\CompanyAddress', 'company_address_id');
    }
}
