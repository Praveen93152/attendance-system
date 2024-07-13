<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function add_employee()
    {
        return view('add_employee');
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
            ->pluck('branch');

        return response()->json($branches);
    }
}

