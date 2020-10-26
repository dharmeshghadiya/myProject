<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Company extends Model
{
    protected $guarded = [];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function companyAddress()
    {
        return $this->hasMany(CompanyAddress::class, 'company_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id');
    }


}
