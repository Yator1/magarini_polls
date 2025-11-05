@extends('layouts.master')
@section('title') @lang('translation.datatables') @endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<!--datatable responsive css-->
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />

<style>
    .pagination-container {
    text-align: center;
    margin-top: 20px;
}

.pagination-container a {
    display: inline-block;
    margin: 0 5px;
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 3px;
    text-decoration: none;
    color: #007bff; /* Default link color */
}

.pagination-container a:hover {
    background-color: #f8f9fa; /* Hover background color */
}

.pagination-container .active {
    background-color: #007bff; /* Active page background color */
    color: #fff; /* Active page text color */
}

.pagination-container .disabled {
    opacity: 0.5; /* Disabled link opacity */
    pointer-events: none; /* Disabled link pointer events */
}

</style>
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') Tables @endslot
@slot('title')Staff @endslot
@endcomponent



<div class="row">
    <div class="col-lg-12">
        {{-- <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Filter</h5>
            </div>
            <div class="card-body">

                
                <form class="needs-validation" validate id="all2" method="POST" action="{{route('users.all2')}}"> 
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="county" class="form-label">Counties</label>
                            <select class="form-select js-example-basic-single" name="county" id="county" aria-label="Default select example" @required(true)>
                                <option value="" disabled selected>Select County</option> <!-- Disable and make the placeholder non-selectable -->
                                @foreach($Counties as $county)
                                    <option value="{{ $county }}" {{ old('county', $oldCounty) == $county ? 'selected' : '' }}>
                                        {{ $county }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('county'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('county') }}
                                </div>
                            @endif

                            <div class="invalid-feedback">
                                Please select a county.
                            </div>
                        </div>
                        

                        <div class="col-lg-6">
                            <label for="FacilityLevel" class="form-label">Facility Levels</label>
                            <select class="form-select js-example-basic-single" name="FacilityLevel" id="FacilityLevel" aria-label="Default select example" >
                                <option value="">Select County</option>
                                @foreach($FacilityLevels as $FacilityLevel)
                                    <option value="{{ $FacilityLevel }}" {{ old('FacilityLevel', $oldFacilityLevel) == $FacilityLevel ? 'selected' : '' }}>
                                        {{ $FacilityLevel }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please Select FacilityLevel.
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <label for="FacilityType" class="form-label">FacilityTypes</label>
                            <select class="form-select js-example-basic-single" name="FacilityType" id="FacilityType" aria-label="Default select example" >
                                <option value="">Select FacilityType</option>
                                @foreach($FacilityTypes as $FacilityType)
                                    <option value="{{ $FacilityType }}" {{ old('FacilityType', $oldFacilityType) == $FacilityType ? 'selected' : '' }}>
                                        {{ $FacilityType }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please Select FacilityType.
                            </div>
                        </div>


                        <div class="col-lg-6">
                            <label for="FacilityAgent" class="form-label">FacilityAgents</label>
                            <select class="form-select js-example-basic-single" name="FacilityAgent" id="FacilityAgent" aria-label="Default select example" >
                                <option value="">Select FacilityAgent</option>
                                @foreach($FacilityAgents as $FacilityAgent)
                                    <option value="{{ $FacilityAgent }}" {{ old('FacilityAgent', $oldFacilityAgent) == $FacilityAgent ? 'selected' : '' }}>
                                        {{ $FacilityAgent }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please Select FacilityAgent.
                            </div>
                        </div>
                        <div class="mb-3 mt-3 col-lg-12">
                            <div class="modal-footer">
                               
                                <button type="submit" class="btn btn-success"><i class="ri-save-line align-bottom me-1"></i>
                                    Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div> --}}

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">All Contacts</h5>
            </div>
            <div class="card-body">
                {{-- <table id="buttons-datatables" class="display dt-responsive align-middle table-hover table table-bordered" style="width:100%"> --}}
         
                {{-- <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%"> --}}
                    <table id="buttons-datatables" class="display dt-responsive align-middle table-hover table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone No</th>
                            <th>Status</th>
                            <th>Agent</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($users as $user)
                            @if ($user)
                            <tr>
                                {{-- <td>{{$loop->iteration .'/ '.$user->id}}</td> --}}
                                <td>{{$loop->iteration }}</td>
                                <td>
                                    {{$user->first_name .' '.$user->middle_name ?? ''.' '.$user->last_name ?? ''}}
                                </td>
                                <td>
                                    <div class="flex-grow-1">
                                        @if($user->phone_no)
                                        <p class="text-muted">Phone 1 No: {{$user->phone_no ?? ''}}</p>
                                        @endif
                                        @if($user->phone_no_2)
                                        <p class="text-muted">Phone 1 No: {{$user->phone_no_2 ?? ''}}</p>
                                        @endif
                                        @if($user->phone_no_3)
                                        <p class="text-muted">Phone 1 No: {{$user->phone_no_3 ?? ''}}</p>
                                        @endif
                                        @if($user->phone_no_4)
                                        <p class="text-muted">Phone 1 No: {{$user->phone_no_4 ?? ''}}</p>
                                        @endif
                                        @if($user->email)
                                        <p class="text-muted">Email: {{$user->email ?? ''}}</p>
                                        @endif
                                        @if($user->address)
                                        <p class="text-muted">Address: {{$user->address ?? ''}}</p>
                                        @endif
                                    </div> 
                                </td>
                                <td>
                                    @if($user->call_status == 0)
                                    <p class="badge badge-soft-info">Not Contacted</p>
                                    @elseif($user->call_status == 1)
                                    <p class="badge badge-soft-success">Picked</p>
                                    @elseif($user->call_status == 2)
                                    <p class="badge badge-soft-warning">Declined/ Unreachable</p>
                                    @elseif($user->call_status == 3)
                                    <p class="badge badge-soft-danger">Missed Call</p>
                                    @elseif($user->call_status == 7)
                                    <p class="badge badge-soft-danger">Call Back</p>
                                    @elseif($user->call_status == 8)
                                    <p class="badge badge-soft-danger">Invalid Phone Number</p>
                                    @else
                                    <p class="badge badge-soft-primary">Unknown: {{$user->call_status}}</p>
                                    @endif

                                </td>
                                    <td>
                                        @if($user->CalledBy)
                                            {{$user->CalledBy->first_name ?? ''}}
                                        @endif
                                        @can('is_admin')
                                        <form class=" w-100" validate id="" method="POST" action="{{route('users.assign-user')}}"> 
                                            @csrf
                                            <input type="hidden" class="form-control" name="participant_id" value="{{$user->id}}"  hidden>
                                                                    <input type="hidden" class="form-control" name="poll_id" value="{{$poll->id}}"  hidden>
                                                                    
                                                            <div class="row g-3">
                                                                <div class="col-md-12">
                                                                    <div class="row g-3">
                                                                        <div class="col-md-6">
                                                                            <div class="mb-3">
                                                                                {{-- <label for="updateUserAgent" class="form-label">Select and Assign Agent</label> --}}
                                                                                <select class="js-example-basic-single" name="updateUserAgent" id="updateUserAgent" aria-label="Default select example" >
                                                                                    <option value="">Select Agent</option>
                                                                                    @foreach($agents as $agent)
                                                                                            <option value="{{ $agent->id }}">{{ $agent->first_name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                <div class="invalid-feedback">
                                                                                    Please Select Decision.
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row g-3">
                                                                        <div class="col-md-6">
                                                                            <button type="submit" class="btn btn-info btn-md w-100">
                                                                                Assign</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                        </form>
                                        @endcan
                                    </td>
                                    <td>
                                        @if($user->called_by == auth()->user()->id || Gate::allows('is_admin'))
                                            <a href="{{ route('participant-answers.addPoll2', $user->id) }}" class="btn btn-secondary btn-sm dropdown" type="button">
                                                <i class="ri-eye-fill align-bottom me-2"></i> Edit
                                            </a>
                                        @endif
                                    </td>
                                    
                            </tr>
                            @endif
                            @endforeach
                    </tbody>
                </table> 
            </div>
        </div>
    </div>
</div>


@endsection

@section('script')



<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<!--select2 cdn-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<!--select2 cdn-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>

<script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>

<script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection
