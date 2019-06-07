<?php

namespace App\Http\Controllers;

use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $dataType = Voyager::model('DataType')->where('slug', '=', 'calls')->first();
        $brigade_call = Auth::user()->brigade->brigade_calls->first();
        $patient = $brigade_call->call->patient ?? new Patient();
        $call = $brigade_call->call;
        $dataTypeContent = $call;
        $brigade = Auth::user()->brigade;
        return view('home', compact('call', 'dataType', 'brigade_call', 'patient', 'dataTypeContent', 'brigade'));
    }
}
