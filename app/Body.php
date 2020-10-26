<?php

namespace App;


use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Body extends Model
{
    use Translatable,SoftDeletes;


    public $translatedAttributes = ['name'];

    protected $guarded = [];
}
