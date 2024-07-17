<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use App\Models\DailyUploadImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function Pest\Laravel\json;

class AdminController extends Controller
{
    public function index()
    {
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
        return view('admin', ['states' => $states, 'branches' => $branches, 'results' => collect()]);
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

        // Ensure at least one field is filled
        $atLeastOne = $request->only(['admin_Client', 'admin_state', 'admin_branch', 'emp_code', 'emp_name', 'emp_mobile', 'from_date', 'to_date']);
        if (count(array_filter($atLeastOne)) === 0) {
            return response()->json(['error' => 'At least one search field is required.'], 400);
        }

        $query = DailyUploadImage::query();

        if ($request->filled('admin_Client')) {
            $query->whereIn('client', $request->admin_Client);
        }

        if ($request->filled('admin_state')) {
            // p("hi");
            $query->whereIn('state', $request->admin_state);
        }

        if ($request->filled('admin_branch')) {
            $query->whereIn('branch', $request->admin_branch);
        }

        if ($request->filled('emp_code')) {
            // p("hi");
            $query->where('employee_id', $request->emp_code);

            // $query->whereHas('user', function ($query) use ($request) {
            //     $query->where('employee_code', $request->emp_code);
            // });
        }

        if ($request->filled('emp_name')) {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('employee_name', 'like', '%' . $request->emp_name . '%');
            });
        }

        if ($request->filled('emp_mobile')) {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('mobile_no', $request->emp_mobile);
            });
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        $results = $query->get();

        return response()->json(['results' => $results]);
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
            'clients.*' => 'string',
            'state' => 'required|string',
            'branch' => 'required|array',
            'password' => 'required|string|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // dd($request->branch);

        // p($request->branch);
        $branchIds = array_map('intval', $request->branch);//convert to intiger

        $user = User::create([
            'employee_code' => $request->employee_code,
            'employee_name' => $request->employee_name,
            'mobile_no' => $request->mobile_no,
            'branch_ids' => json_encode($branchIds),
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
            ->pluck('branch', 'id');

        return response()->json($branches);
    }

    ///////////////////////////add branch/////////////////////////////////////////////////////////////////////////////


    public function addbranch()
    {
        p('addbranch');
    }
}

