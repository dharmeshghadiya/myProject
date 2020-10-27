<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RydeInstance extends Model
{
    protected $guarded = [];

    public function body()
    {
        return $this->belongsTo('App\Models\Body', 'body_id');
    }
    public function door()
    {
        return $this->belongsTo('App\Models\Door', 'door_id');
    }
}
