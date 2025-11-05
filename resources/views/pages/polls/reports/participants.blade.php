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

<link rel="stylesheet" href="{{ URL::asset('build/libs/@simonwep/pickr/themes/classic.min.css') }}" /> <!-- 'classic' theme -->
<link rel="stylesheet" href="{{ URL::asset('build/libs/@simonwep/pickr/themes/monolith.min.css') }}" /> <!-- 'monolith' theme -->
<link rel="stylesheet" href="{{ URL::asset('build/libs/@simonwep/pickr/themes/nano.min.css') }}" /> <!-- 'nano' theme -->
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
@slot('title')Participations @endslot
@endcomponent



<div class="row">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Filter</h5>
            </div>
            <div class="card-body">

                <form class="needs-validation" validate id="all2" method="POST" action="{{route('user.participants')}}"> 
                    @csrf
                    <div class="row">
                        {{-- <div class="col-lg-6">
                            <label for="county" class="form-label">Counties</label>
                            <select class="form-select js-example-basic-single" name="county" id="county" aria-label="Default select example" @required(true)>
                                <option value="" disabled selected>Select County</option> <!-- Disable and make the placeholder non-selectable -->
                                <option value="all"  selected>All</option> <!-- Disable and make the placeholder non-selectable -->
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
                        </div> --}}

                        <div class="col-lg-6">
                            <div>
                                <label class="form-label mb-0">Select Start Date</label>
                                <input type="text" name="date_1" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d"
                                    value="{{ old('date_1', $date1 ?? Carbon\Carbon::today()->toDateString()) }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div>
                                <label class="form-label mb-0">Select End Date</label>
                                <input type="text" name="date_2" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d"
                                    value="{{ old('date_2', $date2 ?? Carbon\Carbon::today()->toDateString()) }}">
                            </div>
                        </div>
                        <div class="mb-3 mt-3 col-lg-12">
                            <div class="modal-footer">
                                {{-- <button id="clear" class="btn btn-danger">
                                    <i class="ri-save-line align-bottom me-1"></i>
                                    Clear</button> --}}
                                <button type="submit" class="btn btn-success"><i class="ri-save-line align-bottom me-1"></i>
                                    Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">All Participations</h5>
            </div>
            <div class="card-body">
                {{-- <table id="buttons-datatables" class="display dt-responsive align-middle table-hover table table-bordered" style="width:100%"> --}}
                {{-- <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%"> --}}

                <table id="buttons-datatables" class="display dt-responsive align-middle table-hover table table-bordered" style="width:100%">
                    {{-- <table id="buttons-datatables" class="display dt-responsive align-middle table-hover table table-bordered" style="width:100%">  --}}
                        {{-- <table id="scroll-vertical" class="table table-bordered nowrap align-middle mdl-data-table" style="width:100%"> --}}
                        {{-- <table id="scroll-vertical" class="table nowrap align-middle" style="width:100%"> --}}
                    <thead>
                        <tr>
                            <th>#</th>
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
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            @if($user)
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
                                    </td> --}}


                                    {{-- <td>
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
                                                $userAnswerComm = $user->participantAnswers()->where('poll_id', $poll->id)->whereNotNull('comment')->first();
                                                if($userAnswer){
                                                    $comment = $userAnswerComm->comment ?? '';
                                                    $followUpDate = $userAnswer->followUpDate ?? '';
                                                }
                                            @endphp
                                            
                                    
                                            @if($userAnswer->QAnswer)
                                                {{$userAnswer->QAnswer->answer;}}
                                            @else
                                            {{ $userAnswer->answer;}}
                                            @endif
                                        @if($userAnswer)
                                        @else
                                        {{-- No Answer--}}

                                            <form class="needs-validation" validate id="" method="POST" action="{{route('participant-answers.update')}}"> 
                                                @csrf

                                                <input type="hidden" class="form-control" name="participant_id" value="{{$user->id}}" required hidden>
                                                <input type="hidden" class="form-control" name="poll_id" value="{{$poll->id}}" required hidden>
                                                <div class="mb-3">
                                                    <label for="question_{{$loop->iteration}}" class="form-label">Question {{$loop->iteration}}: {{ $poll_question->question }}</label>
                                                    <select class="form-select" name="question_{{ $poll_question->id }}" id="question_{{$loop->iteration}}" aria-label="Default select example" required>
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
                                                        Update</button>
                                                </div>
                                            </form> 
                                        @endif
                                        </td>
                                        @endforeach
                                        {{-- <td>
                                            {{$followUpDate ?? 'No Follow Up Date'}} --}}
                                            {{-- @if($followUpDate)
                                            {{$followUpDate}}
                                            @else
                                            <div class="mb-3">
                                                <label for="exampleInputdate" class="form-label">Follow Up Date</label>
                                                <input style="z-index: 1001;" type="date" name="followUpDate"  class="form-control" id="exampleInputdate">
                                                
                                            </div>
                                            @endif --}}
                                        {{-- </td> --}}
                                        {{-- <td>
                                            {{$comment ?? 'No Comment'}} --}}
                                            {{-- @if($comment)
                                            {{$comment}}
                                            @else
                                            <div class="mb-3">
                                                <label for="exampleInputdate" class="form-label">Follow Up Date</label>
                                                <input style="z-index: 1001;" type="date" name="followUpDate"  class="form-control" id="exampleInputdate">
                                                
                                            </div>
                                            @endif --}}
                                        {{-- </td> --}}
                                        <td>
                                            {{$user->CalledBy->first_name ?? 'Test Poll' }}

                                    @if($user->CalledBy)
                                    {{$user->CalledBy->first_name ?? 'Test Poll'}}
                                    @endif
                                        </td>
                                        <td>
                                            @if($user->CalledBy)
                                            <a href="{{route('participant-answers.addPoll2', $user->id)}}" class="btn btn-secondary btn-sm dropdown" type="button" >
                                                <i class="ri-eye-fill align-bottom me-2 "></i> View
                                            </a>
                                            @else
                                            Test
                                            @endif
                                        </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                @if($users  && $type == "needspaginate")
                <div class="pagination-container">
                    <a href="{{$users->previousPageUrl()}}">
                        <!-- You can insert logo or text here -->
                        Previous
                    </a>
                    <a href="{{$users->nextPageUrl()}}">
                        <!-- You can insert logo or text here -->
                        Next
                    </a>
                    <br>
                    <br>
                    @for($i=1; $i<=$users->lastPage(); $i++)
                        <!-- a Tag for another page -->
                        <a href="{{$users->url($i)}}" class="{{ $i == $users->currentPage() ? 'active' : '' }}">
                            {{$i}}
                        </a>
                    @endfor
                    <!-- a Tag for next page -->
                   
                </div>
                @endif
                

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

<script src="{{ URL::asset('build/libs/@simonwep/pickr/pickr.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/form-pickers.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection