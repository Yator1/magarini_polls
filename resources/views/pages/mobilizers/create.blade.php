{{-- resources/views/pages/mobilizers/create.blade.php --}}
@extends('layouts.master')
@section('title') Create Mobilizer @endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Create Mobilizer</h4>
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
            </div>

            <div class="card-body">
                <div class="live-preview">
                   <form class="needs-validation" novalidate method="POST" action="{{ route('mobilizers.store') }}">
    @csrf

    {{-- 🔔 Display validation errors and instructions --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors before submitting:</strong>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="signin-other-title text-center">
        <h5 class="fs-13 mb-4 title text-muted">Add Mobilizer</h5>
    </div>

    <div class="row g-3 needs-validation">

        {{-- ✅ Each input now preserves old() and shows field-level error --}}
        <div class="col-md-4">
            <label for="first_name" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                   name="first_name" value="{{ old('first_name') }}" placeholder="Enter Name" required>
            @error('first_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="age" class="form-label">Age</label>
            <input type="number" class="form-control @error('age') is-invalid @enderror"
                   name="age" value="{{ old('age') }}" placeholder="Enter Age">
            @error('age')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="id_no" class="form-label">ID Number <span class="text-danger">*</span></label>
            <input type="number" class="form-control @error('id_no') is-invalid @enderror"
                   name="id_no" value="{{ old('id_no') }}" placeholder="Enter ID Number">
            @error('id_no')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="phone_no" class="form-label">Phone Number <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('phone_no') is-invalid @enderror"
                   name="phone_no" value="{{ old('phone_no') }}" placeholder="Enter Phone Number" required>
            @error('phone_no')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="role_id" class="form-label">Group <span class="text-danger">*</span></label>
            <select class="js-example-basic-single form-control @error('role_id') is-invalid @enderror"
                    name="role_id" required>
                <option value="" disabled {{ old('role_id') ? '' : 'selected' }}>Select Group</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            @error('role_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- County hidden --}}
        <input type="hidden" name="county_id" value="14">

        <div class="col-md-4">
            <label for="sub_county_id" class="form-label">Sub County <span class="text-danger">*</span></label>
            <select class="js-example-basic-single form-control @error('sub_county_id') is-invalid @enderror"
                    name="sub_county_id" id="sub_county_id" required>
                <option value="" disabled {{ old('sub_county_id') ? '' : 'selected' }}>Select Sub County</option>
                @foreach($subCounties as $subCounty)
                    <option value="{{ $subCounty->id }}" {{ old('sub_county_id') == $subCounty->id ? 'selected' : '' }}>
                        {{ $subCounty->name }}
                    </option>
                @endforeach
            </select>
            @error('sub_county_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="ward_id" class="form-label">Ward <span class="text-danger">*</span></label>
            <select class="js-example-basic-single form-control @error('ward_id') is-invalid @enderror"
                    name="ward_id" id="ward_id" required>
                <option value="" disabled {{ old('ward_id') ? '' : 'selected' }}>Select Ward</option>
                {{-- Wards dynamically loaded via JS --}}
            </select>
            @error('ward_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="pstation_code" class="form-label">Polling Station <span class="text-danger">*</span></label>
            <select class="js-example-basic-single form-control @error('pstation_code') is-invalid @enderror"
                    name="pstation_code" id="pstation_code" required>
                <option value="" disabled {{ old('pstation_code') ? '' : 'selected' }}>Select Polling Station</option>
                <option value="not_in_list" {{ old('pstation_code') == 'not_in_list' ? 'selected' : '' }}>-- {Not in list} --</option>
                @foreach($pollingStations as $ps)
                    <option value="{{ $ps->id }}" {{ old('pstation_code') == $ps->id ? 'selected' : '' }}>
                        {{ $ps->name }}
                    </option>
                @endforeach
            </select>
            @error('pstation_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- New polling station --}}
        <div class="col-md-6" id="newPollingStationDiv" style="{{ old('pstation_code') == 'not_in_list' ? '' : 'display:none;' }}">
            <label for="new_polling_station" class="form-label">Enter New Polling Station Name</label>
            <input type="text" class="form-control @error('new_polling_station') is-invalid @enderror"
                   name="new_polling_station" id="new_polling_station" value="{{ old('new_polling_station') }}"
                   placeholder="Enter New Polling Station">
            @error('new_polling_station')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Gender --}}
        <div class="col-md-4">
            <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
            <select class="js-example-basic-single form-control @error('gender') is-invalid @enderror"
                    name="gender" required>
                <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
                <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Male</option>
                <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Female</option>
            </select>
            @error('gender')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Super Agent --}}
        <div class="col-md-4">
            <label for="super_agent" class="form-label">Super Agent <span class="text-danger">*</span></label>
            <select class="js-example-basic-single form-control @error('super_agent') is-invalid @enderror"
                    name="super_agent" id="super_agent" required>
                <option value="" disabled {{ old('super_agent') ? '' : 'selected' }}>Select Super Agent</option>
                <option value="not_in_list" {{ old('super_agent') == 'not_in_list' ? 'selected' : '' }}>-- {Not in list} --</option>
                @foreach($super_agents as $super_agent)
                    <option value="{{ $super_agent->id }}" {{ old('super_agent') == $super_agent->id ? 'selected' : '' }}>
                        {{ $super_agent->first_name }}
                    </option>
                @endforeach
            </select>
            @error('super_agent')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Super Agent Info --}}
        <div id="superAgentInfo"
             style="{{ old('super_agent') == 'not_in_list' ? '' : 'display:none;' }}"
             class="row g-3 mt-2 border p-3 rounded bg-light">
            <h5 class="text-muted">Super Agent Details</h5>

            <div class="col-md-4">
                <label class="form-label">Name</label>
                <input type="text" id="sa_name" name="sa_name"
                       class="form-control @error('sa_name') is-invalid @enderror"
                       value="{{ old('sa_name') }}" {{ old('super_agent') == 'not_in_list' ? '' : 'readonly' }}>
                @error('sa_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Phone</label>
                <input type="text" id="sa_phone" name="sa_phone"
                       class="form-control @error('sa_phone') is-invalid @enderror"
                       value="{{ old('sa_phone') }}" {{ old('super_agent') == 'not_in_list' ? '' : 'readonly' }}>
                @error('sa_phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">ID Number</label>
                <input type="text" id="sa_id" name="sa_id"
                       class="form-control @error('sa_id') is-invalid @enderror"
                       value="{{ old('sa_id') }}" {{ old('super_agent') == 'not_in_list' ? '' : 'readonly' }}>
                @error('sa_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Age</label>
                <input type="text" id="sa_age" name="sa_age"
                       class="form-control @error('sa_age') is-invalid @enderror"
                       value="{{ old('sa_age') }}" {{ old('super_agent') == 'not_in_list' ? '' : 'readonly' }}>
                @error('sa_age')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="sa_subcounty" class="form-label">Sub County <span class="text-danger">*</span></label>
                <select class="js-example-basic-single form-control @error('sa_subcounty') is-invalid @enderror"
                        name="sa_subcounty" id="sa_subcounty" required>
                    <option value="" disabled {{ old('sa_subcounty') ? '' : 'selected' }}>Select Sub County</option>
                    @foreach($subCounties as $subCounty)
                        <option value="{{ $subCounty->id }}" {{ old('sa_subcounty') == $subCounty->id ? 'selected' : '' }}>
                            {{ $subCounty->name }}
                        </option>
                    @endforeach
                </select>
                @error('sa_subcounty')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="sa_ward" class="form-label">Ward <span class="text-danger">*</span></label>
                <select class="js-example-basic-single form-control @error('sa_ward') is-invalid @enderror"
                        name="sa_ward" id="sa_ward" required>
                    <option value="" disabled {{ old('sa_ward') ? '' : 'selected' }}>Select Ward</option>
                </select>
                @error('sa_ward')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="sa_gender" class="form-label">SA - Gender <span class="text-danger">*</span></label>
                <select class="js-example-basic-single form-control @error('sa_gender') is-invalid @enderror"
                        name="sa_gender" required>
                    <option value="" disabled {{ old('sa_gender') ? '' : 'selected' }}>Select Gender</option>
                    <option value="M" {{ old('sa_gender') == 'M' ? 'selected' : '' }}>Male</option>
                    <option value="F" {{ old('sa_gender') == 'F' ? 'selected' : '' }}>Female</option>
                </select>
                @error('sa_gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

    </div>

    <div class="mt-4">
        <button class="btn btn-success w-25" type="submit">Create</button>
    </div>
</form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.js-example-basic-single').select2();


        // Delegated handler (safer if selects are replaced dynamically)
            $(document).on('change', '#sub_county_id', function(e) {
                console.log('EVENT: #sub_county_id change fired', { event: e });
                var subCountyId = $(this).val();
                console.log('Sub County ID value:', subCountyId);

                // defensive: ensure ward select exists
                if (!$('#ward_id').length) {
                    console.error('ERROR: #ward_id element not found in DOM. Aborting append.');
                    return;
                }

                if (!subCountyId) {
                    console.log('No subCountyId selected. Resetting ward dropdown...');
                    try {
                        $('#ward_id')
                            .empty()
                            .append('<option value="" selected disabled>Select Ward</option>');
                        if (typeof $.fn.select2 !== 'undefined') {
                            $('#ward_id').trigger('change.select2');
                        }
                        console.log('Ward dropdown reset complete');
                    } catch (err) {
                        console.error('Error resetting ward dropdown:', err);
                    }
                    return;
                }

                var url = '/get-wards/' + subCountyId;
                console.log('Making AJAX GET request to:', url);

                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        console.log('AJAX beforeSend - request starting for subCountyId:', subCountyId);
                    },
                    success: function(data) {
                        console.log('AJAX success callback entered. Raw data:', data);

                        try {
                            // support both plain array or { wards: [...] } wrapper
                            var wards = Array.isArray(data) ? data : (data && data.wards ? data.wards : null);

                            if (!Array.isArray(wards)) {
                                console.warn('Warning: unexpected data shape for wards. Trying to handle generically.', data);
                                // attempt to extract first array-like prop
                                for (var k in data) {
                                    if (Array.isArray(data[k])) {
                                        wards = data[k];
                                        console.log('Fallback: extracted wards from data.' + k);
                                        break;
                                    }
                                }
                            }

                            if (!Array.isArray(wards)) {
                                console.error('ERROR: Could not parse wards array from response. Response:', data);
                                return;
                            }

                            console.log('Parsed wards array count:', wards.length);

                            // Clear & append options
                            $('#ward_id').empty().append('<option value="" selected disabled>Select Ward</option>');

                            $.each(wards, function(index, ward) {
                                try {
                                    // defensive: ensure ward has id and name keys
                                    var id = ward.id !== undefined ? ward.id : ward.value || null;
                                    var name = ward.name !== undefined ? ward.name : ward.text || JSON.stringify(ward);
                                    if (id === null) {
                                        console.warn('Skipping ward with missing id:', ward);
                                        return; // continue
                                    }
                                    $('#ward_id').append('<option value="' + id + '">' + name + '</option>');
                                    console.log('Appended ward option:', { index: index, id: id, name: name });
                                } catch (innerErr) {
                                    console.error('Error appending single ward option at index', index, innerErr);
                                }
                            });

                            // If select2 is present, re-init or trigger change so UI reflects new options
                            if (typeof $.fn.select2 !== 'undefined') {
                                try {
                                    // destroy then re-init for a clean refresh (handles weird select2 UI caching)
                                    if ($('#ward_id').hasClass('select2-hidden-accessible')) {
                                        $('#ward_id').select2('destroy');
                                        console.log('Select2 destroyed on #ward_id for re-init');
                                    }
                                    $('#ward_id').select2();
                                    console.log('Select2 re-initialized on #ward_id');
                                } catch (s2err) {
                                    console.warn('select2 re-init failed, trying trigger change; error:', s2err);
                                    try { $('#ward_id').trigger('change.select2'); } catch (tce) { console.error('trigger change.select2 also failed', tce); }
                                }
                            } else {
                                // fallback for non-select2: force a change event
                                $('#ward_id').trigger('change');
                                console.log('Triggered standard change on #ward_id (select2 not present)');
                            }

                            // log final DOM state for ward options
                            console.log('Final #ward_id HTML after append:', $('#ward_id').prop('outerHTML'));

                        } catch (procErr) {
                            console.error('Exception in success handler while processing wards:', procErr, { data: data });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX request failed!', {
                            status: status,
                            error: error,
                            responseText: xhr && xhr.responseText ? xhr.responseText : null,
                            statusCode: xhr && xhr.status ? xhr.status : null
                        });
                    },
                    complete: function() {
                        console.log('AJAX request completed for subCountyId:', subCountyId);
                    }
                });
            });


    // --- Polling Station {Not in list} ---
    $('#pstation_code').on('change', function() {
        if ($(this).val() === 'not_in_list') {
            $('#newPollingStationDiv').show();
            $('#new_polling_station').prop('required', true);
        } else {
            $('#newPollingStationDiv').hide();
            $('#new_polling_station').prop('required', false).val('');
        }
    });

    // --- Super Agent Handling ---
    $('#super_agent').on('change', function() {
        let value = $(this).val();

        if (value === 'not_in_list') {
            // show and make editable
            $('#superAgentInfo').show();
            $('#superAgentInfo input').val('').prop('readonly', false);
        } else if (value) {
            // fetch super agent info from backend
            $('#superAgentInfo').show();
            $.ajax({
                url: '/get-super-agent/' + value,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#superAgentInfo').show();
                    $('#sa_name').val(data.first_name);
                    $('#sa_phone').val(data.phone_no);
                    $('#sa_id').val(data.id_no);
                    $('#sa_age').val(data.age);
                    $('#sa_subcounty').val(data.sub_county_name);
                    $('#sa_ward').val(data.ward_name);
                    $('#sa_polling_station').val(data.polling_station_name);
                    $('#superAgentInfo input').prop('readonly', true);
                },
                error: function(err) {
                    console.error('Error fetching super agent info:', err);
                }
            });
        } else {
            $('#superAgentInfo').hide();
        }
    });
    
            // Delegated handler (safer if selects are replaced dynamically)
            $(document).on('change', '#sa_subcounty', function(e) {
                console.log('EVENT: #sa_subcounty change fired', { event: e });
                var saSubCountyId = $(this).val();
                console.log('Sub County ID value:', saSubCountyId);

                // defensive: ensure ward select exists
                if (!$('#sa_ward').length) {
                    console.error('ERROR: #sa_ward element not found in DOM. Aborting append.');
                    return;
                }

                if (!saSubCountyId) {
                    console.log('No saSubCountyId selected. Resetting ward dropdown...');
                    try {
                        $('#sa_ward')
                            .empty()
                            .append('<option value="" selected disabled>Select Ward</option>');
                        if (typeof $.fn.select2 !== 'undefined') {
                            $('#sa_ward').trigger('change.select2');
                        }
                        console.log('Ward dropdown reset complete');
                    } catch (err) {
                        console.error('Error resetting ward dropdown:', err);
                    }
                    return;
                }

                var url = '/get-wards/' + saSubCountyId;
                console.log('Making AJAX GET request to:', url);

                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        console.log('AJAX beforeSend - request starting for saSubCountyId:', saSubCountyId);
                    },
                    success: function(data) {
                        console.log('AJAX success callback entered. Raw data:', data);

                        try {
                            // support both plain array or { wards: [...] } wrapper
                            var saWards = Array.isArray(data) ? data : (data && data.saWards ? data.saWards : null);

                            if (!Array.isArray(saWards)) {
                                console.warn('Warning: unexpected data shape for saWards. Trying to handle generically.', data);
                                // attempt to extract first array-like prop
                                for (var k in data) {
                                    if (Array.isArray(data[k])) {
                                        saWards = data[k];
                                        console.log('Fallback: extracted saWards from data.' + k);
                                        break;
                                    }
                                }
                            }

                            if (!Array.isArray(saWards)) {
                                console.error('ERROR: Could not parse saWards array from response. Response:', data);
                                return;
                            }

                            console.log('Parsed saWards array count:', saWards.length);

                            // Clear & append options
                            $('#sa_ward').empty().append('<option value="" selected disabled>Select Ward</option>');

                            $.each(saWards, function(index, ward) {
                                try {
                                    // defensive: ensure ward has id and name keys
                                    var id = ward.id !== undefined ? ward.id : ward.value || null;
                                    var name = ward.name !== undefined ? ward.name : ward.text || JSON.stringify(ward);
                                    if (id === null) {
                                        console.warn('Skipping ward with missing id:', ward);
                                        return; // continue
                                    }
                                    $('#sa_ward').append('<option value="' + id + '">' + name + '</option>');
                                    console.log('Appended ward option:', { index: index, id: id, name: name });
                                } catch (innerErr) {
                                    console.error('Error appending single ward option at index', index, innerErr);
                                }
                            });

                            // If select2 is present, re-init or trigger change so UI reflects new options
                            if (typeof $.fn.select2 !== 'undefined') {
                                try {
                                    // destroy then re-init for a clean refresh (handles weird select2 UI caching)
                                    if ($('#sa_ward').hasClass('select2-hidden-accessible')) {
                                        $('#sa_ward').select2('destroy');
                                        console.log('Select2 destroyed on #sa_ward for re-init');
                                    }
                                    $('#sa_ward').select2();
                                    console.log('Select2 re-initialized on #sa_ward');
                                } catch (s2err) {
                                    console.warn('select2 re-init failed, trying trigger change; error:', s2err);
                                    try { $('#sa_ward').trigger('change.select2'); } catch (tce) { console.error('trigger change.select2 also failed', tce); }
                                }
                            } else {
                                // fallback for non-select2: force a change event
                                $('#sa_ward').trigger('change');
                                console.log('Triggered standard change on #sa_ward (select2 not present)');
                            }

                            // log final DOM state for ward options
                            console.log('Final #sa_ward HTML after append:', $('#sa_ward').prop('outerHTML'));

                        } catch (procErr) {
                            console.error('Exception in success handler while processing saWards:', procErr, { data: data });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX request failed!', {
                            status: status,
                            error: error,
                            responseText: xhr && xhr.responseText ? xhr.responseText : null,
                            statusCode: xhr && xhr.status ? xhr.status : null
                        });
                    },
                    complete: function() {
                        console.log('AJAX request completed for saSubCountyId:', saSubCountyId);
                    }
                });
            });
});
</script>
@endsection
