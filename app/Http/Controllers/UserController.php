<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\User;
use App\Models\DailyUploadImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;



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
       
        return view('snapshot', ['branches' => $branches]);
    }

    public function submitphoto(Request $request)
    {

        // $validator = Validator::make($request->all(), [
        //     'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        //     'branch' => 'required|string',
        //     'latitude' => 'required|numeric',
        //     'longitude' => 'required|numeric',
        //     'place' => 'required|string',
        //     'employee_id' => 'required|string',
        //     'name' => 'required|string'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'message' => 'Validation errors',
        //         'errors' => $validator->errors()
        //     ]);
        // }

        if (Auth::user()->max_pics == 0) {
            return response()->json([
                'message' => 'You already submitted, try again tommorrow',
            ]);
        }


        $parts = explode('_', $request->branch);

        if (count($parts) == 3) {
            list($client_name, $state, $branch) = $parts;
            $branch_latitude = null;
            $branch_longitude = null;
        } else {
            list($client_name, $state, $branch, $branch_latitude, $branch_longitude) = $parts;
        }


        if ($branch != "Field") {
            // Calculate the distance
            $distance = $this->calculateDistance($branch_latitude, $branch_longitude, $request->latitude, $request->longitude);
            if ($distance > 500) {
                return response()->json([
                    'message' => 'You are outside the location. Please go inside the location.',
                ]);
            }
        }

        // Save the photo
        $photoPath = $this->saveEmployeeImage($request->photo, $request->employee_id, $request->name);

        
        // Create the user record
        $user = DailyUploadImage::create([
            'employee_id' => $request->employee_id,
            'client' => $client_name,
            'state' => $state,
            'branch' => $branch,
            'path' => $photoPath,
            'address'=>$request->place
        ]);

        Auth::user()->decrement('max_pics');

        

        return response()->json([
            'message' => 'Successfully submitted.',
        ]);
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

    private function saveEmployeeImage($image, $emp_id, $emp_name)
    {
        $baseDir = 'public/employee_images';

        // Get the current date components
        $year = date('Y');
        $month = Carbon::now()->format('F');
        $day = date('d');

        // Create the directory path
        $directory = "$baseDir/$year/$month/$day";

        // Ensure the directory exists
        if (!Storage::exists($directory)) {
            $created = Storage::makeDirectory($directory, 0755, true);

        }

        // Generate the filename
        $timestamp = Carbon::now()->format('Ymd_His');

        $fileExtension = $image->getClientOriginalExtension();
        $sanitizedEmpName = Str::slug($emp_name); // Sanitize the emp_name to avoid invalid characters
        $filename = "{$timestamp}_{$emp_id}_{$sanitizedEmpName}." . $fileExtension;

        $path = $image->storeAs($directory, $filename);



        if (!$path) {
            Log::error("Failed to store image for employee_id: $emp_id, employee_name: $emp_name");
            return null;
        }

        return Storage::url($path);
    }

}
