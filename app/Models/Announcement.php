<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use Translatable;

    public $translatedAttributes = ['name'];

    protected $guarded = [];

    public function countries()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }
}
