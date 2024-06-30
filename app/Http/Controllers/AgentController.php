<?php

namespace App\Http\Controllers;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\UserLocation;

class AgentController extends Controller
{
    public function create()
    {
        $states = UserLocation::select('state')->distinct()->get()->pluck('state')->toArray();
        return view('user', ['states' => $states]);
    }

    // public function getStates()
    // {
    //     $states = UserLocation::select('state')->distinct()->get();
    //     return response()->json($states);
    // }

    public function getBranches(Request $request)
    {
        $state = $request->state;
        $branches = UserLocation::where('state', $state)->pluck('branch');
        return response()->json($branches);
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_code' => 'required|unique:agents',
            'employee_name' => 'required',
            'client' => 'required',
            'state' => 'required',
            'branch' => 'required|array',
            'mobile_no' => 'required',
            'password' => 'required|confirmed',
        ]);

        Agent::create([
            'employee_code' => $request->employee_code,
            'employee_name' => $request->employee_name,
            'client' => $request->client,
            'state' => $request->state,
            'branch' => $request->branch,
            'mobile_no' => $request->mobile_no,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('agents.create')->with('success', 'Agent created successfully');
    }
}
