<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

////////////////////login and logout routes/////////////////////////////
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/loginpost', [AuthController::class, 'login_post'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');




//////////////////////user routes/////////////////////////////

Route::get('/snapshot', [UserController::class, 'index'])->name('snap');
Route::post('/submitphoto', [UserController::class, 'submitphoto'])->name('storephoto');





////////////////////admin routes////////////////////////////////
Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
Route::post('/admin/search', [AdminController::class, 'search'])->name('admin.search');


Route::get('/addemployee', [AdminController::class, 'addemployee'])->name('admin.addemployee');
Route::post('/addemployee', [AdminController::class, 'store'])->name('admin.addemployee_post');
Route::post('/getstates', [AdminController::class, 'getStates'])->name('admin.getstates');
Route::post('/getbranch', [AdminController::class, 'getBranches'])->name('admin.getbranchs');

Route::get('/addbranch', [AdminController::class, 'addbranch'])->name('admin.addbranch');