<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\ValidRc;
use App\Http\Middleware\ValidAdmin;

////////////////////login and logout routes/////////////////////////////
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/loginpost', [AuthController::class, 'login_post'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware(["auth"]);




//////////////////////user routes/////////////////////////////

Route::middleware(['auth', ValidRc::class])->group(function () {

    Route::get('/snapshot', [UserController::class, 'index'])->name('snap');
    Route::post('/submitphoto', [UserController::class, 'submitphoto'])->name('storephoto');

});



////////////////////admin routes////////////////////////////////

Route::middleware(['auth', ValidAdmin::class])->group(function () {

    ///////////////////////////////////////dashboard routes//////////////////////////////////////////////

    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/search', [AdminController::class, 'search'])->name('admin.search');
    Route::get('/download-image', [AdminController::class, 'downloadImage'])->name('download.image');
    Route::get('/download-all-images', [AdminController::class, 'downloadAllImages'])->name('download.allImages');

    ////////////////////////////////////add employee/////////////////////////////////////////////////////

    Route::get('/addemployee', [AdminController::class, 'addemployee'])->name('admin.addemployee');
    Route::post('/addemployee', [AdminController::class, 'store'])->name('admin.addemployee_post');
    Route::post('/getstates', [AdminController::class, 'getStates'])->name('admin.getstates');
    Route::post('/getbranch', [AdminController::class, 'getBranches'])->name('admin.getbranchs');

    Route::get('/download-branch-codes', [AdminController::class, 'downloadBranchCodes'])->name('download.branch.codes');
    Route::get('/download-employee-sample-data', [AdminController::class, 'downloadEmployeeSampleData'])->name('download.employee.sample.data');
    Route::post('/uploademployeedata', [AdminController::class, 'uploademployeedata'])->name('upload.employee.data');

    /////////////////////////////////////add branch///////////////////////////////////////////////////////

    Route::get('/addbranch', [AdminController::class, 'addbranch'])->name('admin.addbranch');
    Route::post('/addbranch', [AdminController::class, 'addBranchPost'])->name('admin.addbranchpost');
    Route::get('/download-branches-sample-data', [AdminController::class, 'downloadBranchSampleData'])->name('download.Branch.sample.data');


    //////////////////////////////////////////add client/////////////////////////////////////////////////

    Route::get('/addclient', [AdminController::class, 'addclient'])->name('admin.addclient');
    Route::post('/addclient', [AdminController::class, 'addClientPost'])->name('admin.addclientpost');
    Route::post('/uploadbranchdata', [AdminController::class, 'uploadbranchdata'])->name('upload.branch.data');


});