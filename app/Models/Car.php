<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $guarded = [];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function brigades()
    {
        return $this->hasMany(Brigade::class);
    }
}
