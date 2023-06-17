<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Vendor\ProductController as VendorProductController;



use App\Http\Controllers\Front\IndexController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\User\CompareController;
use App\Http\Controllers\User\StripeController;

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

Route::get('/', [IndexController::class, 'index']);

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
        ROute::get('/subcategory/ajax/{category_id}','getSubCategory');
    });

    Route::controller(AdminController::class)->group(function(){
        Route::get('/inactive/vendor','inactiveVendor')->name('inactive.vendor');
        Route::get('/active/vendor','activeVendor')->name('active.vendor');
        Route::get('/inactive/vendor/details/{id}','inactiveVendorDetails')->name('inactive.vendor.details');
        Route::post('/active/vendor/approve','activeVendorApprove')->name('active.vendor.approve');
        Route::get('/active/vendor/details/{id}','activeVendorDetails')->name('active.vendor.details');
        Route::post('/inactive/vendor/approve','inactiveVendorApprove')->name('inactive.vendor.approve');
    });

    Route::controller(ProductController::class)->group(function(){
        Route::get('/product/index', 'index')->name('product.index');
        Route::get('/product/create','create')->name('product.create');
        Route::post('/product/store','store')->name('product.store');
        Route::get('/product/edit/{id}','edit')->name('product.edit');
        Route::post('/product/update','update')->name('product.update');
        Route::post('/product/update/thumbnail','updateProductThumbnail')->name('product.update.thumbnail');
        Route::post('/product/update/multiimage','updateProductMultipleImage')->name('product.update.multiimage');
        Route::get('/product/multiimg/delete/{id}','multiImageDelete')->name('product.multiimg.delete');

        Route::get('/product/inactive/{id}' , 'ProductInactive')->name('product.inactive');
        Route::get('/product/active/{id}' , 'ProductActive')->name('product.active');
        Route::get('/product/delete/{id}' , 'ProductDelete')->name('product.delete');
    });

    Route::controller(SliderController::class)->group(function(){
        Route::get('/slider/index','index')->name('slider.index');
        Route::get('/slider/create','create')->name('slider.create');
        Route::post('/slider/store','store')->name('slider.store');
        Route::get('/slider/edit/{id}','edit')->name('slider.edit');
        Route::post('/slider/update','update')->name('slider.update');
        Route::get('/slider/delete/{id}','delete')->name('slider.delete');
    });

    Route::controller(BannerController::class)->group(function(){
        Route::get('/banner/index','index')->name('banner.index');
        Route::get('/banner/create','create')->name('banner.create');
        Route::post('/banner/store','store')->name('banner.store');
        Route::get('/banner/edit/{id}','edit')->name('banner.edit');
        Route::post('/banner/update','update')->name('banner.update');
        Route::get('/banner/delete/{id}','delete')->name('banner.delete');
    });

    Route::controller(CouponController::class)->group(function(){
        Route::get('/coupon/index','index')->name('coupon.index');
        Route::get('/coupon/create','create')->name('coupon.create');
        Route::post('/coupon/store','store')->name('coupon.store');
        Route::get('/coupon/edit/{id}','edit')->name('coupon.edit');
        Route::post('/coupon/update','update')->name('coupon.update');
        Route::get('/coupon/delete/{id}','delete')->name('coupon.delete');
    });

    Route::controller(LocationController::class)->group(function(){
        Route::get('/state/index','stateIndex')->name('state.index');
        Route::get('/state/create','createState')->name('state.create');
        Route::post('/state/store','storeState')->name('state.store');
        Route::get('/state/edit/{id}','editState')->name('state.edit');
        Route::post('/state/update','updateState')->name('state.update');
        Route::get('/state/delete/{id}','deleteState')->name('state.delete');

        Route::get('/area/index','areaIndex')->name('area.index');
        Route::get('/area/create','createArea')->name('area.create');
        Route::post('/area/store','storeArea')->name('area.store');
        Route::get('/area/edit/{id}','editArea')->name('area.edit');
        Route::post('/area/update','updateArea')->name('area.update');
        Route::get('/area/delete/{id}','deleteArea')->name('area.delete');
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


    Route::controller(VendorProductController::class)->group(function(){
        Route::get('/vendor/product/all', 'index')->name('vendor.product.index');
        Route::get('/vendor/product/create','create')->name('vendor.product.create');

        Route::post('/vendor/product/store', 'store')->name('vendor.product.store');
        Route::get('/vendor/product/edit/{id}','edit')->name('vendor.product.edit');
        Route::post('/vendor/product/update','update')->name('vendor.product.update');
        Route::post('/vendor/product/update/thumbnail','updateProductThumbnail')->name('vendor.product.update.thumbnail');

        Route::post('/vendor/product/update/multiimage' , 'updateProductMultipleImage')->name('vendor.product.update.multiimage');

        Route::get('/vendor/product/multiimg/delete/{id}','multiImageDelete')->name('vendor.product.multiimg.delete');
        Route::get('/vendor/product/inactive/{id}' , 'VendorProductInactive')->name('vendor.product.inactive');
        Route::get('/vendor/product/active/{id}' , 'VendorProductActive')->name('vendor.product.active');

        Route::get('/vendor/delete/product/{id}' , 'VendorProductDelete')->name('vendor.product.delete');

        Route::get('/vendor/subcategory/ajax/{category_id}' , 'vendorGetSubCategory');

    });
});

