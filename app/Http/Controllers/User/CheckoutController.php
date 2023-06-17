<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ShipmentArea;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function GetAreaForStateAjax($shipment_state_id)
    {
        $areas = ShipmentArea::where('state_id', $shipment_state_id)->orderBy('area_name', 'ASC')->get();
        return json_encode($areas);
    }

    public function CheckoutStore(Request $request)
    {
        $data = array();
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_email'] = $request->shipping_email;
        $data['shipping_phone'] = $request->shipping_phone;
        $data['post_code'] = $request->post_code;

        $data['area_id'] = $request->area_id;
        $data['state_id'] = $request->state_id;
        $data['shipping_address'] = $request->shipping_address;
        $data['notes'] = $request->notes;
        $cartTotal = Cart::total();

        if($request->payment_option == 'stripe'){
            return view('frontend.payment.stripe', compact('data', 'cartTotal'));
        } elseif($request->payment_option == 'card') {
            return 'card option';
        } else {
            return view('frontend.payment.cash', compact('data', 'cartTotal'));
        }
    }
}
