<?php

namespace App;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use Translatable;

    public $translatedAttributes = ['name'];

    protected $guarded = [];

    public function countries()
    {
        return $this->belongsTo('App\Country', 'country_id');
    }
}
