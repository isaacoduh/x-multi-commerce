<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function AllUser()
    {
        $users = User::where('role','user')->latest()->get();
        return view('admin.user.user_all_data',compact('users'));
    }

    public function AllVendor()
    {
        $vendors = User::where('role','vendor')->latest()->get();
        return view('admin.user.vendor_all_data',compact('vendors'));
    }
}
