<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CompanyAddress extends Model
{
    protected $guarded = [];

    public function companies()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function users()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function companyTime()
    {
        return $this->hasMany('App\Models\CompanyTime', 'company_address_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public static function haversineCompany($query, $filter_array)
    {
        $distance_select = sprintf(
            " ROUND(( %d * acos( cos( radians(%s) ) " .
            " * cos( radians( latitude ) ) " .
            " * cos( radians( longitude ) - radians(%s) ) " .
            " + sin( radians(%s) ) * sin( radians( latitude ) ) " .
            " ) " .
            "), 2 ) " .
            "AS distance",
            6371,
            $filter_array['latitude'],
            $filter_array['longitude'],
            $filter_array['latitude']
        );
        $sql = 'SELECT company_addresses.id,company_addresses.service_distance ,' . $distance_select;
        $sql .= ' FROM company_addresses LEFT JOIN companies ON company_addresses.company_id=companies.id';
        $sql .= ' WHERE companies.deleted_at IS NULL AND company_addresses.deleted_at IS NULL';
        if($filter_array['is_country'] == 1){
            $sql .= ' AND company_addresses.country_id="' . $filter_array['country_id'] . '"';
        }
        if($filter_array['is_country'] == 0){
            $sql .= ' HAVING distance<="' . $filter_array['default_distance'] . '"';
        }
        $sql .= ' ORDER BY distance ASC';

        return DB::select($sql);
        //  dd($query);
    }

}
