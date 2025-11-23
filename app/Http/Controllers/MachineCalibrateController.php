<?php

namespace App\Http\Controllers;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ReactorCalibrate;
use App\Models\CcCalibrate;

class MachineCalibrateController extends Controller{
        
    /*public function index($id){
        $machine = Machine::findOrFail($id);
        return view('machines.machine-calibrate-setup', compact('machine'));
    }*/

    public function index($id)
        {
            $machine = Machine::findOrFail($id);

            // Pagination 10 per page
            $reactorData = ReactorCalibrate::where('machine_id', $id)
                            ->orderBy('created_at','desc')
                            ->paginate(10, ['*'], 'reactorPage');

            $ccData = CcCalibrate::where('machine_id', $id)
                            ->orderBy('created_at','desc')
                            ->paginate(10, ['*'], 'ccPage');

            return view('machines.machine-calibrate-setup', compact('machine','reactorData','ccData'));
        }

       // Delete Reactor (Ajax)
    public function deleteReactor($id)
        {
            $row = ReactorCalibrate::findOrFail($id);
            $row->delete();

            return response()->json(['status' => 'success', 'message' => 'Reactor Calibrate deleted successfully!']);
        }

        // Delete CC (Ajax)
    public function deleteCc($id)
        {
            $row = CcCalibrate::findOrFail($id);
            $row->delete();

            return response()->json(['status' => 'success', 'message' => 'CC Calibrate deleted successfully!']);
        }

    public function saveReactor(Request $request, $id){
    $request->validate([
        'reactor_value' => 'required|numeric'
    ]);

    $machine = Machine::findOrFail($id);

    $reactor = ReactorCalibrate::create([
        'machine_id' => $machine->id,
        'value' => (float) $request->reactor_value
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Reactor Calibrate value saved successfully!',
        'data' => $reactor
    ]);
}


    // CC Calibrate save
    public function saveCc(Request $request, $id)
{
    $request->validate(['cc_value' => 'required|numeric']);

    CcCalibrate::create([
        'machine_id' => $id,
        'value' => $request->cc_value
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'CC Calibrate value saved successfully!'
    ]);
}
}
