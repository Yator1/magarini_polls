@extends('layouts.master')
@section('title') {{$pagename}} @endsection
@section('css')
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<!--datatable responsive css-->
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
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
@slot('title'){{$pagename}} @endslot
@endcomponent



<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">All Calls by {{$agent->first_name?? '' .' '. $agent->last_name ?? ''}}</h5>
            </div>
            <div class="card-body">
                {{-- <table id="buttons-datatables" class="display dt-responsive align-middle table-hover table table-bordered" style="width:100%"> --}}
                {{-- <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%"> --}}
                        {{-- <table id="scroll-vertical" class="table table-bordered nowrap align-middle mdl-data-table" style="width:100%"> --}}
                        {{-- <table id="scroll-vertical" class="table nowrap align-middle" style="width:100%"> --}}
                <table id="buttons-datatables" class="display dt-responsive align-middle table-hover table table-bordered" style="width:100%"> 
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone No</th>
                            <th>FacilityType</th>
                            <th>FacilityLevel</th>
                            <th>LicenceNo</th>
                            <th>County</th>
                            @foreach($poll->questions as $poll_question)
                            <th>{{$poll_question->question}}</th>
                            @endforeach
                            <th>Follow Up Date</th>
                            <th>Comments</th>
                            <th>Agent</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            {{-- <td>{{$loop->iteration .'/ '.$user->id}}</td> --}}
                            <td>{{$loop->iteration }}</td>
                            <td>{{$user->first_name}}</td>
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
                            </td>
                           
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
                                <td>
                                    {{$followUpDate ?? 'No Follow Up Date'}}
                                    {{-- @if($followUpDate)
                                    {{$followUpDate}}
                                    @else
                                    <div class="mb-3">
                                        <label for="exampleInputdate" class="form-label">Follow Up Date</label>
                                        <input style="z-index: 1001;" type="date" name="followUpDate"  class="form-control" id="exampleInputdate">
                                        
                                    </div>
                                    @endif --}}
                                </td>
                                <td>
                                    {{$comment ?? 'No Comment'}}
                                    {{-- @if($comment)
                                    {{$comment}}
                                    @else
                                    <div class="mb-3">
                                        <label for="exampleInputdate" class="form-label">Follow Up Date</label>
                                        <input style="z-index: 1001;" type="date" name="followUpDate"  class="form-control" id="exampleInputdate">
                                        
                                    </div>
                                    @endif --}}
                                </td>
                                <td>
                                    {{$user->CalledBy->first_name }}
                                </td>
                                <td>
                                    <a href="{{route('participant-answers.addPoll2', $user->id)}}" class="btn btn-secondary btn-sm dropdown" type="button" >
                                        <i class="ri-eye-fill align-bottom me-2 "></i> View
                                    </a>
                                </td>
                        </tr>
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

@endsection