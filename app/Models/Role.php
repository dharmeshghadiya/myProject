<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    public function roleAbility()
    {
        return $this->belongsTo('App\Models\RoleAbility', 'role_id');
    }
}
