
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\EspSensorController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\MachineCalibrateController;
//Route::post('/esp-sensor', [EspSensorController::class, 'store']);
use App\Events\NewTerminalData;
use App\Events\DeviceDataReceived;

// Health check endpoint
Route::get('/health', function() {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});

//Route::post('/machine/connect', [MachineController::class,'connectDevice']);

//Route::post('/machines/connect-device', [MachineController::class, 'connectDevice'])->name('machines.connect-device');

Route::post('/machines/connect-device', [MachineController::class, 'connectDevice'])->name('machines.connect-device');
Route::get('/machines/{mac_id}', [MachineController::class, 'getMachineData'])->name('machines.get-data');

// ESP32 Device Data Endpoint - No authentication required
Route::post('/device/send-data', function(Request $request) {
    $data = $request->validate([
        'mac_id' => 'required|string',
        'cmd' => 'nullable|string',
        'value' => 'nullable',
        'timestamp' => 'nullable|integer',
    ]);

    // Broadcast to connected clients
    event(new DeviceDataReceived([
        'mac_id' => $data['mac_id'],
        'status' => 'ok',
        'message' => 'Data received from device',
        'cmd' => $data['cmd'] ?? null,
        'value' => $data['value'] ?? null,
        'timestamp' => $data['timestamp'] ?? now()->timestamp,
    ]));

    return response()->json(['status' => 'success', 'message' => 'Data received']);
});

// Send command to ESP32 device
Route::post('/device/send-command', function(Request $request) {
    try {
        $data = $request->validate([
            'mac_id' => 'required|string',
            'command' => 'required|string',
            'payload' => 'nullable|array',
        ]);

        \Log::info('Command received:', $data);

        // Broadcast command to the specific device channel
        try {
            event(new NewTerminalData([
                'mac_id' => $data['mac_id'],
                'command' => $data['command'],
                'payload' => $data['payload'] ?? null,
                'timestamp' => now()->timestamp,
            ]));
        } catch (\Exception $broadcastError) {
            \Log::error('Broadcast error: ' . $broadcastError->getMessage());
            // Continue even if broadcast fails
        }

        return response()->json([
            'status' => 'success', 
            'message' => 'Command sent to device',
            'data' => $data
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Send command error: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 400);
    }
});

// Save Reactor Calibrate
Route::post('/machines/{machine}/reactor-calibrate', [MachineCalibrateController::class, 'saveReactor'])->name('machines.reactor-calibrate.save');
Route::post('/machines/{machine}/cc-calibrate', [MachineCalibrateController::class, 'saveCc'])->name('machines.cc-calibrate.save');

Route::post('/send-command', function(Request $request){
    $data = $request->input('data'); // JSON data
    event(new NewTerminalData($data));
    return response()->json(['status' => 'success', 'data' => $data]);
});

Route::get('/test-event', function() {
    event(new DeviceDataReceived([
        'mac_id' => '8C:4F:00:AC:26:EC',
        'status' => 'ok',
        'message' => 'Test message from Laravel',
        'data' => ['test' => 123]
    ]));

    return 'Event sent!';
});

