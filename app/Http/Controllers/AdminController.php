<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;




class AdminController extends Controller
{
    public function index()
    {
        return view('admin');
    }
    public function addemployee()
    {
        return view('addemployee');
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'employee_code' => 'required|unique:users,employee_code',
            'employee_name' => 'required|string|max:255',
            'mobile_no' => 'required|unique:users,mobile_no',
            'clients' => 'required|array',
            'clients.*' => 'string',
            'state' => 'required|string',
            'branch' => 'required|array',
            'password' => 'required|string|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // dd($request->branch);

       
        $user = User::create([
            'employee_code' => $request->employee_code,
            'employee_name' => $request->employee_name,
            'mobile_no' => $request->mobile_no,
            'branch_ids' =>json_encode($request->branch),
            'password' => Hash::make($request->password),
        ]);

       
        return redirect()->route('admin.addemployee_post')->with('success', 'Employee added successfully');
    }



    public function getStates(Request $request)
    {
        $clients = $request->input('clients');
        $states = Branch::whereIn('client_name', $clients)->distinct()->pluck('state');

        return response()->json($states);
    }

    public function getBranches(Request $request)
    {
        $state = $request->state;
        $clients = $request->clients; // Get selected clients from request
        $branches = Branch::whereIn('client_name', $clients)
            ->where('state', $state)
            ->pluck('branch','id');

        return response()->json($branches);
    }


    public function addbranch()
    {
        p('addbranch');
    }
}

