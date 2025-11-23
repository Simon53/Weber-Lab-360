@extends('layout.app')
@section('title', 'Lab360::Settings-Machine Setup')

@section('content')

<style>
.machineRadio {
    width: 16px;
    height: 16px;
    accent-color: #007bff;
    cursor: pointer;
}
.form-group label {
    font-weight: 500;
}
</style>

<div class="container-fluid">
    <div class="content-wrapper">
        <div class="row justify-content-center">
            {{-- Machine Add Form --}}
            <div class="col-md-12">
                <h1>Machine Name - {{ $machine->machine_name }}</h1>
            </div>

            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">Machine Setup</h3>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        {{-- ===================== DRAIN SECTION ===================== --}}
                        <button type="button" class="btn btn-warning btn-sm mb-2" style="display:none" id="testButton">🧪 Test WebSocket</button>
                        
                        <form id="drainForm">
                            @csrf
                            <div class="col-md-12">
                                <h4>Drain</h4>
                            </div>

                            <div class="row mt-3 align-items-center">
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <input type="radio" name="drainOption" id="drainPipe" class="machineRadio toggleable" value="pipe">
                                        <label for="drainPipe" class="ml-1">Pipe</label>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="form-group mb-0">
                                                <input type="radio" name="drainOption" id="drainContainer" class="machineRadio toggleable" value="container">
                                                <label for="drainContainer" class="ml-1">Container</label>
                                            </div>
                                        </div>

                                        <div class="col-md-8" id="drainMlGroup" style="display: none;">
                                            <div class="input-group mt-2">
                                                <input type="text" class="form-control" name="drain_ml" placeholder="Enter Value">
                                                <div class="input-group-append">
                                                    <button class="btn btn-sm btn-facebook" type="button">ml</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3 text-right">
                                    <button type="button" class="btn btn-primary btn-sm" id="saveDrainBtn">Save & Sent Drain</button>
                                </div>
                            </div>
                        </form>

                        <hr>

                        {{-- ===================== RODI SECTION ===================== --}}
                        <form id="rodiForm">
                            @csrf
                            <div class="col-md-12">
                                <h4>RODI</h4>
                            </div>

                            <div class="row mt-3 align-items-center">
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <input type="radio" name="rodiOption" id="rodiSupply" class="machineRadio toggleable" value="pipe">
                                        <label for="rodiSupply" class="ml-1">Supply</label>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="form-group mb-0">
                                                <input type="radio" name="rodiOption" id="rodiContainer" class="machineRadio toggleable" value="container">
                                                <label for="rodiContainer" class="ml-1">Container</label>
                                            </div>
                                        </div>

                                        <div class="col-md-8" id="rodiMlGroup" style="display: none;">
                                            <div class="input-group mt-2">
                                                <input type="text" class="form-control" name="rodi_ml" placeholder="Enter Value">
                                                <div class="input-group-append">
                                                    <button class="btn btn-sm btn-facebook" type="button">ml</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3 text-right">
                                    <button type="button" class="btn btn-primary btn-sm" id="saveRodiBtn">Save & Sent RODI</button>
                                </div>
                            </div>
                        </form>



                         {{-- ===================== others ===================== --}}
                        <form id="othersForm">
                            @csrf
                          
                            <div class="row mt-3 align-items-center">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Clarity Test</label>
                                        <div class="input-group">
                                            <div class="switch-container text-center">
                                                <label class="switch">
                                                    <input type="checkbox" class="toggleSwitch" data-target="claritytest">
                                                    <span class="slider"></span>
                                                </label>
                                                <p id="claritytest" class="statusText mt-1 mb-0">OFF</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                     <div class="form-group">
                                        <label>Tem. Test </label>
                                        <div class="input-group">
                                            <div class="switch-container text-center">
                                                <label class="switch">
                                                    <input type="checkbox" class="toggleSwitch" data-target="temtest">
                                                    <span class="slider"></span>
                                                </label>
                                                <p id="temtest" class="statusText mt-1 mb-0">OFF</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                      <div class="form-group">
                                        <label>Alarm</label>
                                        <div class="input-group">
                                            <div class="switch-container text-center">
                                                <label class="switch">
                                                    <input type="checkbox" class="toggleSwitch" data-target="alarm">
                                                    <span class="slider"></span>
                                                </label>
                                                <p id="alarm" class="statusText mt-1 mb-0">OFF</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3 text-right">
                                    <button type="button" class="btn btn-primary btn-sm" id="saveOthersBtn">Save & Save Others</button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection


@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const macId = "{{ $machine->mac_id }}";
const machineId = "{{ $machine->id }}";

// ===========================
// 🔹 SAVE & RELOAD FUNCTION
// ===========================
function showSuccessAndReload(message) {
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: message,
        confirmButtonText: 'OK'
    }).then(() => {
        location.reload();
    });
}

// ===========================
// 🔹 ERROR POPUP
// ===========================
function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: message
    });
}

