<?php

namespace App;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GlobalExtra extends Model
{
    use Translatable,SoftDeletes;

    public $translatedAttributes = ['name','description'];

    protected $guarded = [];
}
