<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = ['name', 'address'];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function status()
    {
        return $this->belongsTo(StationStatus::class);
    }

    public function type()
    {
        return $this->belongsTo(StationType::class);
    }
}
