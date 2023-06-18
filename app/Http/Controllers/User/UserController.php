<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
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
        $id = Auth::user()->id;
        $orders = Order::where('user_id', $id)->orderBy('id', 'DESC')->get();
        return view('frontend.user.user_order_page',compact('orders'));
    }

    public function UserOrderDetails($order_id)
    {
        $order = Order::with('state','area','user')->where('id',$order_id)->where('user_id', Auth::id())->first();
        $orderItem = OrderItem::with('product')->where('order_id', $order_id)->orderBy('id','DESC')->get();

        return view('frontend.order.order_details',compact('order','orderItem'));
    }

}
