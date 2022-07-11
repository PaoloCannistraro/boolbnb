<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $guarded = [];
    public function houses()
    {
        return $this->belongsTo('App\House');
    }
}