// ===========================
// 🔹 DOCUMENT READY
// ===========================
$(document).ready(function(){

        // Show/hide ML input groups when radio option changes
        function toggleMlGroup(radioName, groupSelector) {
            const val = $(`input[name="${radioName}"]:checked`).val();
            if (val === 'container') {
                $(groupSelector).show();
            } else {
                $(groupSelector).hide();
            }
        }

        // Initialize visibility on page load
        toggleMlGroup('drainOption', '#drainMlGroup');
        toggleMlGroup('rodiOption', '#rodiMlGroup');

        // Attach change handlers
        $('input[name="drainOption"]').on('change', function() {
            toggleMlGroup('drainOption', '#drainMlGroup');
        });

        $('input[name="rodiOption"]').on('change', function() {
            toggleMlGroup('rodiOption', '#rodiMlGroup');
        });


    // -------------------------------------------
    // 🔹 SAVE DRAIN
    // -------------------------------------------
    $('#saveDrainBtn').click(function(){
        const drainOption = $('input[name="drainOption"]:checked').val();
        const drainMl = $('input[name="drain_ml"]').val();

        if (!drainOption) {
            return Swal.fire('Warning!', 'Please select a drain option.', 'warning');
        }

        if (drainOption === 'container' && !drainMl) {
            return Swal.fire('Warning!', 'Please enter container value.', 'warning');
        }

        // Build ESP32 command
        let command = drainOption === 'pipe'
            ? 'drain=pipe'
            : `drain=container ${drainMl}`;

        // First save to DB
        const drainPayload = { machine_id: machineId, type: drainOption, ml_value: drainMl };
        console.log('Saving drain payload:', drainPayload);
        fetch('/machine/save-drain', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(drainPayload)
        })
        .then(r => {
            console.log('Save-drain response status:', r.status);
            if (!r.ok) {
                return r.text().then(text => { throw new Error('Save-drain failed: ' + r.status + ' ' + text); });
            }
            return r.json();
        })
        .then(saveResp => {
            // then send command to device
            const formData = new FormData();
            formData.append('mac_id', macId);
            formData.append('command', command);

            console.log('Sending device command for drain:', command);
            return fetch('/device/send', {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            }).then(r => {
                console.log('Device send status:', r.status);
                return saveResp;
            });
        })
        .then(() => {
            showSuccessAndReload('Drain setup saved & sent to ESP32.');
        })
        .catch(err => {
            console.error('Error saving/sending drain:', err);
            showError('Failed to save/send Drain command: ' + (err.message || 'unknown'));
        });
    });

    // -------------------------------------------
    // 🔹 SAVE RODI
    // -------------------------------------------
    $('#saveRodiBtn').click(function(){
        const rodiOption = $('input[name="rodiOption"]:checked').val();
        const rodiMl = $('input[name="rodi_ml"]').val();

        if (!rodiOption) {
            return Swal.fire('Warning!', 'Please select a RODI option.', 'warning');
        }

        if (rodiOption === 'container' && !rodiMl) {
            return Swal.fire('Warning!', 'Please enter container value.', 'warning');
        }

        let command = rodiOption === 'pipe'
            ? 'rodi=supply'
            : `rodi=container ${rodiMl}`;

        // Save to DB then send to device
        const rodiPayload = { machine_id: machineId, type: rodiOption, ml_value: rodiMl };
        console.log('Saving rodi payload:', rodiPayload);
        fetch('/machine/save-rodi', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(rodiPayload)
        })
        .then(r => {
            console.log('Save-rodi response status:', r.status);
            if (!r.ok) return r.text().then(text => { throw new Error('Save-rodi failed: ' + r.status + ' ' + text); });
            return r.json();
        })
        .then(saveResp => {
            const formData = new FormData();
            formData.append('mac_id', macId);
            formData.append('command', command);

            console.log('Sending device command for rodi:', command);
            return fetch('/device/send', {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            }).then(r => { console.log('Device send status (rodi):', r.status); return saveResp; });
        })
        .then(() => {
            showSuccessAndReload('RODI setup saved & sent to ESP32.');
        })
        .catch(err => {
            console.error('Error saving/sending rodi:', err);
            showError('Failed to save/send RODI command: ' + (err.message || 'unknown'));
        });
    });

    // -------------------------------------------
    // 🔹 SAVE OTHERS
    // -------------------------------------------
    $('#saveOthersBtn').click(function(){

        const clarityTest = $('input[data-target="claritytest"]').is(':checked') ? 'ON' : 'OFF';
        const temTest = $('input[data-target="temtest"]').is(':checked') ? 'ON' : 'OFF';
        const alarm = $('input[data-target="alarm"]').is(':checked') ? 'ON' : 'OFF';

        let command = `clarity=${clarityTest},temp=${temTest},alarm=${alarm}`;

        // Save 'others' to DB first
        const othersPayload = { machine_id: machineId, clarity: clarityTest, tem: temTest, alarm: alarm };
        console.log('Saving others payload:', othersPayload);
        fetch('/machine/save-others', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(othersPayload)
        })
        .then(r => {
            console.log('Save-others response status:', r.status);
            if (!r.ok) return r.text().then(text => { throw new Error('Save-others failed: ' + r.status + ' ' + text); });
            return r.json();
        })
        .then(saveResp => {
            const formData = new FormData();
            formData.append('mac_id', macId);
            formData.append('command', command);

            console.log('Sending device command for others:', command);
            return fetch('/device/send', {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            }).then(r => { console.log('Device send status (others):', r.status); return saveResp; });
        })
        .then(() => {
            showSuccessAndReload('Other settings saved & sent to ESP32.');
        })
        .catch(err => {
            console.error('Error saving/sending others:', err);
            showError('Failed to save/send settings to ESP32: ' + (err.message || 'unknown'));
        });
    });

});
</script>

@endsection
