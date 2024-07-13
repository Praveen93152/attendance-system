<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

////////////////////login and logout routes/////////////////////////////
Route::get('/', [AuthController::class, 'login'])->name('login');
// Route::post('/', [AgentController::class, 'create'])->name('login.post');
// Route::logout('/logout', [AgentController::class, 'create'])->name('logout');




//////////////////////user routes/////////////////////////////

Route::get('/take-snapshot', function () {
    return view('snapshot');
});





////////////////////admin routes////////////////////////////////

Route::post('/get-branches', [UserController::class, 'getBranches']);
Route::post('/get-employee', [UserController::class, 'getEmployee']);
Route::post('/submit-attendance', [UserController::class, 'submitAttendance']);

Route::get('/admin', function () {
    return view('admin');
});
Route::post('/admin/search', [AdminController::class, 'search']);

