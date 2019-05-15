<?php

namespace App\Repositories;


use App\Models\BrigadeCall;
use App\Models\Call;
use App\Patient;
use Bosnadev\Repositories\Eloquent\Repository;

class CallRepository extends Repository
{
    public function model()
    {
        return Call::class;
    }

    public function updateOrCreatePatient(array $data = [], $patient_id = null)
    {
        if ($patient_id) {
            $patient = Patient::find($patient_id);
            $patient->update($data);
        } else {
            $patient = Patient::create($data);
        }

        return $patient;
    }

    public function updateOrCreateCall($data, Patient $patient)
    {
        Call::updateOrCreate(['patient_id' => $patient->id], $data);

        return $patient->calls->first();
    }

    public function updateOrCreateBrigadeCall($brigade_id, Call $call, $data = [])
    {
        return BrigadeCall::updateOrCreate([
            'brigade_id' => $brigade_id,
            'call_id' => $call->id], $data);
    }

}
