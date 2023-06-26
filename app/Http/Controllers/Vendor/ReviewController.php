<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function VendorAllReview()
    {
        $id = Auth::user()->id;
        $review = Review::where('vendor_id',$id)->where('status',1)->orderBy('id','DESC');
        return view('vendor.review.approve_review',compact('review'));
    }


}
