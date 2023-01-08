<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function Dashboard()
    {
        return view('vendor.vendor_dashboard');
    }
}
