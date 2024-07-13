<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(){
        // dd('hi');
        return view('login');
    }

    public function login_post(Request $request){

    }




}
