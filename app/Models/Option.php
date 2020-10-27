<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model
{
    use Translatable,SoftDeletes;

    public $translatedAttributes = ['name'];

    protected $guarded = [];
}
