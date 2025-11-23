<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MachineData;
use App\Models\Machine;
use App\Models\Test;
use App\Models\Location;

class MachineDataController extends Controller{


    public function index()
    {
        $machine = Machine::first(); 
        $tests = Test::all();
        $locations = Location::all();

        $machineData = MachineData::with([
            'brand',
            'reagentALocation',
            'reagentBLocation',
            'reagentCLocation',
            'reagentDLocation',
            'reagentELocation'
        ])->get();

        return view('machines.setup', compact('machine', 'tests', 'locations', 'machineData'));
    }


    public function store(Request $request)
    {
        MachineData::create([
            'machine_id' => $request->machine_id,
            'chip_id' => $request->chip_id,
            'test_name' => $request->test_name,
            'brand_id' => $request->brand_id,
            'reagent_a_location_id' => $request->reagent_a_location_id,
            'reagent_b_location_id' => $request->reagent_b_location_id,
            'reagent_c_location_id' => $request->reagent_c_location_id,
            'reagent_d_location_id' => $request->reagent_d_location_id,
            'reagent_e_location_id' => $request->reagent_e_location_id
        ]);

        return response()->json(['success' => true, 'message' => 'Data saved successfully']);
    }


    

}
