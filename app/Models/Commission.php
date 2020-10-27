<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Commission extends Model
{
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function companyAddress()
    {
        return $this->belongsTo('App\Models\CompanyAddress', 'company_address_id');
    }
}
