<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Commission extends Model
{
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }

    public function companyAddress()
    {
        return $this->belongsTo('App\CompanyAddress', 'company_address_id');
    }
}
