<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallSetting extends Model
{
    const DIAGNOSES_SLUG = 'diagnoses';
    const OBJECTIVE_DATA = 'objective_data';
    const MEDICAID = 'medicaid';
    const STATE_AFTER_RELIEF = 'state_after_relief';
    protected $guarded = [];
}
