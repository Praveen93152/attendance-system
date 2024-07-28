<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Storage;
use App\Models\DailyUploadImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BranchesExport;
use App\Imports\EmployeeImport;
use App\Imports\BranchImport;
use App\Exports\DailyUploadImagesExport;
use ZipArchive;
use Exception;

use function Pest\Laravel\json;

class AdminController extends Controller
{

    //////////////////////////////////////////dashboard/////////////////////////////////////////////////////
    public function index()
    {
        $clients = Client::select('clients')->get();
        ;
        $states = DB::table('branches')
            ->select('id', 'state')
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MIN(id)'))
                    ->from('branches')
                    ->groupBy('state');
            })
            ->orderBy('state', 'asc')
            ->get();
        $branches = Branch::select('id', 'branch')->distinct('branch')->orderBy('branch', 'asc')->get();
        // p($branches->toArray());
        return view('admin', ['states' => $states, 'branches' => $branches, 'clients' => $clients, 'results' => collect()]);
    }

    public function search(Request $request)
    {
        $validatedData = $request->validate([
            'admin_Client' => 'nullable|array',
            'admin_state' => 'nullable|array',
            'admin_branch' => 'nullable|array',
            'emp_code' => 'nullable|string|max:255',
            'emp_name' => 'nullable|string|max:255',
            'emp_mobile' => 'nullable|string|max:255',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $atLeastOne = $request->only(['admin_Client', 'admin_state', 'admin_branch', 'emp_code', 'emp_name', 'emp_mobile', 'from_date', 'to_date']);
        if (count(array_filter($atLeastOne)) === 0) {
            return response()->json(['error' => 'At least one search field is required.'], 400);
        }

        $query = DailyUploadImage::query()->select('daily_upload_images.*', 'users.employee_name')
            ->join('users', 'daily_upload_images.employee_id', '=', 'users.employee_code');

        if ($request->filled('admin_Client')) {
            $query->whereIn('client', $request->admin_Client);
        }

        if ($request->filled('admin_state')) {
            $query->whereIn('state', $request->admin_state);
        }

        if ($request->filled('admin_branch')) {
            $query->whereIn('branch', $request->admin_branch);
        }

        if ($request->filled('emp_code')) {
            $query->where('employee_id', $request->emp_code);
        }

        if ($request->filled('emp_name')) {
            $user = User::where('employee_name', 'like', '%' . $request->emp_name . '%')->first();
            if (!$user) {
                return response()->json(['results' => 'User not found']);
            } else {
                $emp_code = $user->employee_code;
                $query->where('employee_id', $emp_code);
            }
        }

        if ($request->filled('emp_mobile')) {
            $user = User::where('mobile_no', $request->emp_mobile)->first();
            if (!$user) {
                return response()->json(['results' => 'User not found']);
            } else {
                $emp_code = $user->employee_code;
                $query->where('employee_id', $emp_code);
            }
        }

        if ($request->filled('from_date') && empty($request->to_date)) {
            $query->whereDate('daily_upload_images.created_at', $request->from_date);
        }

        if ($request->filled('to_date') && empty($request->from_date)) {
            $query->whereDate('daily_upload_images.created_at', '<=', $request->to_date);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('daily_upload_images.created_at', [$request->from_date, $request->to_date]);
        }

        $results = $query->get();

        // p($results->toArray());

        return response()->json(['results' => $results]);
    }

    public function downloadImage(Request $request)
    {
        $path = $request->query('path');

        $fullPath = public_path($path);

        if (!file_exists($fullPath)) {
            abort(404, 'File not found.');
        } else {
            return response()->download($fullPath);
        }
    }

    public function downloadAllImages(Request $request)
    {

        $paths = json_decode($request->query('paths'), true);
        if (empty($paths)) {
            return response()->json(['error' => 'No images to download.'], 400);
        }

        $zip = new ZipArchive;
        $zipFileName = 'images.zip';
        $zipPath = storage_path('app/' . $zipFileName);


        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($paths as $path) {
                $absolutePath = public_path($path);

                if (file_exists($absolutePath)) {
                    $zip->addFile($absolutePath, basename($path));
                } else {
                    return response()->json(['error' => "File does not exist: {$path}"], 404);
                }
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'Failed to create zip file.'], 500);
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function exportbyexcel(Request $request)
    {
        $validatedData = $request->validate([
            'admin_Client' => 'nullable|array',
            'admin_state' => 'nullable|array',
            'admin_branch' => 'nullable|array',
            'emp_code' => 'nullable|string|max:255',
            'emp_name' => 'nullable|string|max:255',
            'emp_mobile' => 'nullable|string|max:255',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $atLeastOne = $request->only(['admin_Client', 'admin_state', 'admin_branch', 'emp_code', 'emp_name', 'emp_mobile', 'from_date', 'to_date']);
        if (count(array_filter($atLeastOne)) === 0) {
            return redirect()->back()->withErrors(['error' => 'At least one search field is required.']);
        }

        $query = DailyUploadImage::query()->select('daily_upload_images.*', 'users.employee_name')
            ->join('users', 'daily_upload_images.employee_id', '=', 'users.employee_code');

        // Add the same filtering logic as in the search method

        $results = $query->get(['client', 'state', 'branch', 'employee_id', 'employee_name', 'path','created_at'])
                        ->map(function($result) {
                            return [
                                'client' => $result->client,
                                'state' => $result->state,
                                'branch' => $result->branch,
                                'employee_id' => $result->employee_id,
                                'employee_name' => $result->employee_name,
                                'image_file_name' => basename($result->path),
                                'date'=> $result->created_at,
                            ];
                        });

        return Excel::download(new DailyUploadImagesExport($results), 'images.xlsx');
    }

    /////////////////////////////////////////add employee////////////////////////////////////////////////////////////

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
            'state' => 'required|string',
            'branch' => 'required|array',
            // 'password' => 'required|string|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'employee_code' => $request->employee_code,
            'employee_name' => $request->employee_name,
            'mobile_no' => $request->mobile_no,
            'branch_ids' => $request->branch,
            'role' => 'rc',
            // 'password' => Hash::make($request->password),
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
            ->pluck('branch', 'id');

        return response()->json($branches);
    }

    public function downloadBranchCodes()
    {
        return Excel::download(new BranchesExport, 'branch_codes.xlsx');
    }

    public function downloadEmployeeSampleData()
    {
        $filePath = public_path('sample files/sample data for employee data upload.xlsx');

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        return response()->download($filePath);
    }

    public function uploadEmployeeData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            Excel::import(new EmployeeImport, $request->file('file'));
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            // Return with a general error message
            return back()->withErrors(['file' => $e->getMessage()]);
        }

        return back()->with('success', 'Users imported successfully.');
    }



    //////////////////////////////////////////////////////add branch////////////////////////////////////////////


    public function addbranch()
    {
        return view('addbranch');
    }

    public function addBranchPost(Request $request)
    {
        $request->validate([
            'clients' => 'required|string',
            'state' => 'required|string',
            'branch' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90|regex:/^\d+(\.\d{1,8})?$/',
            'longitude' => 'required|numeric|between:-180,180|regex:/^\d+(\.\d{1,8})?$/',
        ]);

        // Check if the branch with the same client, state, and branch already exists
        $exists = Branch::where('client_name', $request->input('clients'))
            ->where('state', $request->input('state'))
            ->where('branch', $request->input('branch'))
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['error' => 'This branch already exists.']);
        }

        // Create a new record if no duplicates are found
        Branch::create([
            'client_name' => $request->input('clients'),
            'state' => $request->input('state'),
            'branch' => $request->input('branch'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
        ]);

        return redirect()->back()->with('success', 'Branch details added successfully.');
    }

    public function downloadBranchSampleData()
    {

        $filePath = public_path('sample files/sample data for branches data upload.xlsx');

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        return response()->download($filePath);
    }

    //////////////////////////////////////////////////////add client////////////////////////////////////////////

    public function addclient()
    {
        return view('addclient');
    }

    public function addClientPost(Request $request)
    {

        $request->validate([
            'client_name' => 'required|unique:clients,clients',
        ]);

        Client::create([
            'clients' => $request->input('client_name'),
        ]);
        return redirect()->back()->with('success', 'Branch details added successfully.');
    }

    public function uploadbranchdata(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            Excel::import(new BranchImport, $request->file('file'));
        } catch (ValidationException $e) {
            // Return with all validation errors from the import
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            // Return with a general error message
            return back()->withErrors(['file' => $e->getMessage()]);
        }

        return back()->with('success', 'Branches imported successfully.');
    }
}

