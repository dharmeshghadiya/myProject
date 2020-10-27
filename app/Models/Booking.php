<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    public function vehicles()
    {
        return $this->belongsTo('App\Models\Vehicle', 'vehicle_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function companyAddress()
    {
        return $this->belongsTo('App\Models\CompanyAddress', 'company_address_id');
    }
}