Route::get('/admin/login',[AdminController::class,'login'])->middleware(RedirectIfAuthenticated::class);
Route::get('/vendor/login',[VendorController::class,'login'])->name('vendor.login')->middleware(RedirectIfAuthenticated::class);


Route::get('/vendor/signup',[VendorController::class,'vendorSignup'])->name('vendor.signup');
Route::post('/vendor/register',[VendorController::class,'register'])->name('vendor.register');


Route::get('/product/details/{id}/{slug}', [IndexController::class,'details']);
Route::get('/vendor/all', [IndexController::class, 'VendorAll'])->name('vendor.all');
Route::get('/vendor/details/{id}',[IndexController::class,'vendorDetails'])->name('vendor.details');
Route::get('/product/category/{id}/{slug}', [IndexController::class, 'CatWiseProduct']);
Route::get('/product/subcategory/{id}/{slug}', [IndexController::class, 'SubCatWiseProduct']);
Route::get('/product/view/modal/{id}', [IndexController::class, 'ProductViewAjax']);

Route::post('/cart/data/store/{id}', [CartController::class,'AddToCart']);
Route::get('/product/mini/cart', [CartController::class, 'AddMiniCart']);
Route::post('/dcart/data/store/{id}', [CartController::class, 'AddToCartDetails']);
Route::post('/add-to-wishlist/{product_id}',[WishlistController::class, 'AddToWishList']);

Route::post('/coupon-apply', [CartController::class,'applyCoupon']);
Route::get('/coupon-calculation',[CartController::class,'calculateCoupon']);
Route::get('/coupon-remove',[CartController::class,'removeCoupon']);


Route::get('/checkout', [CartController::class,'CheckoutCreate'])->name('checkout');

Route::controller(CartController::class)->group(function(){
        Route::get('/mycart', 'MyCart')->name('mycart');
        Route::get('/get-cart-product' , 'GetCartProduct');
        Route::get('/cart-remove/{rowId}' , 'CartRemove');
        Route::get('/cart-increment/{rowId}' , 'CartIncrement');
        Route::get('/cart-decrement/{rowId}' , 'CartDecrement');
    });

Route::middleware(['auth','role:user'])->group(function(){
    Route::controller(WishlistController::class)->group(function(){
        Route::get('/wishlist','AllWishlist')->name('wishlist');
        Route::get('/get-wishlist-product','GetWishlistProduct');
        Route::get('/wishlist-remove/{id}' , 'WishlistRemove');
    });

    Route::controller(CompareController::class)->group(function(){
        Route::get('/compare' , 'AllCompare')->name('compare');
        Route::get('/get-compare-product' , 'GetCompareProduct');
       Route::get('/compare-remove/{id}' , 'CompareRemove');

    });

    Route::controller(CheckoutController::class)->group(function(){
        Route::get('/areas/ajax/{state_id}', 'GetAreaForStateAjax');
        Route::post('/checkout/store','CheckoutStore')->name('checkout.store');
    });

    Route::controller(StripeController::class)->group(function(){
        Route::post('/stripe/order', 'StripeOrder')->name('stripe.order');
        Route::post('/cash/order', 'CashOrder')->name('cash.order');
    });
});
