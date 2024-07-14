<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Auth;



class UserController extends Controller
{


    public function index()
    {
        $branch_ids = User::where('id', Auth::user()->id)->pluck('branch_ids')
        ->first();
        if (!is_null($branch_ids) && is_array($branch_ids)) {
            $branches = Branch::whereIn('id', $branch_ids)->get()->toArray();
        }else{
            $branches = collect(); 
        }
        
        return view('snapshot', ['branches' => $branches]);
    }

    public function submitAttendance(Request $request)
    {
        $location = UserLocation::where('branch', $request->branch)->first();
        $distance = $this->calculateDistance($location->latitude, $location->longitude, $request->latitude, $request->longitude);

        if ($distance > 10) {
            return response('You are outside the location. Please go inside the location.', 403);
        }

        // Save the image and other details in the database.
        // ...

        return response('Successfully submitted.');
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return ($miles * 1.609344 * 1000);
    }

}
