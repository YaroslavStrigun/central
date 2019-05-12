<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrigadeCall extends Model
{
    protected $guarded = [];

    public function call()
    {
        return $this->belongsTo(Call::class);
    }

    public function brigade()
    {
        return $this->belongsTo(Brigade::class);
    }

    public function status()
    {
        return $this->belongsTo(BrigadeStatus::class);
    }
}
