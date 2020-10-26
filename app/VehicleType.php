<?php

namespace App;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    use Translatable;

    public $translatedAttributes = ['name'];
}
