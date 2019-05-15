<?php

namespace App;

use App\Models\Call;
use App\Models\CallSetting;
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

    public function calls()
    {
        return $this->hasMany(Call::class);
    }

    public function getObjectiveDataAttribute($value)
    {
        $objective_data = json_decode($value, true);
        $default_objective_data = json_decode(CallSetting::where('slug', CallSetting::OBJECTIVE_DATA)->first()->value, true);

        return array_merge($default_objective_data, $objective_data ?? []);
    }

    public function getMedicaidAttribute($value)
    {
        $medicaid = json_decode($value, true);
        $default_medicaid = json_decode(CallSetting::where('slug', CallSetting::MEDICAID)->first()->value, true);

        return array_merge($default_medicaid, $medicaid ?? []);
    }

    public function getStateAfterReliefAttribute($value)
    {
        $state_after_relief = json_decode($value, true);
        $default_state_after_relief = json_decode(CallSetting::where('slug', CallSetting::STATE_AFTER_RELIEF)->first()->value, true);

        return array_merge($default_state_after_relief, $state_after_relief ?? []);
    }

    public function setObjectiveDataAttribute($value)
    {
        $this->attributes['objective_data'] = json_encode($value, true);
    }

    public function setMedicaidAttribute($value)
    {
        $this->attributes['medicaid'] = json_encode($value, true);
    }

    public function setStateAfterReliefAttribute($value)
    {
        $this->attributes['state_after_relief'] = json_encode($value, true);
    }
}
