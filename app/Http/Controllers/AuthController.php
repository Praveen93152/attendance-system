<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        $userId = Cookie::get('user_id');
        if (isset($userId)) {
            $user = User::find($userId);

            if ($user) {
                Auth::login($user);
                Session::put('user_id', $user->id);

                if ($user->role == 'admin') {
                    return redirect()->route('admin.dashboard');
                } else {
                    // p('hii1');
                    return redirect()->route('snap');
                }
            }
        }

        return view('login');
    }

    public function login_post(Request $request)
    {
        $request->validate([
            'loginBy' => 'required|in:mobile,emp_id',
            'inputMobileEmpId' => 'required',
            'password' => 'required',
        ]);

        $loginBy = $request->input('loginBy');
        $identifier = $request->input('inputMobileEmpId');
        $password = $request->input('password');

        $field = $loginBy === 'mobile' ? 'mobile_no' : 'employee_code';

        $user = User::where($field, $identifier)->first();

        if (!$user) {
            $error = $field === 'mobile_no' ? 'The provided mobile number is incorrect.' : 'The provided employee ID is incorrect.';
            return back()->withErrors(['identifier' => $error])->withInput($request->all());
        }

        if (!Auth::attempt([$field => $identifier, 'password' => $password])) {
            return back()->withErrors(['password' => 'The provided password is incorrect.'])->withInput($request->all());
        }

        // Session::flush();
        // Cookie::queue(Cookie::forget('user_id'));

        Auth::login($user);
        // Cookie::queue(Cookie::forget('user_id'));
        Session::put('user_id', $user->id);
        Cookie::queue('user_id', $user->id, 60 * 24 * 365 * 10);


        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            // p('hii');
            return redirect()->route('snap');
        }
    }

    public function logout(Request $request)
    {
        //  Session::flush();
        Auth::logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        // Cookie::queue(Cookie::forget('user_id'));
        // Session::flush();
        // $request->session()->regenerateToken();
        return view('login');
    }



}
