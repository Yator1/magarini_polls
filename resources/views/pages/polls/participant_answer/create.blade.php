@extends('layouts.master')
@section('title')
    @lang('translation.deals')
@endsection
@section('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<!--datatable responsive css-->
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            CRM
        @endslot
        @slot('title')
            MAKE CALL
        @endslot
    @endcomponent
    {{-- <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="search-box">
                        <input type="text" class="form-control search" placeholder="Search for deals...">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <!--end col-->
                <div class="col-md-auto ms-auto">
                    <div class="d-flex hastck gap-2 flex-wrap">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted">Sort by: </span>
                            <select class="form-control mb-0" data-choices data-choices-search-false
                                id="choices-single-default">
                                <option value="Owner">Owner</option>
                                <option value="Company">Company</option>
                                <option value="Date">Date</option>
                            </select>
                        </div>
                        <button data-bs-toggle="modal" data-bs-target="#adddeals" class="btn btn-success"><i
                                class="ri-add-fill align-bottom me-1"></i> Add
                            Deals</button>
                        <div class="dropdown">
                            <button class="btn btn-soft-info btn-icon fs-14" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-settings-4-line"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="#">Copy</a></li>
                                <li><a class="dropdown-item" href="#">Move to pipline</a></li>
                                <li><a class="dropdown-item" href="#">Add to exceptions</a></li>
                                <li><a class="dropdown-item" href="#">Switch to common form view</a>
                                </li>
                                <li><a class="dropdown-item" href="#">Reset form view to default</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
    </div>
    <!--end card--> --}}



        <!--end card-->
{{-- 
        <button data-bs-toggle="modal" data-bs-target="#adddeals" class="btn btn-success"><i
            class="ri-add-fill align-bottom me-1"></i> Add
        Deals</button> --}}
    <div class="card">
        <div class="card-body">
            <div class="row row-cols-xxl-3 row-cols-lg-3 row-cols-md-3 row-cols-1">
                <div class="col">
                    <div class="card">
                        <a href="{{route('users.declined')}}" class="card-body bg-soft-danger"  role="button">
                            <h5 class="card-title text-uppercase fw-semibold mb-1 fs-15">Calls Declined /Unreachable</h5>
                            <p class="text-muted mb-0"><span class="fw-medium">{{auth()->user()->calls()->where('call_status', 2)->count();}}</span></p>
                        </a>
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->

                <div class="col">
                    <div class="card">
                        <a href="{{route('users.notreached')}}"  class="card-body bg-soft-warning"  role="button">
                            <h5 class="card-title text-uppercase fw-semibold mb-1 fs-15">Missed Calls</h5>
                            <p class="text-muted mb-0"><span class="fw-medium">{{auth()->user()->calls()->where('call_status', 3)->count();}}</span></p>
                        </a>
                    </div>
                </div>
              
                <div class="col">
                    <div class="card">
                        <a href="{{route('users.notreached')}}"  class="card-body bg-soft-warning"  role="button">
                            <h5 class="card-title text-uppercase fw-semibold mb-1 fs-15">Call Back</h5>
                            <p class="text-muted mb-0"><span class="fw-medium">{{auth()->user()->calls()->where('call_status', 7)->count();}}</span></p>
                        </a>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <a href="{{route('users.notreached')}}"  class="card-body bg-soft-warning"  role="button">
                            <h5 class="card-title text-uppercase fw-semibold mb-1 fs-15">Invalid/Wrong/No Phone Number</h5>
                            <p class="text-muted mb-0"><span class="fw-medium">{{auth()->user()->calls()->where('call_status', 8)->count();}}</span></p>
                        </a>
                    </div>
                </div>
                <!--end col-->

                {{-- <div class="col">
                    <div class="card">
                        <a class="card-body bg-soft-info" data-bs-toggle="collapse" href="#meetingArranged" role="button"
                            aria-expanded="false" aria-controls="meetingArranged">
                            <h5 class="card-title text-uppercase fw-semibold mb-1 fs-15">Calls Picked But Did Not Participate</h5>
                            <p class="text-muted mb-0"><span class="fw-medium">{{auth()->user()->calls()->where('call_status', 1)->count();}}</span></p>
                        </a>
                    </div>
                </div> --}}
                <!--end col-->

                
                <div class="col">
                    <div class="card">
                        <a href="{{route('users.picked')}}"  class="card-body bg-soft-success" role="button">
                            <h5 class="card-title text-uppercase fw-semibold mb-1 fs-15">Calls Picked</h5>
                            <p class="text-muted mb-0"><span class="fw-medium">{{auth()->user()->calls()->where('call_status', 1)->count();}}</span></p>
                            {{-- <p class="text-muted mb-0"><span class="fw-medium">{{auth()->user()->calls()->where('call_status', 1)->whereHas('participantAnswers')->count();}}</span></p> --}}
                        </a>
                    </div>
                </div>
                <!--end col-->
            </div>
        </div>
    </div>
    <!--end row-->

    <div class="card">
        <div class=" border-top border-top-dashed" >
            <div class="card-body">
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

                <h3 class=""><small class="">
                    NAME: </small>{{$user->first_name .' '.$user->middle_name ?? ''.' '.$user->last_name ?? '' }}
                </h3>
                {{-- <p class=""><small class="">
                    FacilityAgent: </small>{{$user->FacilityAgent }}
                </p>
                <p class=""><small class="">
                    FacilityType: </small>{{$user->FacilityType }}
                </p> --}}
                {{-- <p class=""><small class="">
                    FacilityLevel: </small>{{$user->FacilityLevel }}
                </p> --}}
                <p class=""><small class="">
                    County: </small>{{$user->CountyName->county_name ?? 'Not Update '.$user->County     }}
                </p>
                <p class=""><small class="">
                    Sub County: </small>{{$user->SubCounty->name ?? 'Not Update'     }}
                </p>
                <p class=""><small class="">
                    Ward: </small>{{$user->Ward->name ?? 'Not Update'     }}
                </p>
                {{-- <p class=""><small class="">
                    CAW: </small>{{$user->caw ?? 'Not Update'     }}
                </p> --}}
                <p class=""><small class="">
                    Polling Station: </small>{{$user->pollingstation ?? 'N/A'     }}
                </p>
                <p class=""><small class="">
                    Mobilizer Group: </small>{{$user->role->name ?? 'N/A'     }}
                </p>
                <p class=""><small class="">
                    Called By: </small>{{$user->UpdatedBy->first_name ?? 'Not Yet Called'     }}
                </p>
                {{-- @if($user->Facility)
                
                 <h3 class=""><small class="">
                        NAME: </small>{{$user->Facility->facility_name }}
                </h3>
                @endif --}}
                <hr>
                
                <ul class="list-unstyled vstack gap-2 mb-0">
                    <li>
                        <div class="d-flex">
                            {{-- <div class="flex-shrink-0 avatar-xxs text-muted">
                                <i class="ri-question-answer-line"></i>
                            </div> --}}
                            <div class="flex-grow-1">
                                <h3 class="mb-0">Contacts</h3>
                                <br>
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
                        </div>
                    </li>
                </ul>

                @if($user->called_by != NULL)
                <h2>
                    Assigned Agent: <b>{{ $user->CalledBy->first_name}}</b>
                </h2>
                
                @endif
                @can('is_admin')
                <form class=" w-100" validate id="" method="POST" action="{{route('users.assign-user')}}"> 
                    @csrf
                    <input type="hidden" class="form-control" name="participant_id" value="{{$user->id}}" required hidden>
                                            <input type="hidden" class="form-control" name="poll_id" value="{{$poll->id}}" required hidden>
                                            
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="updateUserAgent" class="form-label">Select and Assign Agent</label>
                                                        <select class="js-example-basic-single" name="updateUserAgent" id="updateUserAgent" aria-label="Default select example" required>
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
                                                    <button type="submit" class="btn btn-info btn-md w-100"><i
                                                            class="ri-phone-line  align-bottom me-1"></i>
                                                        Assign</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                </form>
                @endcan

                <hr>
                <br>
                <h3>
                    Maping Details </b>
                </h3>
               <form class="w-100" validate id="updateParticipantForm" method="POST" action="{{ route('users.update_participant') }}">
                @csrf
                <input type="hidden" class="form-control" name="participant_id" value="{{ $user->id }}" required hidden>
                <input type="hidden" class="form-control" name="poll_id" value="{{ $poll->id }}" required hidden>
                <div class="row">
                    <div class="col-md-4">
                        <label for="id_number" class="form-label">Id Number</label>
                        <input type="text" class="form-control @error('id_number') is-invalid @enderror" name="id_number" value="{{ old('id_number', $user->id_no) }}" id="first_id_number" placeholder="Id Number" oninput="this.value = this.value.toUpperCase();">
                        @error('id_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="invalid-feedback">
                            Please enter Id Number
                        </div>
                        <br>
                    </div>

                    <!-- Gender Selection -->
                    <div class="col-lg-4">
                        <label for="gender" class="form-label">Select Gender</label>
                        <select class="js-example-basic-single form-control @error('gender') is-invalid @enderror" name="gender" id="gender">
                            <option value="">Select</option>
                            <option value="M" {{ $user->gender == 'M' ? 'selected' : '' }}>Male</option>
                            <option value="F" {{ $user->gender == 'F' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="invalid-feedback">
                            Please select a gender.
                        </div>
                        <br>
                    </div>

                    <!-- County Selection -->
                    <div class="col-lg-4">
                        <label for="county" class="form-label">Select County</label>
                        <select class="js-example-basic-single form-control @error('county') is-invalid @enderror" name="county" id="county">
                            <option value="">Select</option>
                            @foreach($counties as $county)
                                <option value="{{ $county->id }}" {{ $user->county_id == $county->id ? 'selected' : '' }}>{{ $county->county_name }}</option>
                            @endforeach
                        </select>
                        @error('county')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="invalid-feedback">
                            Please select a County.
                        </div>
                        <br>
                    </div>

                    <!-- Sub County Selection -->
                    <div class="col-lg-4">
                        <label for="sub_county" class="form-label">Select Sub County</label>
                        <select class="js-example-basic-single form-control @error('sub_county') is-invalid @enderror" name="sub_county" id="sub_county">
                            <option value="">Select</option>
                            @if($user->sub_county_id)
                                <option value="{{ $user->sub_county_id }}" selected>{{ $user->subCounty->name }}</option>
                            @endif
                        </select>
                        @error('sub_county')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="invalid-feedback">
                            Please select a Sub County.
                        </div>
                        <br>
                    </div>

                        <!-- Ward Selection -->
                        <div class="col-lg-4">
                            <label for="ward" class="form-label">Select Ward</label>
                            <select class="js-example-basic-single form-control @error('ward') is-invalid @enderror" name="ward" id="ward">
                                <option value="">Select</option>
                                @if($user->ward_id)
                                    <option value="{{ $user->ward_id }}" selected>{{ $user->ward->name }}</option>
                                @endif
                            </select>
                            @error('ward')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="invalid-feedback">
                                Please select a Ward.
                            </div>
                            <br>
                        </div>
       
                        <!-- Polling Station Selection -->
                    <div class="col-md-4">
                        <label for="pstation_code" class="form-label">Polling Station Code <span class="text-danger">*</span></label>
                        <select class="js-example-basic-single form-control @error('pstation_code') is-invalid @enderror" name="pstation_code" id="pstation_code">
                            <option value="" disabled>Select Polling Station</option>
                            @foreach($pollingStations as $ps)
                                <option value="{{ $ps->id }}" {{ $user->pstation_code == $ps->id ? 'selected' : '' }}>{{ $ps->name }}</option>
                            @endforeach
                        </select>
                        @error('pstation_code')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                        <div class="invalid-feedback">
                            Please select a Polling Station.
                        </div>
                        <br>
                    </div>

                    <div id="message" style="display: none;"></div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-info btn-md w-100">Update</button>
                        </div>
                    </div>
                </div>
            </form>
                <hr>
                <br>
                <a href="{{route('participant-answers.addPoll2', $user->id)}}" class="btn btn-danger btn-sm dropdown" type="button" >
                    <i class="ri-eye-fill align-bottom me-2 "></i> Refresh Page 
                </a>
                <br>
                <hr>
                

                
            </div>
            {{-- {{$user->participantAnswers}} --}}
            {{-- @if($user->participantAnswers != []) --}}
            {{-- @if(empty($user->participantAnswers)) --}}
            @if($user->participantAnswers->isEmpty())
<div class="card-footer">
    <div class="row g-2">
        <div class="col-12 col-md">
            <form class="w-100" validate id="" method="POST" action="{{route('users.update-call')}}"> 
                @csrf
                <input type="hidden" class="form-control" name="participant_id" value="{{$user->id}}" required hidden>
                <input type="hidden" class="form-control" name="call_status" value="2" required hidden>
                <button type="submit" class="btn btn-danger btn-md w-100">
                    <i class="ri-phone-line align-bottom me-1"></i> Declined/ Uneachable
                </button>
            </form>
        </div>

        <div class="col-12 col-md">
            <form class="w-100" validate id="" method="POST" action="{{route('users.update-call')}}"> 
                @csrf
                <input type="hidden" class="form-control" name="participant_id" value="{{$user->id}}" required hidden>
                <input type="hidden" class="form-control" name="call_status" value="3" required hidden>
                <button type="submit" class="btn btn-info btn-md w-100">
                    <i class="ri-phone-line align-bottom me-1"></i> Missed Calls
                </button>
            </form>
        </div>

        <div class="col-12 col-md">
            <form class="w-100" validate id="" method="POST" action="{{route('users.update-call')}}"> 
                @csrf
                <input type="hidden" class="form-control" name="participant_id" value="{{$user->id}}" required hidden>
                <input type="hidden" class="form-control" name="call_status" value="7" required hidden>
                <button type="submit" class="btn btn-warning btn-md w-100">
                    <i class="ri-phone-line align-bottom me-1"></i> Call Back
                </button>
            </form>
        </div>

        <div class="col-12 col-md">
            <button data-bs-toggle="modal" data-bs-target="#adddeals" class="btn btn-success btn-md w-100">
                <i class="ri-add-fill align-bottom me-1"></i> Picked
            </button>
        </div>

        <div class="col-12 col-md">
            <form class="w-100" validate id="" method="POST" action="{{route('users.update-call')}}"> 
                @csrf
                <input type="hidden" class="form-control" name="participant_id" value="{{$user->id}}" required hidden>
                <input type="hidden" class="form-control" name="call_status" value="8" required hidden>
                <button type="submit" class="btn btn-info btn-md w-100">
                    <i class="ri-phone-line align-bottom me-1"></i> Invalid/Wrong/No Phone No
                </button>
            </form>
        </div>

        <div class="col-12 col-md">
            <a href="{{route('participant-answers.create')}}" class="btn btn-warning btn-md w-100" onclick="reloadPage()">
                <i class="ri-question-answer-line align-bottom me-1"></i> Next
            </a>
            {{-- <button class="btn btn-warning btn-md w-100" onclick="reloadPage()">
                <i class="ri-question-answer-line align-bottom me-1"></i> Next
            </button> --}}
        </div>
    </div>
</div>

            @else
            <div class="card-body">
                <table id="buttons-datatables" class="display dt-responsive align-middle table-hover table table-bordered" style="width:100%">
                    {{-- <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%"> --}}
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone No</th>
                            {{-- <th>FacilityType</th>
                            <th>FacilityLevel</th>
                            <th>LicenceNo</th>
                            <th>County</th> --}}
                            @foreach($poll->questions as $poll_question)
                                <th>{{$poll_question->question}}</th>
                            @endforeach
                            {{-- <th>Follow Up Date</th>
                            <th>Comments</th> --}}
                            <th>Agent</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
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

                            {{-- <td>
                                <div class="flex-grow-1">
                                    @if($user->FacilityType)
                                    <p class="text-muted">{{$user->FacilityType }}</p>
                                    @endif
                                </div> 
                            </td>


                            <td>
                                <div class="flex-grow-1">
                                    @if($user->FacilityLevel)
                                    <p class="text-muted">{{$user->FacilityLevel }}</p>
                                    @endif
                                </div> 
                            </td>


                            <td>
                                <div class="flex-grow-1">
                                    @if($user->LicenceNo)
                                    <p class="text-muted">{{$user->LicenceNo }}</p>
                                    @endif
                                </div> 
                            </td>


                            <td>
                                <div class="flex-grow-1">
                                    @if($user->County)
                                    <p class="text-muted">{{$user->County }}</p>
                                    @endif
                                </div> 
                            </td> --}}
                            
                        
                                @foreach($poll->questions as $poll_question)
                                <td>
                                    @php
                                        $userAnswer = $user->participantAnswers()->where('poll_question_id', $poll_question->id)->first();
                                        
                                        $userAnswer = $user->participantAnswers()->where('poll_question_id', $poll_question->id)->first();
                                            $userAnswerComm = $user->participantAnswers()->where('poll_id', $poll->id)->whereNotNull('comment')->first();
                                            if($userAnswer){
                                                $comment = $userAnswerComm->comment ?? '';
                                                $followUpDate = $userAnswer->followUpDate ?? '';
                                            }
                                    @endphp
                                    
                                        @if($userAnswer)
                                            {{ $userAnswer->QAnswer->answer ??  $userAnswer->answer}}
                                        @else
                                        {{-- No Answer --}}
                                            <form class="needs-validation" validate id="" method="POST" action="{{route('participant-answers.update')}}"> 
                                                @csrf

                                                <input type="hidden" class="form-control" name="participant_id" value="{{$user->id}}" required hidden>
                                                <input type="hidden" class="form-control" name="poll_id" value="{{$poll->id}}" required hidden>
                                                <div class="mb-3">
                                                    <label for="question_{{$loop->iteration}}" class="form-label">Question {{$loop->iteration}}: {{ $poll_question->question }}</label>
                                                    <select class="form-select" name="question_{{ $poll_question->id }}" id="question_{{$loop->iteration}}" aria-label="Default select example" >
                                                        <option value="">Select Decision</option>
                                                        @foreach($poll_answers as $poll_answer)
                                                            @if($poll_answer->poll_question_id == $poll_question->id)
                                                                <option value="{{ $poll_answer->id }}">{{ $poll_answer->answer }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Please Select Decision.
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success"><i class="ri-save-line align-bottom me-1"></i>
                                                        Submit</button>
                                                </div>
                                            </form>
                                        @endif
                                </td>
                                @endforeach 
                                {{-- <td>
                                    {{$followUpDate ?? 'No Follow Up Date'}}
                                </td>
                                <td>
                                    {{$comment ?? 'No Comment'}}
                                </td> --}}
                                <td>
                                    {{$user->CalledBy->first_name }}
                                </td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <br>

                <a href="{{route('participant-answers.create')}}" class="btn btn-warning btn-md w-100" onclick="reloadPage()">
                    <i class="ri-question-answer-line align-bottom me-1"></i> Next Call
                </a>
            </div>
            @endif
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="adddeals" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Submit Poll</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="needs-validation" validate id="" method="POST" action="{{route('participant-answers.store')}}"> 
                    @csrf
                    <div class="modal-body">
                        @foreach($poll_questions as $poll_question)
                        @php
                            // Check if this question has a parent
                            $isChild = $poll_question->parent_id != null;
                            $parentID = $poll_question->parent_id;
                        @endphp
                        
                        <div class="mb-3 parent-question" @if($isChild) style="display:none;" data-parent="{{ $parentID }}" @endif>
                            <label for="question_{{$loop->iteration}}" class="form-label">
                                Question {{$loop->iteration}}: {{ $poll_question->question }} 
                                @if(!$isChild) @endif
                            </label>
                    
                            @if($poll_question->answers->count() > 0)
                                <select class="form-select parent-answer" name="question_{{ $poll_question->id }}" id="question_{{$loop->iteration}}" data-question-id="{{ $poll_question->id }}" aria-label="Default select example" >
                                    <option value="">Select Decision</option>
                                    @foreach($poll_answers as $poll_answer)
                                        @if($poll_answer->poll_question_id == $poll_question->id)
                                            <option value="{{ $poll_answer->id }}" data-answer="{{ strtolower($poll_answer->answer) }}">{{ $poll_answer->answer }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Please Select Decision.
                                </div>
                            @else
                                @if($poll_question->answer_type == "text" )
                                <input type="text" class="form-control 
                                    @error('question_{{ $poll_question->id }}') is-invalid @enderror"
                                    name="question_{{ $poll_question->id }}" 
                                    value="{{ old('question_' . $poll_question->id) }}"
                                    id="question_{{ $poll_question->id }}" 
                                    placeholder="Enter Answer" 
                                    >
                                @else
                                    <input type="number" class="form-control 
                                        @error('question_{{ $poll_question->id }}') is-invalid @enderror"
                                        name="question_{{ $poll_question->id }}" 
                                        value="{{ 0 ?? old('question_' . $poll_question->id) }}"
                                        id="question_{{ $poll_question->id }}" 
                                        placeholder="Enter Answer" 
                                        >
                                @endif
                                @error('question_{{ $poll_question->id }}')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="invalid-feedback">
                                    Please enterAnswer
                                </div>
                            @endif
                        </div>
                    @endforeach
                    

                    

                        <input type="hidden" class="form-control" name="participant_id" value="{{$user->id}}" required hidden>
                        <input type="hidden" class="form-control" name="poll_id" value="{{$poll->id}}" required hidden>

                        {{-- <div class="mb-3">
                            <label for="exampleInputdate" class="form-label">Follow Up Date</label>
                            <input style="z-index: 1001;" type="date" name="followUpDate"  class="form-control" id="exampleInputdate">
                            
                        </div>
                        <div class="mb-3">
                            <label for="cpmments" class="form-label">Comments</label>
                            <textarea name="comment" class="form-control" id="cpmments" rows="3" placeholder="Enter description" ></textarea>
                            <div class="invalid-feedback">
                                Please add a comment.
                            </div>
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" id="close-modal"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success"><i class="ri-save-line align-bottom me-1"></i>
                            Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end modal-->
@endsection

@section('script')
<!--jquery cdn-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<!--select2 cdn-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>
<script src="{{ URL::asset('build/libs/cleave.js/cleave.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/crm-deals.init.js') }}"></script>
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2 for all select elements with js-example-basic-single class
        $('.js-example-basic-single').select2({
            placeholder: function() {
                return $(this).find('option[value=""]').text();
            },
            allowClear: true
        });

        // Specific initialization for pstation_code to enable search for long list
        // $('#pstation_code').select2({
        //     placeholder: "Select Polling Station",
        //     allowClear: true,
        //     minimumInputLength: 1, // Start searching after 1 character
        //     width: '100%',
        //     dropdownCssClass: 'select2-long-list' // Optional: for custom styling
        // });

        // When a county is selected, fetch subcounties
        $('#county').change(function() {
            var countyId = $(this).val();
            console.log("Selected County ID:", countyId);

            if (countyId) {
                $.ajax({
                    url: '/get-subCountyt/' + countyId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log("Subcounties received:", data);
                        $('#sub_county').empty();
                        $('#sub_county').append('<option value="">Select Sub County</option>');
                        $.each(data, function(key, subcounty) {
                            var selected = subcounty.id == {{ $user->sub_county_id ?? 'null' }} ? 'selected' : '';
                            $('#sub_county').append('<option value="' + subcounty.id + '" ' + selected + '>' + subcounty.name + '</option>');
                        });
                        $('#sub_county').trigger('change'); // Trigger change to load wards
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching subcounties:", status, error);
                    }
                });
            } else {
                console.log("No County selected");
                $('#sub_county').empty();
                $('#sub_county').append('<option value="">Select Sub County</option>');
                $('#ward').empty();
                $('#ward').append('<option value="">Select Ward</option>');
            }
        });

        // When a subcounty is selected, fetch wards
        $('#sub_county').change(function() {
            var subCountyId = $(this).val();
            console.log("Selected Subcounty ID:", subCountyId);

            if (subCountyId) {
                $.ajax({
                    url: '/get-wards/' + subCountyId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log("Wards received:", data);
                        $('#ward').empty();
                        $('#ward').append('<option value="">Select Ward</option>');
                        $.each(data, function(key, ward) {
                            var selected = ward.id == {{ $user->ward_id ?? 'null' }} ? 'selected' : '';
                            $('#ward').append('<option value="' + ward.id + '" ' + selected + '>' + ward.name + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching wards:", status, error);
                    }
                });
            } else {
                console.log("No Subcounty selected");
                $('#ward').empty();
                $('#ward').append('<option value="">Select Ward</option>');
            }
        });

        // Trigger county change on page load to populate subcounties and wards
        @if($user->county_id)
            $('#county').trigger('change');
        @endif

        $('#updateParticipantForm').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Ensure Select2 values are included in form submission
            $(this).find('select').each(function() {
                $(this).val($(this).val()); // Update the underlying select value
            });

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#message').html('<div class="alert alert-success">' + response.message + '</div>').show();
                },
                error: function(xhr) {
                    if (xhr.responseJSON) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        for (let key in errors) {
                            errorMessage += errors[key][0] + '\n';
                        }
                        $('#message').html('<div class="alert alert-danger">' + errorMessage + '</div>').show();
                    } else {
                        $('#message').html('<div class="alert alert-danger">An unexpected error occurred.</div>').show();
                    }
                }
            });
        });
    });
</script>
@endsection
