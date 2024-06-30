<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function search(Request $request)
    {
        $pictures = UserLocation::where('state', $request->state)
            ->where('branch', $request->branch)
            ->whereBetween('created_at', [$request->from_date, $request->to_date])
            ->get();
    
        return view('admin_results', compact('pictures'));
    }
}
