@extends('layout.app')
@section('title', 'Lab360::Settings-Machine Calibrate Setup')
@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="row justify-content-center">
            <div class="col-md-12"><h1>Machine Name - {{ $machine->machine_name }}</h1></div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center"><h4 class="card-title">Machine Calibrate</h4></div>

                        {{-- Reactor Calibrate --}}
                        <form id="reactorCalibrateForm" method="POST" action="{{ route('machines.reactor-calibrate.save', $machine->id) }}">
                            @csrf
                            <div class="form-group">
                               <div class="d-flex justify-content-between">
                                    <label>Reactor Calibrate</label>
                                    <button type="button" class="btn btn-danger btn-sm" id="startReactorBtn">Start</button>
                                </div>
                                <div class="input-group mt-2">
                                    <input type="text" name="reactor_value" class="form-control" placeholder="Enter Value" aria-label="Reactor Value">
                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-facebook" type="button">ml</button>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2 btn-block">Save Reactor</button>
                        </form>

                        {{-- CC Calibrate --}}
                        <form id="ccCalibrateForm" method="POST" action="{{ route('machines.cc-calibrate.save', $machine->id) }}">
                            @csrf
                            <div class="form-group mt-4">
                                <div class="d-flex justify-content-between">
                                    <label>>Cleaning Chember Calibrate</label>
                                    <button type="button" class="btn btn-danger btn-sm" id="startCcBtn">Start</button>
                                </div>
                                <div class="input-group mt-2">
                                    <input type="text" name="cc_value" class="form-control" placeholder="Enter Value" aria-label="CC Value">
                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-facebook" type="button">ml</button>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2 btn-block">Save CC</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tables --}}
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Reactor Calibrate</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Value</th>
                                        <th>Created Date</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reactorData as $row)
                                    <tr id="reactorRow-{{ $row->id }}">
                                        <td>{{ $row->value }}</td>
                                        <td>{{ $row->created_at->format('d M, Y H:i') }}</td>
                                        <td><button class="btn btn-sm btn-danger deleteReactorBtn" data-id="{{ $row->id }}">Delete</button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $reactorData->appends(['ccPage' => $ccData->currentPage()])->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">CC Calibrate</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Value</th>
                                        <th>Created Date</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ccData as $row)
                                    <tr id="ccRow-{{ $row->id }}">
                                        <td>{{ $row->value }}</td>
                                        <td>{{ $row->created_at->format('d M, Y H:i') }}</td>
                                        <td><button class="btn btn-sm btn-danger deleteCcBtn" data-id="{{ $row->id }}">Delete</button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $ccData->appends(['reactorPage' => $reactorData->currentPage()])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>

<script>
window.Pusher = Pusher;

// IIFE (already includes Echo)
window.Echo = Echo;

const macId = "{{ $machine->mac_id }}";

// Listen to machine channel
window.Echo.channel(`machine.${macId}`)
    .listen('.device.data', (e) => {
        console.log("📡 WS Received:", e);
        if(e.command === 'start'){
            Swal.fire('Device Started!', `Start command received for ${macId}`, 'info');
        }
    });

// ✅ এখানে Echo/Pusher connection state check
if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
    console.log('Connection state:', window.Echo.connector.pusher.connection.state);
} else {
    console.warn('Echo or Pusher not initialized yet!');
}

console.log('✅ WebSocket initialized');
</script>


<script>
$(document).ready(function(){
    const macId = "{{ $machine->mac_id }}";

    // Listen again safely (optional, but ensures jQuery ready)
    if(window.Echo && window.Echo.channel){
        window.Echo.channel(`machine.${macId}`)
            .listen('.device.data', (e) => {
                console.log("📡 Received command:", e);
                if(e.command === 'start') {
                    Swal.fire('Device Started!', `Start command received for ${macId}`, 'info');
                }
            });
    }

    // Submit forms (Reactor + CC)
    $('#reactorCalibrateForm, #ccCalibrateForm').submit(function(e){
        e.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            success: function(res){
                Swal.fire({icon:'success', title:'Saved!', text:res.message, timer:1500, showConfirmButton:false})
                    .then(() => location.reload());
            },
            error: function(err){
                Swal.fire('Error!', err.responseJSON?.message || 'Failed to save value.', 'error');
            }
        });
    });

    // Delete buttons
    $('.deleteReactorBtn, .deleteCcBtn').click(function(){
        let id = $(this).data('id');
        let type = $(this).hasClass('deleteReactorBtn') ? 'reactor' : 'cc';
        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the record!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if(result.isConfirmed){
                $.ajax({
                    url: `/machines/${type}-calibrate/${id}`,
                    type: 'DELETE',
                    data: {_token: '{{ csrf_token() }}'},
                    success: function(res){
                        $(`#${type}Row-${id}`).remove();
                        Swal.fire('Deleted!', res.message, 'success');
                    },
                    error: function(){ Swal.fire('Error!', 'Failed to delete.', 'error'); }
                });
            }
        });
    });

    // Start Reactor via WebSocket
    $('#startReactorBtn').click(function(){
        $.ajax({
            url: "/device/send",
            method: "POST",
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            data: { mac_id: macId, command: "RC start" },
            success: function(res){
                console.log("✅ WebSocket Command Sent:", res);
                Swal.fire({icon:'success', title:'Command Sent!', text:'RC start command broadcasted via WebSocket.'});
            },
            error: function(err){
                Swal.fire({icon:'error', title:'Error!', text:'Failed to send command.'});
            }
        });
    });

    // Start CC via WebSocket
    $('#startCcBtn').click(function(){
        $.ajax({
            url: "/device/send",
            method: "POST",
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            data: { mac_id: macId, command: "CCC start" },
            success: function(res){
                console.log("✅ WebSocket Command Sent:", res);
                Swal.fire({icon:'success', title:'Command Sent!', text:'CCC start command broadcasted via WebSocket.'});
            },
            error: function(err){
                Swal.fire({icon:'error', title:'Error!', text:'Failed to send command.'});
            }
        });
    });
});
</script>
@endsection

