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

    public function AdminConfirmedOrder()
    {
        $orders = Order::where('status','confirmed')->orderBy('id','DESC')->get();
        return view('admin.orders.confirmed_orders', compact('orders'));
    }

    public function AdminProcessingOrder()
    {
        $orders = Order::where('status','processing')->orderBy('id','DESC')->get();
        return view('admin.orders.processing_orders',compact('orders'));
    }

    public function AdminDeliveredOrder()
    {
        $orders = Order::where('status','delivered')->orderBy('id','DESC')->get();
        return view('admin.orders.delivered_orders',compact('orders'));
    }

    public function updatePendingToConfirm($order_id)
    {
        Order::findOrFail($order_id)->update(['status' => 'confirm']);
        $notification = array('message' => 'Order Confirmed!','alert-type' => 'success');
    }

    public function updateConfirmToProcessing($order_id)
    {
        Order::findOrFail($order_id)->update(['status' => 'processing']);
        $notification = array(
            'message' => 'Order Processing!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.processing.order')->with($notification);
    }

    public function updateProcessingToDelivered($order_id)
    {
        Order::findOrFail($order_id)->update(['status' => 'delivered']);
        $notification = array(
            'message' => 'Order Delivered!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.delivered.order')->with($notification);
    }
}
