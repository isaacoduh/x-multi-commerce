<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function Dashboard(){
        return view('admin.index');
    }

    public function login()
    {
        return view('admin.login');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    public function profile()
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('admin.profile',compact('data'));
    }

    public function saveProfile(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if($request->file('photo')){
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_profiles/'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_profiles'),$filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array('message' => 'PRofile Updated Successfully','alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function changepassword()
    {
        return view('admin.change_password');
    }

    public function updatepassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new+_password' => 'required|confirmed'
        ]);

        if(!Hash::check($request->old_password, auth::user()->password)){
            return back()->with('error','Old password does not match');
        }

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('status','Password changed!');
    }


    public function inactiveVendor()
    {
        $inactiveVendor = User::where('status','inactive')->where('role','vendor')->latest()->get();
        return view('admin.vendor.inactive_vendor',compact('inactiveVendor'));
    }

    public function activeVendor()
    {
        $activeVendor = User::where('status','active')->where('role','vendor')->latest()->get();
        return view('admin.vendor.active_vendor',compact('activeVendor'));
    }

    public function inactiveVendorDetails($id)
    {
        $inactiveVendorDetails = User::findOrFail($id);
        return view('admin.vendor.inactive_vendor_details',compact('inactiveVendorDetails'));
    }

    public function activeVendorApprove(Request $request)
    {
        $vendor_id = $request->id;
        $user = User::findOrFail($vendor_id)->update([
            'status' => 'active'
        ]);
        $notification = array(
            'message' => 'Vendor activated!',
            'alert-type' => 'success'
        );

        return redirect()->route('active.vendor')->with($notification);
    }

    public function activeVendorDetails($id)
    {
        $activeVendorDetails = User::findOrFail($id);
        return view('admin.vendor.active_vendor_details',compact('activeVendorDetails'));
    }

    public function inactiveVendorApprove(Request $request)
    {
        $vendor_id = $request->id;
        $user = User::findOrFail($vendor_id)->update(['status' => 'inactive']);

        $notification = array(
            'message' => 'Vendor deactivated!',
            'alert-type' => 'success'
        );
        return redirect()->route('inactive.vendor')->with($notification);
    }
}
