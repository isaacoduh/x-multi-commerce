<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function index(){
        $coupon = Coupon::latest()->get();
        return view('admin.coupon.index',compact('coupon'));
    } // End Method


    public function create(){
        return view('admin.coupon.create');
    }// End Method


public function store(Request $request){

        Coupon::insert([
            'coupon_name' => strtoupper($request->coupon_name),
            'coupon_discount' => $request->coupon_discount,
            'coupon_validity' => $request->coupon_validity,
            'created_at' => Carbon::now(),
        ]);

       $notification = array(
            'message' => 'Coupon Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('coupon.index')->with($notification);

    }// End Method


    public function edit($id){

        $coupon = Coupon::findOrFail($id);
        return view('admin.coupon.edit',compact('coupon'));

    }// End Method


    public function update(Request $request){

        $coupon_id = $request->id;

         Coupon::findOrFail($coupon_id)->update([
            'coupon_name' => strtoupper($request->coupon_name),
            'coupon_discount' => $request->coupon_discount,
            'coupon_validity' => $request->coupon_validity,
            'created_at' => Carbon::now(),
        ]);

       $notification = array(
            'message' => 'Coupon Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('coupon.index')->with($notification);


    }// End Method

     public function delete($id){

        Coupon::findOrFail($id)->delete();

         $notification = array(
            'message' => 'Coupon Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);


    }// End Method


}
