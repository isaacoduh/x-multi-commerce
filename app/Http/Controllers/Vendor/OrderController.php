<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
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
}
