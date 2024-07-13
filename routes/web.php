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
Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');


Route::get('/addemployee', [AdminController::class, 'addemployee'])->name('admin.addemployee');
Route::post('/addemployee', [AdminController::class, 'store'])->name('admin.addemployee_post');
Route::post('/getstates', [AdminController::class, 'getStates'])->name('admin.getstates');
Route::post('/getbranch', [AdminController::class, 'getBranches'])->name('admin.getbranchs');

Route::get('/addbranch', [AdminController::class, 'addbranch'])->name('admin.addbranch');