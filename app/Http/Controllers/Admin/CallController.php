<?php

namespace App\Http\Controllers\Admin;


use App\Models\Call;
use App\Repositories\CallRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class CallController extends VoyagerBaseController
{
    protected $callRepository;

    public function __construct(CallRepository $callRepository)
    {
        $this->callRepository = $callRepository;
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) {
            $call_data = [
                'user_id' => Auth::user()->id,
                'phone' => $request->input('phone', ''),
                'call_address' => $request->input('call_address', ''),
                'reason' => $request->input('reason', ''),
                'urgency_off_call_id' => $request->input('urgency_off_call_id', 1),
                'comment' => $request->input('comment', '')
            ];

            $patient_data =  $request->input('patient', []);

            if (isset($patient_data['male']) && $patient_data['male'])
                $patient_data['male'] = true;
            else
                $patient_data['male'] = false;

            if (isset($patient_data['reasonableness']) && $patient_data['reasonableness'])
                $patient_data['reasonableness'] = true;
            else
                $patient_data['reasonableness'] = false;

            if (isset($patient_data['unsuccessful_departure']) && $patient_data['unsuccessful_departure'])
                $patient_data['unsuccessful_departure'] = true;
            else
                $patient_data['unsuccessful_departure'] = false;

            $patient = $this->callRepository->updateOrCreatePatient($patient_data);

            $call = $this->callRepository->updateOrCreateCall($call_data, $patient);

            $this->callRepository->updateOrCreateBrigadeCall($request->input('brigade_id'), $call, $request->input('brigade_call', []));
        }
        return redirect()
            ->route("voyager.calls.index")
            ->with([
                'message'    => __('voyager::generic.successfully_added_new')." Виклик",
                'alert-type' => 'success',
            ]);
    }

    public function update(Request $request, $id)
    {
        if (!$request->ajax()) {
            $call = Call::findOrFail($id);

            $call_data = [
                'user_id' => Auth::user()->id,
                'phone' => $request->input('phone', ''),
                'call_address' => $request->input('call_address', ''),
                'reason' => $request->input('reason', ''),
                'urgency_off_call_id' => $request->input('urgency_off_call_id', 1),
                'comment' => $request->input('comment', '')
            ];

            $patient_data =  $request->input('patient', []);

            if (isset($patient_data['male']) && $patient_data['male'])
                $patient_data['male'] = true;
            else
                $patient_data['male'] = false;

            if (isset($patient_data['reasonableness']) && $patient_data['reasonableness'])
                $patient_data['reasonableness'] = true;
            else
                $patient_data['reasonableness'] = false;

            if (isset($patient_data['unsuccessful_departure']) && $patient_data['unsuccessful_departure'])
                $patient_data['unsuccessful_departure'] = true;
            else
                $patient_data['unsuccessful_departure'] = false;

            $patient = $this->callRepository->updateOrCreatePatient($patient_data, $call->patient->id ?? null);

            $call = $this->callRepository->updateOrCreateCall($call_data, $patient);

            $this->callRepository->updateOrCreateBrigadeCall($request->input('brigade_id'), $call, $request->input('brigade_call', []));
        }
        return redirect()
            ->route("voyager.calls.index")
            ->with([
                'message'    => __('voyager::generic.successfully_added_new')." Виклик",
                'alert-type' => 'success',
            ]);
    }
}
