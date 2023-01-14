<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('frontend.index');
});

ROute::middleware(['auth'])->group(function(){
    Route::get('/dashboard',[UserController::class,'dashboard'])->name('dashboard');
    Route::post('/user/profile/store', [UserController::class, 'profilestore'])->name('user.profile.store');
    Route::post('/user/update/password', [UserController::class,'updatepassword'])->name('user.update.password');
    Route::get('/user/logout',[UserController::class,'logout'])->name('user.logout');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');
require __DIR__.'/auth.php';

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


Route::middleware(['auth','role:admin'])->group(function(){
    Route::get('/admin/dashboard',[AdminController::class,'Dashboard'])->name('admin.dashboard');

    Route::get('/admin/profile',[AdminController::class,'profile'])->name('admin.profile');
    Route::post('/admin/profile/store',[AdminController::class,'saveProfile'])->name('admin.profile.store');
    ROute::get('/admin/change/password',[AdminController::class,'changepassword'])->name('admin.change.password');
    Route::post('/admin/update/password',[AdminController::class,'updatepassword'])->name('update.password');


    Route::controller(BrandController::class)->group(function(){
        Route::get('/brands/index', 'index')->name('brand.all');
        Route::post('/brand/create','create')->name('brand.create');
    });
    Route::get('/admin/logout', [AdminController::class,'logout'])->name('admin.logout');
});

Route::middleware(['auth','role:vendor'])->group(function(){
    Route::get('/vendor/dashboard', [VendorController::class,'Dashboard'])->name('vendor.dashboard');
    Route::get('/vendor/profile', [VendorController::class,'profile'])->name('vendor.profile');

    Route::get('/vendor/profile/store', [VendorController::class,'profilestore'])->name('vendor.profile.store');

    Route::get('/vendor/change/password',[VendorController::class,'changepassword'])->name('vendor.change.password');
    Route::post('/vendor/change/password',[VendorController::class,'changepassword'])->name('vendor.update.password');

    Route::get('/vendor/logout', [VendorController::class,'logout'])->name('vendor.logout');
});

Route::get('/admin/login',[AdminController::class,'login']);
Route::get('/vendor/login',[VendorController::class,'login']);


