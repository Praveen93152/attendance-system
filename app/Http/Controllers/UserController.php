<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\User;
use App\Models\DailyUploadImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class UserController extends Controller
{
    public function index()
    {
        $branch_ids = User::where('id', Auth::user()->id)->pluck('branch_ids')
            ->first();
        if (!is_null($branch_ids) && is_array($branch_ids)) {
            $branches = Branch::whereIn('id', $branch_ids)->get()->toArray();
        } else {
            $branches = collect();
        }

        // p($branches);
        return view('snapshot', ['branches' => $branches]);
    }

    public function submitphoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'branch' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'place' => 'required|string',
            'employee_id' => 'required|string',
            'name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Split branch into its components
        list($client_name, $state, $branch, $branch_latitude, $branch_longitude) = explode('_', $request->branch);

        // Calculate the distance
        $distance = $this->calculateDistance($branch_latitude, $branch_longitude, $request->latitude, $request->longitude);

        if ($distance > 200) {
            return response()->json([
                'message' => 'You are outside the location. Please go inside the location.',
            ], 403);
        }

        // Save the photo
        $photoPath = saveEmployeeImage($request->photo, $request->employee_id, $request->name);

        // Create the user record
        $user = DailyUploadImage::create([
            'employee_id' => $request->employee_id,
            'client' => $client_name,
            'state' => $state,
            'branch' => $branch,
            'path' => $photoPath,
        ]);

        return response()->json([
            'message' => 'Successfully submitted.',
        ], 200);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return ($miles * 1.609344 * 1000); // convert miles to meters
    }

}
