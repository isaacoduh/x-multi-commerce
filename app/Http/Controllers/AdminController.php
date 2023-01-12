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
}
