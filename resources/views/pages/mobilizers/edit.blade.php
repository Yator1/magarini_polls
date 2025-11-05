{{-- resources/views/pages/mobilizers/edit.blade.php --}}
@extends('layouts.master')
@section('title') Edit Mobilizer @endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Edit Mobilizer</h4>
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
            </div>

            <div class="card-body">
                <div class="live-preview">
                    <form class="needs-validation" novalidate method="POST" action="{{ route('mobilizers.update', $mobilizer->id) }}">
                        @csrf
                        @method('PUT')

                        <br>
                        <div class="signin-other-title text-center">
                            <h5 class="fs-13 mb-4 title text-muted">Update Mobilizer</h5>
                        </div>
                        <br>

                        <div class="row g-3 needs-validation">

                            <div class="col-md-4">
                                <label for="first_name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ $mobilizer->first_name ?? old('first_name') }}" required>
                                @error('first_name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            {{-- <div class="col-md-4">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control @error('middle_name') is-invalid @enderror" name="middle_name" value="{{ $mobilizer->middle_name ?? old('middle_name') }}">
                                @error('middle_name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div> --}}

                            {{-- <div class="col-md-4">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ $mobilizer->last_name ?? old('last_name') }}" >
                                @error('last_name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div> --}}
                            <div class="col-md-4">
                                <label for="age" class="form-label">Age</label>
                                <input type="text" class="form-control @error('age') is-invalid @enderror" name="age" value="{{ $mobilizer->age ?? old('age') }}" >
                                @error('age')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="d_o_b" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control @error('d_o_b') is-invalid @enderror" name="d_o_b" value="{{ $mobilizer->d_o_b ?? old('d_o_b') }}">
                                @error('d_o_b')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="id_no" class="form-label">ID Number <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('id_no') is-invalid @enderror" name="id_no" value="{{ $mobilizer->id_no ?? old('id_no') }}" >
                                @error('id_no')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="phone_no" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('phone_no') is-invalid @enderror" name="phone_no" value="{{ $mobilizer->phone_no ?? old('phone_no') }}" required>
                                @error('phone_no')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-control @error('role_id') is-invalid @enderror" name="role_id" required>
                                    <option value="" disabled>Select Role</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $mobilizer->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <input type="hidden" name="county_id" value="14">

                            <div class="col-md-4">
                                <label for="sub_county_id" class="form-label">Sub County <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-control @error('sub_county_id') is-invalid @enderror" name="sub_county_id" id="sub_county_id" required>
                                    <option value="" disabled>Select Sub County</option>
                                    @foreach($subCounties as $subCounty)
                                    <option value="{{ $subCounty->id }}" {{ $mobilizer->sub_county_id == $subCounty->id ? 'selected' : '' }}>{{ $subCounty->name }}</option>
                                    @endforeach
                                </select>
                                @error('sub_county_id')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="ward_id" class="form-label">Ward <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-control @error('ward_id') is-invalid @enderror" name="ward_id" id="ward_id" >
                                    <option value="" disabled>Select Ward</option>
                                    <!-- Populated via AJAX or pre-loaded -->
                                </select>
                                @error('ward_id')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="market_id" class="form-label">Market</label>
                                <select class="js-example-basic-single form-control @error('market_id') is-invalid @enderror" name="market_id" id="market_id">
                                    <option value="" disabled>Select Market (Optional)</option>
                                    @foreach($markets as $market)
                                    <option value="{{ $market->id }}" {{ $mobilizer->market_id == $market->id ? 'selected' : '' }}>{{ $market->name }}</option>
                                    @endforeach
                                </select>
                                @error('market_id')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="pstation_code" class="form-label">Polling Station Code <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-control @error('pstation_code') is-invalid @enderror" name="pstation_code" >
                                    <option value="" disabled>Select Polling Station</option>
                                    @foreach($pollingStations as $ps)
                                    <option value="{{ $ps->id }}" {{ $mobilizer->pstation_code == $ps->id ? 'selected' : '' }}>{{ $ps->name }}</option>
                                    @endforeach
                                </select>
                                @error('pstation_code')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-control @error('gender') is-invalid @enderror" name="gender" required>
                                    <option value="" disabled>Select Gender</option>
                                    <option value="M" {{ $mobilizer->gender == 'M' ? 'selected' : '' }}>Male</option>
                                    <option value="F" {{ $mobilizer->gender == 'F' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="user_type" class="form-label">User Type <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-control @error('user_type') is-invalid @enderror" name="user_type" >
                                    <option value="Chair" {{ $mobilizer->user_type == 'Chair' ? 'selected' : '' }}>Chair</option>
                                    <option value="Mobilizer" {{ $mobilizer->user_type == 'Mobilizer' ? 'selected' : '' }}>Mobilizer</option>
                                </select>
                                @error('user_type')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                              <div class="col-md-4">
                                <label for="super_agent" class="form-label">Super Agents <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-control @error('super_agent') is-invalid @enderror" name="super_agent" >
                                    <option value="" disabled>Select Super Agents</option>
                                    @foreach($super_agents as $super_agent)
                                    <option value="{{ $super_agent->id }}" {{ $mobilizer->super_agent == $super_agent->id ? 'selected' : '' }}>{{ $super_agent->first_name }}</option>
                                    @endforeach
                                </select>
                                @error('super_agent')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-4">
                            <button class="btn btn-success w-25" type="submit">Update</button>
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

        // AJAX for subcounty change: fetch wards
        $('#sub_county_id').change(function() {
            var subCountyId = $(this).val();
            if (subCountyId) {
                $.ajax({
                    url: '/get-wards/' + subCountyId,
                    method: 'GET',
                    success: function(data) {
                        $('#ward_id').empty().append('<option value="" selected disabled>Select Ward</option>');
                        $.each(data, function(key, ward) {
                            $('#ward_id').append('<option value="' + ward.id + '">' + ward.name + '</option>');
                        });
                    }
                });
            } else {
                $('#ward_id').empty().append('<option value="" selected disabled>Select Ward</option>');
            }
        });

        // AJAX for market change: fetch subcounty and ward
        $('#market_id').change(function() {
            var marketId = $(this).val();
            if (marketId) {
                $.ajax({
                    url: '/get-market-details/' + marketId,
                    method: 'GET',
                    success: function(data) {
                        $('#sub_county_id').val(data.sub_county_id).trigger('change');
                        // Wait for wards to load, then set ward
                        setTimeout(function() {
                            $('#ward_id').val(data.ward_id);
                        }, 500); // Small delay to allow wards to load
                    }
                });
            }
        });

        // Trigger initial load for subcounty if pre-selected
        $('#sub_county_id').trigger('change');
    });
</script>
@endsection