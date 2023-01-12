<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
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
    Route::get('/admin/logout', [AdminController::class,'logout'])->name('admin.logout');
});

Route::middleware(['auth','role:vendor'])->group(function(){
    Route::get('/vendor/dashboard', [VendorController::class,'Dashboard'])->name('vendor.dashboard');
});

Route::get('/admin/login',[AdminController::class,'login']);


