<?php

namespace App\Models;

use App\Patient;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function urgency_off_call()
    {
        return $this->belongsTo(UrgencyOffCall::class);
    }
}
