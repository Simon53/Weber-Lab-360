@extends('layout.app')
@section('title','Lab360::Settings-Reagent Setup v' . time())

@push('scripts-head')
<style>
    /* Disable Echo on this page */
    .echo-disabled { }
</style>
@endpush

@section('content')

<!-- CACHE BUSTER: {{ time() }} -->
<!-- ECHO DISABLED ON THIS PAGE -->

<style>
    
.dropdowns {
  margin-bottom: 20px;
}

.dropdowns select {
  padding: 5px 10px;
  margin: 0 10px;
  font-size: 16px;
}

.box-grid {
  display: grid;
  grid-template-columns: repeat(5, 100px);
  grid-gap: 40px;
  justify-content: center;
}



.box {
  width: 100px;
  height: 80px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid #333;
  cursor: pointer;
  font-weight: bold;
  font-size: 16px;
  user-select: none;
  transition: background-color 0.3s;
  text-align: center;
  padding: 2px;
  
}

.box_model{
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid #333;
  cursor: pointer;
  font-weight: bold;
  font-size: 18px;
  user-select: none;
  transition: background-color 0.3s;
}
.box-grid_model {
  display: grid;
  grid-template-columns: repeat(5, 60px);
  grid-gap: 10px;
  justify-content: center;
}

.box.checked {
  background-color: green;
  color: white;
}

.custom-modal-width {
    max-width: 800px; 
    width: 100%; 
}


.form-check-label {
    margin-left: 8px; 
    font-weight: 500;
    font-size: 16px;
    cursor: pointer;
    
}

.small {
    font-size: 40%;
}

.modal-sm{
    max-width:600px
}

  
/* Checked & disabled locations in modal */
#modalLocationsBody .location-checkbox:checked + .form-check-label,
#modalLocationsBody .location-checkbox:disabled + .form-check-label {
    background-color: #610808ff; /* dark background */
    color: #fff;            /* white text */
    border-radius: 5px;     /* optional, rounded corners */
    padding: 5px 10px;      /* optional, spacing */
    display: inline-block;   /* ensure proper background */
}

/* Optional: reduce opacity for disabled */
#modalLocationsBody .location-checkbox:disabled + .form-check-label {
    opacity: 0.7;
}


