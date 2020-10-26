<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RydeInstance extends Model
{
    protected $guarded = [];

    public function body()
    {
        return $this->belongsTo('App\Body', 'body_id');
    }
    public function door()
    {
        return $this->belongsTo('App\Door', 'door_id');
    }
}
