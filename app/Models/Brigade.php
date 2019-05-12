<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Brigade extends Model
{
    protected $guarded = [];

    public function type()
    {
        return $this->belongsTo(BrigadeType::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function brigade_calls()
    {
        return $this->hasMany(BrigadeCall::class);
    }
}
