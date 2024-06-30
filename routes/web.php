<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;

// Route::get('/', function () {
//     return view('user');
// });
Route::get('/take-snapshot', function () {
    return view('snapshot');
});

Route::get('/', [AgentController::class, 'create'])->name('agents.create');
Route::post('/agents/store', [AgentController::class, 'store'])->name('agents.store');
Route::post('/agents/get-branches', [AgentController::class, 'getBranches'])->name('agents.getBranches');





Route::post('/get-branches', [UserController::class, 'getBranches']);
Route::post('/get-employee', [UserController::class, 'getEmployee']);
Route::post('/submit-attendance', [UserController::class, 'submitAttendance']);

Route::get('/admin', function () {
    return view('admin');
});
Route::post('/admin/search', [AdminController::class, 'search']);

