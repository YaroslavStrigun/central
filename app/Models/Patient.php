<?php

namespace App;

use App\Models\Result;
use App\Models\SocialStatus;
use App\Models\Trauma;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $guarded = [];

    public function status()
    {
        return $this->belongsTo(SocialStatus::class);
    }

    public function result()
    {
        return $this->belongsTo(Result::class);
    }

    public function trauma()
    {
        return $this->belongsTo(Trauma::class);
    }
}