</style>
<div class="container-fluid">
     <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                    <h1>Machine Name - {{ $machine->machine_name }}</h1>
                    <div class="d-flex justify-content-center bd-highlight">
                           <div class="p-2 bd-highlight col-md-3">
                                <select class="form-control" id="chooseTest">
                                    <option value="">Select Test</option>
                                        @foreach($tests as $test)
                                            <option value="{{ $test->id }}">{{ $test->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="p-2 bd-highlight col-md-3">
                                    <select class="form-control" id="selectBrand">
                                        <option value="">Select Brand</option>
                                    </select>
                                </div>
                        </div>

                  <!-- Box Grid -->

                  <div class="box-grid mt-5" id="boxGrid">
                        @foreach($locations as $location)
                            @php
                                // check if location is assigned in DB
                                $mapping = $machineData->first(function($data) use ($location) {
                                    return in_array($location->id, [
                                        optional($data->reagentALocation)->id,
                                        optional($data->reagentBLocation)->id,
                                        optional($data->reagentCLocation)->id,
                                        optional($data->reagentDLocation)->id,
                                        optional($data->reagentELocation)->id
                                    ]);
                                });
                            @endphp

                            <div class="box" id="box{{ $location->id }}" data-booked="{{ $mapping ? 1 : 0 }}" style="{{ $mapping ? 'background-color:#520202ff; color:#fff;' : '' }}">
                                @if($mapping)
                                    <div style="text-align:center; font-size:13px; font-weight:normal;">
                                        <div>{{ $location->name }}</div>
                                        <div>{{ $mapping->test_name }}</div>
                                        <div>{{ $mapping->brand->name ?? '' }}</div>
                                        @php
                                            $reagentLabel = '';
                                            if(optional($mapping->reagentALocation)->id == $location->id) $reagentLabel = 'Reagent A';
                                            elseif(optional($mapping->reagentBLocation)->id == $location->id) $reagentLabel = 'Reagent B';
                                            elseif(optional($mapping->reagentCLocation)->id == $location->id) $reagentLabel = 'Reagent C';
                                            elseif(optional($mapping->reagentDLocation)->id == $location->id) $reagentLabel = 'Reagent D';
                                            elseif(optional($mapping->reagentELocation)->id == $location->id) $reagentLabel = 'Reagent E';
                                        @endphp
                                        <div>{{ $reagentLabel }}</div>
                                    </div>
                                @else
                                    {{ $location->name }}
                                @endif
                            </div>
                        @endforeach
                    </div>
                  <!--div class="d-flex justify-content-center bd-highlight mb-3">
                      <div class="p-2 bd-highlight">
                          <div class="box-grid mt-5" id="boxGrid">
                           @foreach($locations as $location)
                                <div class="box" id="box{{ $location->id }}" data-booked="0">
                                    {{ $location->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                  </div-->
                  <!-- Submit button -->
                 
            </div>    
        </div>   
    </div>     

    <div class="container mt-5">
        <h4 class="mb-3">Machine Data List</h4>
        <!-- DEBUG: Test Button -->
        <div class="mb-3">
            <button id="testBtn" class="btn btn-primary btn-sm">TEST: Click Me (jQuery Test)</button>
            <span id="testResult" class="ms-2 text-success"></span>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>Test Name</th>
                        <th>Brand</th>
                        <th>Reagent A</th>
                        <th>Reagent B</th>
                        <th>Reagent C</th>
                        <th>Reagent D</th>
                        <th>Reagent E</th>
                       
                        <th style="display:none">Meta</th>
                        <th>Created</th>
                        <th style="display:none">Updated</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($machineData) && $machineData->count() > 0)
                        @foreach($machineData as $data)
                            <tr id="row-{{ $data->id }}">
                                <td>{{ $data->test_name }}</td>
                                <td>{{ $data->brand->name ?? '' }}</td>
                                <td>{{ $data->reagentALocation->name ?? '' }}</td>
                                <td>{{ $data->reagentBLocation->name ?? '' }}</td>
                                <td>{{ $data->reagentCLocation->name ?? '' }}</td>
                                <td>{{ $data->reagentDLocation->name ?? '' }}</td>
                                <td>{{ $data->reagentELocation->name ?? '' }}</td>
                              
                                <td style="display:none">{{ json_encode($data->meta) }}</td>
                                <td>{{ $data->created_at }}</td>
                                <td style="display:none">{{ $data->updated_at }}</td>
                                <td>
                                   <button type="button" class="btn btn-danger btn-sm delete-btn" 
                                           data-id="{{ $data->id }}">
                                       Delete
                                   </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="18">No machine data found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>   
    </div> 
</div>


<!-- Modal -->
<div class="modal fade" id="addReagentModal" tabindex="-1" aria-labelledby="addReagentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <form id="assignReagentsForm">
        <div class="modal-header">
          <h5 class="modal-title" id="addReagentModalLabel">Assign Reagents & Locations</h5>
          <button type="button" class="btn-danger" data-bs-dismiss="modal" aria-label="Close">X</button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="modalBrandId" name="brand_id">
          <input type="hidden" id="modalTestName" name="test_name">

          <div id="modalReagentsBody"></div>

          <hr>
          <div id="modalLocationsBody" class="d-flex flex-wrap gap-2">
            @foreach($locations as $location)
              @php
                $used = $machineData->first(function($data) use ($location) {
                    return in_array($location->id, [
                        optional($data->reagentALocation)->id,
                        optional($data->reagentBLocation)->id,
                        optional($data->reagentCLocation)->id,
                        optional($data->reagentDLocation)->id,
                        optional($data->reagentELocation)->id
                    ]);
                });
              @endphp
              <div class="form-check" style="width:100px; text-align:center;">
                <input class="form-check-input location-checkbox" type="checkbox" 
                       value="{{ $location->id }}" 
                       id="location{{ $location->id }}" 
                       {{ $used ? 'checked disabled' : '' }}>
                <label class="form-check-label" for="location{{ $location->id }}">
                  {{ $location->name }}
                </label>
              </div>
            @endforeach
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save & Sent</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection


@section('script')
<!-- Disable Echo initialization on this page to prevent errors -->
<script>
    // Prevent Echo from initializing
    if (window.Echo && window.Echo.connector) {
        try {
            window.Echo.disconnect();
        } catch(e) {}
    }
    window.Echo = {
        channel: function() { return this; },
        listen: function() { return this; },
        error: function() { return this; }
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
console.log('SCRIPT VERSION: 3.0 - ' + new Date().toISOString());

jQuery(document).ready(function($) {
    console.log('=== INITIALIZATION ===');
    console.log('jQuery loaded:', typeof $ !== 'undefined');
    console.log('jQuery version:', $.fn.jquery);
    console.log('SweetAlert2 loaded:', typeof Swal !== 'undefined');
    console.log('Document ready');
    
    // Test button handler
    $('#testBtn').on('click', function() {
        console.log('TEST BUTTON CLICKED!');
        $('#testResult').text('jQuery is working! ✓');
        alert('jQuery click event is working!');
    });
    
    // Also test with delegated event
    $(document).on('click', '#testBtn', function() {
        console.log('TEST BUTTON CLICKED (delegated)!');
    });
    
    const mac_id = "{{ $machine->mac_id }}";
    const testSelect = document.getElementById('chooseTest');
    const brandSelect = document.getElementById('selectBrand');
    const modalBrandId = document.getElementById('modalBrandId');
    const modalBrandName = document.getElementById('modalBrandName');
    const modalTestName = document.getElementById('modalTestName');
    const modalReagentsBody = document.getElementById('modalReagentsBody');
    const modalLocationsBody = document.getElementById('modalLocationsBody');
    const bsModal = new bootstrap.Modal(document.getElementById('addReagentModal'), { backdrop: 'static', keyboard: false });
   
    let currentMapping = {};      // { reagentId: locationId }
    let reagentsToAssign = [];    // Selected reagents that need location
    let assignedLocations = [];   // Already used locations

    // ------------------------
    // Test change → load brands
    // ------------------------
    testSelect.addEventListener('change', function() {
        const testId = this.value;
        modalTestName.value = this.options[this.selectedIndex]?.text || '';
        brandSelect.innerHTML = '<option value="">Select Brand</option>';
        if (!testId) return;

        fetch(`/machines/brands-by-test/${testId}`, { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                data.forEach(brand => {
                    const option = document.createElement('option');
                    option.value = brand.id;
                    option.textContent = brand.name;
                    brandSelect.appendChild(option);
                });
            });
    });

    // ------------------------
    // Brand change → load reagents + assigned locations
    // ------------------------
    brandSelect.addEventListener('change', function() {
        const brandId = this.value;
        const brandName = this.options[this.selectedIndex]?.text || '';
        if (!brandId) return;

        modalBrandId.value = brandId;
        modalBrandName.textContent = brandName;

        fetch(`/machines/brands/${brandId}/reagents?machine_id={{ $machine->id }}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            modalReagentsBody.innerHTML = '';
            currentMapping = {};
            reagentsToAssign = [];
            assignedLocations = (data.assignedLocations || []).map(id => parseInt(id));

            // ------------------------
            // Create reagents list
            // ------------------------
            if (!data.reagents || data.reagents.length === 0) {
                modalReagentsBody.innerHTML = '<p class="text-muted">No reagents found.</p>';
                return;
            }

            data.reagents.forEach(r => {
                const div = document.createElement('div');
                div.classList.add('form-check', 'mb-2');

                const input = document.createElement('input');
                input.type = 'checkbox';
                input.name = 'reagent_ids[]';
                input.value = r.id;
                input.id = 'reagent' + r.id;
                input.classList.add('form-check-input');

                const label = document.createElement('label');
                label.classList.add('form-check-label');
                label.setAttribute('for', 'reagent' + r.id);
                label.textContent = r.name;

                div.appendChild(input);
                div.appendChild(label);
                modalReagentsBody.appendChild(div);

                input.addEventListener('change', function() {
                    if (this.checked) {
                        if (reagentsToAssign.length > 0) {
                            this.checked = false;
                            Swal.fire({
                                icon: 'warning',
                                title: 'Assign Location First',
                                text: 'Please assign location for the previously selected reagent before selecting another.',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }
                        reagentsToAssign.push(r.id);
                    } else {
                        reagentsToAssign = reagentsToAssign.filter(id => id !== r.id);
                        delete currentMapping[r.id];
                    }
                });
            });

            // ------------------------
            // Handle location checkboxes with notification
            // ------------------------
            modalLocationsBody.querySelectorAll('.location-checkbox').forEach(locInput => {
                const locId = parseInt(locInput.value);

                // Disable already assigned locations
                if (assignedLocations.includes(locId)) {
                    locInput.checked = true;
                    locInput.disabled = true;
                    locInput.parentElement.style.opacity = 0.6;
                } else {
                    locInput.checked = false;
                    locInput.disabled = false;
                    locInput.parentElement.style.opacity = 1;
                }

                locInput.addEventListener('click', function() {
                    if (assignedLocations.includes(locId)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Location Already Used',
                            text: 'This location is already assigned to a reagent and cannot be used again.',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    if (reagentsToAssign.length === 0) return;

                    const reagentId = reagentsToAssign[0];
                    currentMapping[reagentId] = locId;
                    reagentsToAssign.shift();
                    this.disabled = true;
                    this.parentElement.style.opacity = 0.6;
                    assignedLocations.push(locId); // mark as used
                });
            });

            bsModal.show();
        });
    });document.getElementById('selectBrand').addEventListener('change', function() {
        const brandId = this.value;
        if (!brandId) return;

        modalBrandId.value = brandId;
        modalReagentsBody.innerHTML = '';
        currentMapping = {};
        reagentsToAssign = [];

        fetch(`/machines/brands/${brandId}/reagents?machine_id={{ $machine->id }}`, {
            headers: {'Accept':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')}
        })
        .then(res => res.json())
        .then(data => {
            assignedLocations = (data.assignedLocations || []).map(id => parseInt(id));

            if(!data.reagents || data.reagents.length===0){
                modalReagentsBody.innerHTML = '<p>No reagents found</p>';
                return;
            }

            data.reagents.forEach(r=>{
                const div = document.createElement('div');
                div.classList.add('form-check','mb-2');

                const input = document.createElement('input');
                input.type='checkbox';
                input.classList.add('form-check-input');
                input.id='reagent'+r.id;
                input.value=r.id;

                const label = document.createElement('label');
                label.classList.add('form-check-label');
                label.setAttribute('for','reagent'+r.id);
                label.textContent=r.name;

                div.appendChild(input);
                div.appendChild(label);
                modalReagentsBody.appendChild(div);

                input.addEventListener('change', function(){
                    if(this.checked){
                        if(reagentsToAssign.length>0){
                            this.checked=false;
                            Swal.fire('Assign Location First','Please assign location for previous reagent','warning');
                            return;
                        }
                        reagentsToAssign.push(r.id);
                    }else{
                        reagentsToAssign = reagentsToAssign.filter(id=>id!==r.id);
                        delete currentMapping[r.id];
                    }
                });
            });

            // Locations
            modalLocationsBody.querySelectorAll('.location-checkbox').forEach(loc=>{
                const locId=parseInt(loc.value);

                loc.addEventListener('click', function(){
                    if(assignedLocations.includes(locId)){
                        Swal.fire('Location Used','This location is already assigned','warning');
                        return;
                    }
                    if(reagentsToAssign.length===0) return;

                    const reagentId=reagentsToAssign.shift();
                    currentMapping[reagentId]=locId;
                    loc.disabled=true;
                    loc.parentElement.style.opacity=0.6;
                    assignedLocations.push(locId);
                });
            });

            bsModal.show();
        });
    });

    // ------------------------
    // Submit form
    // ------------------------
  document.getElementById('assignReagentsForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const allReagentIds = Array.from(modalReagentsBody.querySelectorAll('input.form-check-input')).map(i => parseInt(i.value));
    const selectedReagents = Array.from(modalReagentsBody.querySelectorAll('input.form-check-input:checked')).map(i => parseInt(i.value));

    if (selectedReagents.length !== allReagentIds.length) {
        Swal.fire('Incomplete Selection', 'Select all reagents', 'warning');
        return;
    }

    const missingLocs = selectedReagents.filter(rId => !currentMapping[rId]);
    if (missingLocs.length > 0) {
        Swal.fire('Missing Locations', 'Assign location for every reagent', 'warning');
        return;
    }

    const payload = {
        machine_id: {{ $machine->id }},
        test_name: modalTestName.value,
        brand_id: modalBrandId.value,
        mappings: currentMapping
    };

    fetch("{{ route('machines.saveReagentLocation') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        const modalEl = document.getElementById('addReagentModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();

        // Optional: Success handling (আপনি চাইলে এখানে SweetAlert দিতে পারেন)
    })
    .catch(() => {
        const modalEl = document.getElementById('addReagentModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();

        // 🔹 Error SweetAlert + page reload after OK
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while saving data.'
        }).then(() => {
            location.reload();
        });
    });
});


    // No need for extra modal hidden event for reload

    // ------------------------
    // Delete record
    // ------------------------
    console.log('Setting up delete button handler');
    console.log('SweetAlert2 available:', typeof Swal !== 'undefined');
    
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $btn = $(this);
        const id = $btn.data('id');
        
        console.log('=== DELETE BUTTON CLICKED ===');
        console.log('Button:', $btn[0]);
        console.log('ID from data-id:', id);
        
        if (!id) {
            alert('Error: No ID found!');
            return false;
        }
        
        console.log('About to show Swal confirmation...');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "This record will be deleted permanently!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            try {
                console.log('Swal closed. Result:', result);
                console.log('isConfirmed:', result.isConfirmed);
                
                if (result.isConfirmed) {
                    console.log('User confirmed deletion');
                    performDelete(id);
                } else {
                    console.log('User cancelled deletion');
                }
            } catch (err) {
                console.error('Error in Swal then callback:', err);
                alert('Error after confirmation: ' + err.message);
            }
        }).catch((error) => {
            console.error('Swal error:', error);
            alert('Swal error: ' + error.message);
        });
        
        return false;
    });
    
    function performDelete(id) {
        console.log('=== PERFORMING DELETE ===');
        console.log('ID:', id);
        
        try {
            const deleteUrl = '/machines/data/' + id;
            console.log('Delete URL:', deleteUrl);
            
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            console.log('CSRF Token:', csrfToken ? 'Found: ' + csrfToken : 'NOT FOUND!');
            
            console.log('About to call $.ajax...');
            
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                beforeSend: function() {
                    console.log('beforeSend: Sending DELETE request...');
                },
                success: function(response) {
                    console.log('=== DELETE SUCCESS ===');
                    console.log('Response:', response);
                    
                    if (response.status === 'success') {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                timer: 1200,
                                showConfirmButton: false
                            }).then(() => {
                                console.log('Reloading page...');
                                location.reload();
                            });
                        } else {
                            alert('Deleted successfully!');
                            location.reload();
                        }
                    } else {
                        console.error('Delete failed:', response.message);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Error', response.message || 'Failed to delete the record.', 'error');
                        } else {
                            alert('Error: ' + (response.message || 'Failed to delete the record.'));
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('=== DELETE ERROR ===');
                    console.error('Status:', status);
                    console.error('Error:', error);
                    console.error('XHR Status Code:', xhr.status);
                    console.error('XHR Status Text:', xhr.statusText);
                    console.error('Response Text:', xhr.responseText);
                    console.error('Response JSON:', xhr.responseJSON);
                    
                    let errorMsg = 'An error occurred while deleting the record.';
                    
                    if (xhr.status === 404) {
                        errorMsg = 'Record not found (404).';
                    } else if (xhr.status === 419) {
                        errorMsg = 'CSRF token mismatch (419). Please refresh the page.';
                    } else if (xhr.status === 403) {
                        errorMsg = 'Unauthorized access (403).';
                    } else if (xhr.status === 500) {
                        errorMsg = 'Server error (500).';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg += ' ' + xhr.responseJSON.message;
                        }
                    }
                    
                    console.error('Error message:', errorMsg);
                    
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', errorMsg, 'error');
                    } else {
                        alert('Error: ' + errorMsg);
                    }
                },
                complete: function() {
                    console.log('AJAX request complete');
                }
            });
            
            console.log('$.ajax called successfully');
            location.reload();
            
        } catch (err) {
            console.error('=== EXCEPTION IN performDelete ===');
            console.error('Error:', err);
            console.error('Stack:', err.stack);
            alert('JavaScript error: ' + err.message);
            //location.reload();
        }
    }



    // WebSocket listen - Re-enabled with robust error handling
    let deviceConnected = false;
    let echoSubscribed = false;

    function subscribeToMachineChannel() {
        if (echoSubscribed) return; // Prevent multiple subscriptions
        
        try {
            if (window.Echo && window.Echo.channel && typeof window.Echo.channel === 'function') {
                console.log('Subscribing to machine channel:', mac_id);
                
                window.Echo.channel('machine.' + mac_id)
                    .listen('.device.data', (e) => {
                        deviceConnected = true;
                        console.log('Device data received:', e);
                        const rxWindow = document.getElementById('rxWindow');
                        if (rxWindow) {
                            const div = document.createElement('div');
                            div.textContent = JSON.stringify(e.data);
                            rxWindow.appendChild(div);
                            rxWindow.scrollTop = rxWindow.scrollHeight;
                        }
                    })
                    .error((error) => {
                        console.warn('WebSocket channel error:', error);
                    });
                
                echoSubscribed = true;
                console.log('Successfully subscribed to machine.' + mac_id);
            } else {
                console.warn('Echo not available, WebSocket features disabled');
            }
        } catch (error) {
            console.warn('Failed to subscribe to machine channel:', error.message);
           
        }
    }

    // Subscribe after a small delay to ensure Echo is ready
    setTimeout(subscribeToMachineChannel, 1000);
});
</script>
@endsection