<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function UserAccount()
    {
        $id = Auth::user()->id;
        $userData = User::find($id);

        view('frontend.user.account_details', compact('userData'));
    }

    public function UserChangePassword()
    {
        return view('frontend.user.user_change_password');
    }

    public function UserOrderPage()
    {
        return view('frontend.user.user_order_page');
    }
}
