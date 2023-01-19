<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShipmentArea;
use App\Models\ShipmentState;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function stateIndex()
    {
        $state = ShipmentState::latest()->get();
        return view('admin.location.state.index',compact('state'));
    }

    public function createState()
    {
        return view('admin.location.state.create');
    }

    public function storeState(Request $request)
    {
        ShipmentState::insert([
            'state_name' => $request->state_name
        ]);

        $notification = array(
            'message' => 'State Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('state.index')->with($notification);
    }

    public function editState($id)
    {
        $state = ShipmentState::findOrFail($id);
        return view('admin.location.state.edit',compact('state'));
    }

    public function updateState(Request $request)
    {
        $state_id = $request->id;
        ShipmentState::findOrFail($state_id)->update([
            'state_name' => $request->state_name
        ]);

        $notification = array(
            'message' => 'State Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('state.index')->with($notification);
    }

    public function deleteState($id)
    {
        ShipmentState::findOrFail($id)->delete();

        $notification = array(
            'message' => 'State Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }

    public function areaIndex()
    {
        $area = ShipmentArea::latest()->get();
        return view('admin.location.area.index',compact('area'));
    }

    public function createArea()
    {
        $state = ShipmentState::orderBy('state_name','ASC')->get();
        return view('admin.location.area.create',compact('state'));
    }

    public function storeArea(Request $request)
    {
        ShipmentArea::insert([
            'state_id' => $request->state_id,
            'area_name' => $request->area_name
        ]);

        $notification = array(
            'message' => 'Area Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('area.index')->with($notification);
    }

    public function editArea($id)
    {
        $state = ShipmentState::orderBy('state_name','ASC')->get();
        $area = ShipmentArea::findOrFail($id);

        return view('admin.location.area.edit',compact('area','state'));
    }

    public function updateArea(Request $request)
    {
        $area_id = $request->id;
        ShipmentArea::findOrFail($area_id)->update([
            'state_id' => $request->state_id,
            'area_name' => $request->area_name
        ]);
        $notification = array(
            'message' => 'Area Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('area.index')->with($notification);
    }

    public function deleteArea($id)
    {
        ShipmentArea::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Area Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
