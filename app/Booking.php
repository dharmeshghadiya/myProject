<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    public function vehicles()
    {
        return $this->belongsTo('App\Vehicle', 'vehicle_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function companyAddress()
    {
        return $this->belongsTo('App\CompanyAddress', 'company_address_id');
    }
}
