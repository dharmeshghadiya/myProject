<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelYear extends Model
{
    use SoftDeletes,SoftDeletes;

    protected $guarded = [];
}
