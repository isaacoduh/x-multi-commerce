<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function PendingOrder()
    {
        $orders = Order::where('status', 'pending')->orderBy('id','DESC')->get();
        return view('admin.orders.pending_orders', compact('orders'));
    }

    public function AdminOrderDetails($order_id)
    {
        $order = Order::with('state','area','user')->where('id',$order_id)->first();
        $orderItem = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
        return view('admin.orders.admin_order_details', compact('order','orderItem'));
    }
}
