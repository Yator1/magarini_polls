@extends('layouts.master')
@section('title') @lang('translation.datatables') @endsection
@section('css')
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<!--datatable responsive css-->
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="{{ URL::asset('build/libs/@simonwep/pickr/themes/classic.min.css') }}" /> <!-- 'classic' theme -->
<link rel="stylesheet" href="{{ URL::asset('build/libs/@simonwep/pickr/themes/monolith.min.css') }}" /> <!-- 'monolith' theme -->
<link rel="stylesheet" href="{{ URL::asset('build/libs/@simonwep/pickr/themes/nano.min.css') }}" /> <!-- 'nano' theme -->
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') Tables @endslot
@slot('title')Staff @endslot
@endcomponent



<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">All Staff From: {{$date1.' - '.$date1}}</h5>
                <br>
                <hr>

                <form action="{{ route('users.agents') }}" method="POST">
                    @csrf
                    <div class="row gy-3">
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
                    </div>
            
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
                <br>
                <hr>
            </div>
            <div class="card-body">
                <table id="buttons-datatables" class="display dt-responsive align-middle table-hover table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone No</th>
                            <th>Email</th>
                            <th>Declined/ Unreachable</th>
                            <th>Missed Calls</th>
                            <th>Picked</th>
                            <th>Call Back</th>
                            <th>Invalid Phone No</th>
                            <th>Total</th>
                            <th>All</th>
                            <th>Picked</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $declinedCount = 0;
                            $missedCallsCount = 0;
                            $pickedCount = 0;
                            $callBackCount = 0;
                            $invalidPhoneCount = 0;
                            $totalCount = 0;
                        @endphp
                
                        @foreach ($users as $user)
                        @if($user->Calls->count() > 0)
                        <tr>
                            <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
                            <td>{{ $user->phone_no }}</td>
                            <td>{{ $user->email }}</td>
                            @php
                                $declined = $user->Calls->where('call_status', 2)->where('user_type', 'participants')->whereBetween('updated_at', [$date1, $date2])->count();
                                $missedCalls = $user->Calls->where('call_status', 3)->where('user_type', 'participants')->whereBetween('updated_at', [$date1, $date2])->count();
                                $picked = $user->Calls->where('call_status', 1)->where('user_type', 'participants')->whereBetween('updated_at', [$date1, $date2])->count();
                                $callBack = $user->Calls->where('call_status', 7)->where('user_type', 'participants')->whereBetween('updated_at', [$date1, $date2])->count();
                                $invalidPhone = $user->Calls->where('call_status', 8)->where('user_type', 'participants')->whereBetween('updated_at', [$date1, $date2])->count();
                                $total = $user->Calls->where('user_type', 'participants')->whereBetween('updated_at', [$date1, $date2])->count() - $user->Calls->where('call_status', 0)->where('user_type', 'participants')->whereBetween('updated_at', [$date1, $date2])->count();
                
                                // Update the sums
                                $declinedCount += $declined;
                                $missedCallsCount += $missedCalls;
                                $pickedCount += $picked;
                                $callBackCount += $callBack;
                                $invalidPhoneCount += $invalidPhone;
                                $totalCount += $total;
                            @endphp
                            <td>{{ $declined }}</td>
                            <td>{{ $missedCalls }}</td>
                            <td>{{ $picked }}</td>
                            <td>{{ $callBack }}</td>
                            <td>{{ $invalidPhone }}</td>
                            <td>{{ $total }}</td>
                            <td>
                                <a href="{{ route('user.agentCalls', $user->id) }}" class="btn btn-secondary btn-sm dropdown" type="button">
                                    <i class="ri-eye-fill align-bottom me-2"></i> All Contacts
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('user.agentCallsPicked', $user->id) }}" class="btn btn-success btn-sm dropdown" type="button">
                                    <i class="ri-eye-fill align-bottom me-2"></i> Picked Calls
                                </a>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><strong>Total</strong></td>
                            <td colspan="2"></td>
                            <td>{{ $declinedCount }}</td>
                            <td>{{ $missedCallsCount }}</td>
                            <td>{{ $pickedCount }}</td>
                            <td>{{ $callBackCount }}</td>
                            <td>{{ $invalidPhoneCount }}</td>
                            <td>{{ $totalCount }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
                
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

<script src="{{ URL::asset('build/libs/@simonwep/pickr/pickr.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/form-pickers.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection
