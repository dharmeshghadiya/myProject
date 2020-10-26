<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class ReportModel extends Model
{

    protected $guarded = [];

    public function brand()
    {
        return $this->belongsTo('App\Brand', 'brand_id');
    }

    public function modelYear()
    {
        return $this->belongsTo('App\ModelYear', 'model_year_id');
    }
    public function toYear()
    {
        return $this->belongsTo('App\ModelYear', 'to_year_id');
    }

    public function color()
    {
        return $this->belongsTo('App\Color', 'color_id');
    }
    public function companies()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }

}
