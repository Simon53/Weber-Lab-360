<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Brand;
use App\Models\Location;
use App\Models\Machine;
use App\Models\MachineData;
use App\Events\DeviceDataReceived;

class ESPSetupController extends Controller
{
    // Show reagent setup & machine data list
    public function reagentSetup($machineId)
    {
        $user = auth()->user();

        // Ensure machine belongs to logged-in user
        $machine = Machine::where('id', $machineId)
            ->where('user_id', $user->id)
            ->first();

        if (!$machine) {
            return abort(404, 'Machine not found for this user.');
        }

        $tests = Test::all();
        $locations = Location::all();

        $machineData = MachineData::with([
            'brand',
            'reagentALocation',
            'reagentBLocation',
            'reagentCLocation',
            'reagentDLocation',
            'reagentELocation'
        ])
            ->where('machine_id', $machine->id ?? 0)
            ->latest()
            ->get();

        return view('machines.setup', compact('machine', 'tests', 'locations', 'machineData'));
    }

    // Save reagent locations
    public function saveReagentLocation(Request $request)
    {
        $data = $request->validate([
            'machine_id' => 'required|integer|exists:machines,id',
            'test_name'  => 'required|string|max:255',
            'brand_id'   => 'required|integer|exists:brands,id',
            'mappings'   => 'required|array|min:1',
        ]);

        // create a new entry
        $machineData = new MachineData();
        $machineData->machine_id = (int) $data['machine_id'];
        $machineData->test_name = $data['test_name'];
        $machineData->brand_id = (int) $data['brand_id'];

        // handle reagent mappings (A–E)
        $reagentKeys = ['a', 'b', 'c', 'd', 'e'];
        $i = 0;

        foreach ($data['mappings'] as $reagentId => $locationId) {
            if ($i < count($reagentKeys)) {
                $column = 'reagent_' . $reagentKeys[$i] . '_location_id';
                $machineData->$column = (int) $locationId;
                $i++;
            }
        }

        $machineData->save();

        // Send data to ESP32 via WebSocket (broadcast)
        $machine = Machine::find($data['machine_id']);
        if ($machine) {
            $payload = [
                'mac_id' => $machine->mac_id,
                'test_name' => $data['test_name'],
                'brand_id' => $data['brand_id'],
                'mappings' => $data['mappings'],
            ];
            broadcast(new DeviceDataReceived($payload));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Reagent location mapping saved successfully!'
        ]);
    }

    // Delete machine data
    public function deleteMachineData($id)
    {
        try {
            $machineData = MachineData::find($id);

            if (!$machineData) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Record not found!'
                ], 404);
            }

            // Check if the machine belongs to the authenticated user
            $machine = Machine::where('id', $machineData->machine_id)
                ->where('user_id', auth()->id())
                ->first();

            if (!$machine) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access!'
                ], 403);
            }

            // Prepare data to send to ESP32 before deletion
            $deletedData = [
                'id' => $machineData->id,
                'test_name' => $machineData->test_name,
                'brand_id' => $machineData->brand_id,
                'machine_id' => $machineData->machine_id,
            ];

            // Delete the record
            $machineData->delete();

            // Broadcast delete event to ESP32 via WebSocket
            if ($machine) {
                $payload = [
                    'mac_id' => $machine->mac_id,
                    'command' => 'DELETE_DATA',
                    'deleted_id' => $deletedData['id'],
                    'test_name' => $deletedData['test_name'],
                    'brand_id' => $deletedData['brand_id'],
                    'timestamp' => now()->timestamp,
                ];
                
                broadcast(new DeviceDataReceived($payload))->toOthers();
                
                \Log::info('Delete notification sent to ESP32', [
                    'mac_id' => $machine->mac_id,
                    'deleted_id' => $deletedData['id']
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Deleted successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Delete machine data error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($machineId){
        // Machine load
        $machine = Machine::findOrFail($machineId);

        $locations = Location::all();

        $machineData = MachineData::with([
            'brand',
            'reagentALocation',
            'reagentBLocation',
            'reagentCLocation',
            'reagentDLocation',
            'reagentELocation'
        ])->where('machine_id', $machine->id)->get();

        // Blade view return
        return view('machines.show', compact('machine', 'locations', 'machineData'));
    }


    public function getReagents($brandId, $machineId) {
        $reagents = \App\Models\Reagent::where('brand_id', $brandId)->get();

        $usedLocations = MachineData::where('machine_id', $machineId)
            ->where('brand_id', $brandId)
            ->get([
                'reagent_a_location_id',
                'reagent_b_location_id',
                'reagent_c_location_id',
                'reagent_d_location_id',
                'reagent_e_location_id'
            ])
            ->pluck('reagent_a_location_id', 'reagent_b_location_id', 'reagent_c_location_id', 'reagent_d_location_id', 'reagent_e_location_id')
            ->flatten()
            ->filter() 
            ->unique()
            ->values()
            ->all();

        return response()->json([
            'reagents' => $reagents,
            'usedLocations' => $usedLocations
        ]);
    }
}
