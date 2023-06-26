<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function VendorOrder()
    {
        $id = Auth::user()->id;
        $orderitem = OrderItem::with('order')->where('vendor_id', $id)->orderBy('id','DESC')->get();
        
        return view('vendor.orders.pending_orders', compact('orderitem'));
    }

    public function VendorReturnOrder()
    {
        $id = Auth::user()->id;
        $orderitem = OrderItem::with('order')->where('vendor_id',$id)->orderBy('id','DESC')->get();
        return view('vendor.orders.return_orders', compact('orderitem'));
    }

    public function VendorCompleteReturnOrder()
    {
        $id = Auth::user()->id;
        $orderitem = OrderItem::with('order')->where('vendor_id',$id)->orderBy('id','DESC')->get();
        return view('vendor.orders.complete_return_orders',compact('orderitem'));
    }

    public function VendorOrderDetails($order_id)
    {
        $order = Order::with('state','area','user')->where('id',$order_id)->first();
        $orderItem = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
        return view('vendor.orders.vendor_order_details', compact('order','orderItem'));
    }
}
