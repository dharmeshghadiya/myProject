<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    public function roleAbility()
    {
        return $this->belongsTo('App\RoleAbility', 'role_id');
    }
}
