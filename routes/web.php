<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Middleware\RedirectIfAuthenticated;
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
        Route::get('/brands/index', 'index')->name('brand.index');
        Route::get('/brand/create','create')->name('brand.create');
        Route::post('/brand/store','store')->name('brand.store');
        Route::get('/brand/edit/{id}','edit')->name('brand.edit');
        Route::post('/brand/update','update')->name('brand.update');
        Route::get('/brand/delete/{id}','delete')->name('brand.delete');
    });

    Route::controller(CategoryController::class)->group(function(){
        Route::get('/category/index','index')->name('category.index');
        Route::get('/category/create','create')->name('category.create');
        Route::post('/category/store','store')->name('category.store');
        Route::get('/category/edit/{id}','edit')->name('category.edit');
        Route::post('/category/update','update')->name('category.update');
        Route::get('/category/delete/{id}','delete')->name('category.delete');
    });


    Route::controller(SubCategoryController::class)->group(function(){
        Route::get('/subcategory/index','index')->name('subcategory.index');
        Route::get('/subcategory/create','create')->name('subcategory.create');
        Route::post('/subcategory/store','store')->name('subcategory.store');
        Route::get('/subcategory/edit/{id}','edit')->name('subcategory.edit');
        Route::post('/subcategory/update','update')->name('subcategory.update');
        Route::get('/subcategory/delete/{id}','delete')->name('subcategory.delete');
    });

    Route::controller(AdminController::class)->group(function(){
        Route::get('/inactive/vendor','inactiveVendor')->name('inactive.vendor');
        Route::get('/active/vendor','activeVendor')->name('active.vendor');
        Route::get('/inactive/vendor/details/{id}','inactiveVendorDetails')->name('inactive.vendor.details');
        Route::post('/active/vendor/approve','activeVendorApprove')->name('active.vendor.approve');
        Route::get('/active/vendor/details/{id}','activeVendorDetails')->name('active.vendor.details');
        Route::post('/inactive/vendor/approve','inactiveVendorApprove')->name('inactive.vendor.approve');
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

Route::get('/admin/login',[AdminController::class,'login'])->middleware(RedirectIfAuthenticated::class);
Route::get('/vendor/login',[VendorController::class,'login'])->name('vendor.login')->middleware(RedirectIfAuthenticated::class);


Route::get('/vendor/signup',[VendorController::class,'vendorSignup'])->name('vendor.signup');
Route::post('/vendor/register',[VendorController::class,'register'])->name('vendor.register');


