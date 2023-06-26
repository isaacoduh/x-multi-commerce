<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function PendingReview()
    {
        $review = Review::where('status',0)->orderBy('id','DESC')->get();
        return view('admin.review.pending_review', compact('review'));
    }

    public function ReviewApprove($id)
    {
        Review::where('id', $id)->update(['status' => 1]);
        $notification = array(
            'message' => 'Review Approved Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 
    }

    public function PublishReview()
    {
        $review = Review::where('status',1)->orderBy('id','DESC')->get();
        return view('admin.review.publish_review',compact('review'));
    }

    public function ReviewDelete($id)
    {
        Review::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Review Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
