<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReportModel extends Model
{

    protected $guarded = [];

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'brand_id');
    }

    public function modelYear()
    {
        return $this->belongsTo('App\Models\ModelYear', 'model_year_id');
    }
    public function toYear()
    {
        return $this->belongsTo('App\Models\ModelYear', 'to_year_id');
    }

    public function color()
    {
        return $this->belongsTo('App\Models\Color', 'color_id');
    }
    public function companies()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

}
