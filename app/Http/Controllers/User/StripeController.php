<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use App\Mail\OrderMail;

class StripeController extends Controller
{
    public function StripeOrder(Request $request)
    {
        if(Session::has('coupon')){
            $total_amount = Session::get('coupon')['total_amount']; 
        } else {
            $total_amount = round(floatval(Cart::total()));
        }
        \Stripe\Stripe::setApiKey('sk_test_UjCD2kLt6KIOevQyMSHBXc2M00PnxwK3GU');
        $token = $_POST['stripeToken'];

        $charge =  \Stripe\Charge::create([
            'amount' => $total_amount * 100,
            'currency' => 'usd',
            'description' => 'X Multi Commerce',
            'source' => $token,
            'metadata' => ['order_id' => uniqid()],
        ]);

        $order_id = Order::insertGetId([
            'user_id' => Auth::id(),
            'area_id' => $request->area_id,
            'state_id' => $request->state_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'adress' => $request->address,
            'post_code' => $request->post_code,
            'notes' => $request->notes,
            'payment_type' => $charge->payment_method,
            'payment_method' => 'Stripe',
            'transaction_id' => $charge->balance_transaction,
            'currency' => $charge->currency,
            'amount' => $total_amount,
            'order_number' => $charge->metadata->order_id,
            'invoice_no' => 'EOS'.mt_rand(10000000,99999999),
            'order_date' => Carbon::now()->format('d F Y'),
            'order_month' => Carbon::now()->format('F'),
            'order_year' => Carbon::now()->format('Y'),
            'status' => 'pending',
            'created_at' => Carbon::now(),
        ]);

        $invoice = Order::findOrFail($order_id);

        $data = [

            'invoice_no' => $invoice->invoice_no,
            'amount' => $total_amount,
            'name' => $invoice->name,
            'email' => $invoice->email,

        ];

        Mail::to($request->email)->send(new OrderMail($data));

        $carts = Cart::content();

        foreach($carts as $cart) {
            OrderItem::insert([
                'order_id' => $order_id,
                'product_id' => $cart->id,
                'vendor_id' => $cart->options->vendor,
                'color' => $cart->options->color,
                'size' => $cart->options->size,
                'qty' => $cart->qty,
                'price' => $cart->price,
                'created_at' => Carbon::now()
            ]);
        }

        if(Session::has('coupon')){
            Session::forget('coupon');
        }

        Cart::destroy();

        $notification = array('message' => 'Your Order was placed Successfully', 'alert-type' => 'success');
        return redirect()->route('dashboard')->with($notification);
    }

    public function CashOrder(Request $request)
    {
        if(Session::has('coupon')){
            $total_amount = Session::get('coupon')['total_amount']; 
        } else {
            $total_amount = round(floatval(Cart::total()));
        }

        $order_id = Order::insertGetId([
            'user_id' => Auth::id(),
            'area_id' => $request->area_id,
            'state_id' => $request->state_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'adress' => $request->address,
            'post_code' => $request->post_code,
            'notes' => $request->notes,
            'payment_type' => 'Cash on Delivery',
            'payment_method' => 'Cash on Delivery',
            'currency' => 'Usd',
            'amount' => $total_amount,
            'invoice_no' => 'EOS'.mt_rand(10000000,99999999),
            'order_date' => Carbon::now()->format('d F Y'),
            'order_month' => Carbon::now()->format('F'),
            'order_year' => Carbon::now()->format('Y'), 
            'status' => 'pending',
            'created_at' => Carbon::now(), 
        ]);

        $invoice = Order::findOrFail($order_id);

        $data = [

            'invoice_no' => $invoice->invoice_no,
            'amount' => $total_amount,
            'name' => $invoice->name,
            'email' => $invoice->email,

        ];

        Mail::to($request->email)->send(new OrderMail($data));

        $carts = Cart::content();
        foreach($carts as $cart){
            
            OrderItem::insert([
                'order_id' => $order_id,
                'product_id' => $cart->id,
                'vendor_id' => $cart->options->vendor,
                'color' => $cart->options->color,
                'size' => $cart->options->size,
                'qty' => $cart->qty,
                'price' => $cart->price,
                'created_at' =>Carbon::now(),

            ]);

        }
        if (Session::has('coupon')) {
           Session::forget('coupon');
        }

        Cart::destroy();

        $notification = array('message' => 'Your Order was placed Successfully', 'alert-type' => 'success');
        return redirect()->route('dashboard')->with($notification);
    }
}
