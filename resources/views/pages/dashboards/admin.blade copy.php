@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <!--datatable css-->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!--datatable responsive css-->
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')

    <div class="row">
        <div class="col">

            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                            <div class="flex-grow-1">
                                <h4 class="fs-16 mb-1">Welcome Back, {{$user->first_name.' '.$user->last_name}}!</h4>
                            </div>
                        </div><!-- end card header -->
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                            <div class="flex-grow-1">
                                <br>
                                <h4 class="fs-16 mb-1">Poll Dashboard</h4>
                            </div>
                        </div><!-- end card header -->
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

                <div class="card">
                    <div class="card-body">
                        <div class="row row-cols-xxl-4 row-cols-lg-4 row-cols-md-4 row-cols-2">
                            <div class="col">
                                <div class="card">
                                        <a href="{{route('users.declined')}}" class="card-body bg-soft-danger"  role="button">
                                        <h5 class="card-title text-uppercase fw-semibold mb-1 fs-15">Calls Declined / Unreachable</h5>
                                        @can('is_admin')
                                        <p class="text-muted mb-0"><span class="fw-medium">{{$usersQuery->where('call_status', 2)->where('user_type', "participants")->count()}}</span></p>
                                        @else
                                        <p class="text-muted mb-0"><span class="fw-medium">{{auth()->user()->calls()->where('call_status', 2)->where('user_type', "participants")->count();}}</span></p>
                                        @endcan
                                    </a>
                                </div>
                                <!--end card-->
                            </div>
                            <!--end col-->
            
                            <div class="col">
                                <div class="card">
                                        <a href="{{route('users.notreached')}}"  class="card-body bg-soft-warning"  role="button">
                                        <h5 class="card-title text-uppercase fw-semibold mb-1 fs-15">Missed Calls</h5>
                                        @can('is_admin')
                                        <p class="text-muted mb-0"><span class="fw-medium">{{$usersQuery2->where('call_status', 3)->where('user_type', "participants")->count()}}</span></p>
                                        @else
                                        <p class="text-muted mb-0"><span class="fw-medium">{{auth()->user()->calls()->where('call_status', 3)->where('user_type', "participants")->count();}}</span></p>
                                        @endcan
                                    </a>
                                </div>
                            </div>
                            <!--end col-->
            
                            <div class="col">
                                <div class="card">
                                        <a href="{{route('users.picked')}}"  class="card-body bg-soft-success" role="button">
                                        <h5 class="card-title text-uppercase fw-semibold mb-1 fs-15">Calls Back</h5>
                                        @can('is_admin')
                                        <p class="text-muted mb-0"><span class="fw-medium">{{$usersQuery3->where('call_status', 7)->where('user_type', "participants")->count()}}</span></p>
                                        @else
                                        <p class="text-muted mb-0"><span class="fw-medium">{{auth()->user()->calls()->where('call_status', 7)->where('user_type', "participants")->count();}}</span></p>
                                        @endcan
                                    </a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                        <a href="{{route('users.picked')}}"  class="card-body bg-soft-success" role="button">
                                        <h5 class="card-title text-uppercase fw-semibold mb-1 fs-15">Invalid/Wrong/No Phone Number</h5>
                                        @can('is_admin')
                                        <p class="text-muted mb-0"><span class="fw-medium">{{$usersQuery4->where('call_status', 8)->where('user_type', "participants")->count()}}</span></p>
                                        @else
                                        <p class="text-muted mb-0"><span class="fw-medium">{{auth()->user()->calls()->where('call_status', 8)->where('user_type', "participants")->count();}}</span></p>
                                        @endcan
                                    </a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                        <a href="{{route('users.picked')}}"  class="card-body bg-soft-success" role="button">
                                        <h5 class="card-title text-uppercase fw-semibold mb-1 fs-15">Calls Picked and Participated</h5>
                                        @can('is_admin')
                                        <p class="text-muted mb-0"><span class="fw-medium">{{$usersQuery5->where('call_status', 1)->where('user_type', "participants")->count()}}</span></p>
                                        @else
                                        <p class="text-muted mb-0"><span class="fw-medium">{{auth()->user()->calls()->where('call_status', 1)->where('user_type', "participants")->whereHas('participantAnswers')->count();}}</span></p>
                                        @endcan
                                    </a>
                                </div>
                            </div>
                            <!--end col-->
            
                            <div class="col">
                                <div class="card">
                                    <a href="{{route('users.pending')}}"  class="card-body bg-soft-warning" role="button">
                                        <h5 class="card-title text-uppercase fw-semibold mb-1 fs-15">Not Contacted</h5>
                                        <p class="text-muted mb-0"><span class="fw-medium">{{$usersQuery6->where('call_status', 0)->orWhere('called_by', Null)->count()}}</span></p>
                                        {{-- <p class="text-muted mb-0"><span class="fw-medium">{{$users->where('call_status', 0)->where('user_type', "participants")->where('called_by', Null)->count()}}</span></p> --}}
                                    </a>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                    </div>
                </div>

                {{-- <div class="row">
                    <div class="col-xl-4 col-md-6">
                        <!-- card -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                            Total Partcipants</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{$farmers_recorded}}">0</span>
                                        </h4>
                                        <a href="{{route('farmers_data.view_all')}}" class="text-decoration-underline">View</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success rounded fs-3">
                                        <i class="bx bx-dollar-circle"></i>
                                    </span>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div> --}}
                @foreach($polls as $poll)
                @if($poll->id == 3)
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <!-- card -->
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h4 class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                Poll {{$loop->iteration.': '. $poll->name}}</h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    {{-- <table class="table table-striped"> --}}
                                        <table id="buttons-datatables-new-record" class="display dt-responsive align-middle table-hover table table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Number</th>
                                                    <th>Question</th>
                                                    <th>Answer(s)</th>
                                                    <th>Count/Sum</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($poll->questions->sortBy('id') as $poll_question)
                                                    <tr>
                                                        <td>
                                                            <p class="">{{ $loop->iteration }}</p>
                                                        </td>
                                                        <td>
                                                            <p class="">{{ $poll_question->id . ': ' . $poll_question->question }}</p>
                                                        </td>
                                                        <td>
                                                            @if($poll_question->question_type == "multiple")
                                                                @foreach($poll_question->answers as $poll_answer)
                                                                    <p class="">
                                                                        {{ $loop->iteration }}: {{ $poll_answer->answer }}
                                                                    </p>
                                                                @endforeach
                                                                <p><strong>Blanks:</strong></p>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($poll_question->question_type == "multiple")
                                                                @foreach($poll_question->answers as $poll_answer)
                                                                    @php
                                                                        // Get latest answer for each participant
                                                                        $participantAnswers = $poll_answer->participantAnswers()
                                                                            ->orderBy('created_at', 'desc') // Assumes created_at or id can show latest record
                                                                            ->get()
                                                                            ->unique('participant_id'); // Get the latest answer for each participant
                                        
                                                                        $participantAnswerCount = $participantAnswers->count();
                                                                        $blankAnswerCount = $poll_question->participantAnswers()
                                                                                            ->whereNull('answer_id') // Count where answer is null
                                                                                            ->orWhere('answer_id', '') // Count empty string answers
                                                                                            ->orWhere('answer_id', ' ') // Count empty string answers
                                                                                            ->count();
                                                                    @endphp
                                                                    <p class="">
                                                                        <span class="counter-value" data-target="{{ $participantAnswerCount }}">
                                                                            {{ $participantAnswerCount }}
                                                                        </span>
                                                                    </p>
                                                                @endforeach
                                                                <p><span class="counter-value" data-target="{{ $blankAnswerCount }}">
                                                                    {{ $blankAnswerCount }}
                                                                </span></p>
                                                            @else
                                                                @php
                                                                    // Get latest answers for single-type questions
                                                                    $participantAnswers = $poll_question->participantAnswers()
                                                                        ->orderBy('created_at', 'desc') // Assuming created_at or id helps to pick the latest
                                                                        ->get()
                                                                        ->unique('participant_id'); // Get unique participants' latest answers
                                        
                                                                    $blankAnswerCount = $poll_question->participantAnswers()
                                                                                        ->whereNull('answer') // Count where answer is null
                                                                                        ->orWhere('answer', '') // Count empty string answers
                                                                                        ->count();
                                        
                                                                    // Check if the question ID is in the list that should be counted, not summed
                                                                    if(in_array($poll_question->id, [12, 19, 20, 25, 26, 27])) {
                                                                        // Count non-blank responses (latest for each participant)
                                                                        $participantAnswerCount = $participantAnswers->filter(function ($value) {
                                                                            return !is_null($value->answer) && $value->answer !== ''; // Exclude blank answers
                                                                        })->count();
                                                                        $participantAnswerSum = "Count: $participantAnswerCount";
                                                                    } else {
                                                                        // Sum numeric values for other single-type questions (latest for each participant)
                                                                        $participantAnswerSum = $participantAnswers->filter(function ($value) {
                                                                            return is_numeric($value->answer); // Ensure the value is numeric
                                                                        })->map(function ($value) {
                                                                            return (int)$value->answer; // Convert to integer
                                                                        })->sum();
                                                                    }
                                                                @endphp
                                                                <p class="">
                                                                    Total: <span class="counter-value" data-target="{{ $participantAnswerSum }}">
                                                                        {{ $participantAnswerSum }}
                                                                    </span>
                                                                </p>
                                                                <p><strong>Blanks:</strong> {{ $blankAnswerCount }}</p>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        
                                        
                                    

                                
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                    </div> <!-- end row-->
                @endif
                @endforeach

                   


            </div> <!-- end .h-100-->

        </div> <!-- end col -->

    </div>
@endsection
@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var showModalBtn = document.getElementById("showModalBtn");
            var modal = new bootstrap.Modal(document.getElementById("staticBackdrop"));
            modal.show();
        });
    </script>
    <!-- apexcharts -->
    <script src="{{ URL::asset('build/js/pages/project-overview.init.js') }}"></script>
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js')}}"></script>
    <!-- dashboard init -->
    <script src="{{ URL::asset('build/js/pages/dashboard-ecommerce.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
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


