<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::latest()->get();
        return view('admin.slider.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.slider.create');
    }

    public function store(Request $request)
    {
        $image = $request->file('slider_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(2376,807)->save('upload/slider/'.$name_gen);
        $save_url = 'upload/slider/'.$name_gen;

        Slider::insert([
            'slider_title' => $request->slider_title,
            'short_title' => $request->short_title,
            'slider_image' => $save_url,
        ]);

       $notification = array(
            'message' => 'Slider Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('slider.index')->with($notification);
    }

    public function edit($id)
    {
        $sliders = Slider::findOrFail($id);
        return view('admin.slider.edit',compact('sliders'));
    }

    public function update(Request $request)
    {
        $slider_id = $request->id;
        $old_img = $request->old_image;

        if($request->file('slider_image')){
            $image = $request->file('slider_image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(2376,807)->save('upload/slider/'.$name_gen);
            $save_url = 'upload/slider/'.$name_gen;

            if (file_exists($old_img)) {
            unlink($old_img);
            }

            Slider::findOrFail($slider_id)->update([
                'slider_title' => $request->slider_title,
                'short_title' => $request->short_title,
                'slider_image' => $save_url,
            ]);

        $notification = array(
                'message' => 'Slider Updated with image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('slider.index')->with($notification);

        } else {
            Slider::findOrFail($slider_id)->update([
                'slider_title' => $request->slider_title,
                'short_title' => $request->short_title,
            ]);

            $notification = array(
                'message' => 'Slider Updated without image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('slider.all')->with($notification);
        }
    }

    public function delete($id)
    {
        $slider = Slider::findOrFail($id);
        $img = $slider->slider_image;
        unlink($img);

        Slider::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Slider Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

}
