<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Image;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->get();
        return view('admin.brand.index',compact('brands'));
    }

    public function create()
    {
        return view('admin.brand.create');
    }

    public function store(Request $request)
    {
        $image = $request->file('brand_image');
        $genName = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(300,300)->save('upload/brands/'.$genName);
        $imageurl = 'upload/brand/'.$genName;


        Brand::insert([
            'brand_name' => $request->brand_name,
            'brand_slug' => strtolower(str_replace(' ', '-', $request->brand_name)),
            'brand_image' => $imageurl
        ]);

        $notification = array('message' => 'Brand Created!', 'alert-type' => 'success');

        return redirect()->route('brand.index')->with($notification);
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brand.edit',compact('brand'));
    }


    public function update(Request $request)
    {
        $brand_id = $request->id;
        $old_img = $request->old_image;

        if($request->file('brand_image')){
            $image = $request->file('brand_image');
            $genName = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(300,300)->save('upload/brands/'.$genName);
            $imageUrl = 'upload/brand/'.$genName;

            if(file_exists($old_img)){
                unlink($old_img);
            }

            Brand::findOrFail($brand_id)->update([
                'brand_name' => $request->brand_name,
            'brand_slug' => strtolower(str_replace(' ', '-',$request->brand_name)),
            'brand_image' => $imageUrl,
            ]);

            $notification = array(
                'message' => 'Brand Updated with image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('brand.index')->with($notification);
        } else {
            Brand::findOrFail($brand_id)->update([
                'brand_name' => $request->brand_name,
                'brand_slug' => strtolower(str_replace(' ', '-',$request->brand_name)),
            ]);

           $notification = array(
                'message' => 'Brand Updated without image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('brand.index')->with($notification);
        }
    }

    public function delete($id)
    {
        $brand = Brand::findOrFail($id);
        $img = $brand->brand_image;
        unlink($img );

        Brand::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Brand Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
