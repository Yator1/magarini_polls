@extends('layouts.master')
@section('title') @lang('translation.datatables') @endsection
@section('css')
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<!--datatable responsive css-->
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') Tables @endslot
@slot('title') {{ $pagename }} @endslot
@endcomponent



<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">All {{ $pagename }}</h5>
            </div>
            <div class="card-body">
                <table id="buttons-datatables" class="display dt-responsive align-middle table-hover table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone No</th>
                            <th>Id No</th>
                            <th>Value Chain</th>
                            <th>Aggregator</th>
                            <th>Sub County</th>
                            <th>Ward</th>
                            <th>Polling Station</th>
                            <th>Registration Centre</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{$user->first_nam.' '.$user->last_name}}</td>
                            <td>{{$user->phone_no}}</td>
                            <td>{{$user->id_no}}</td>
                            <td>
                                @php
                                    $valueChainArray = json_decode($user->value_chain, true); // Decode JSON string to array
                                    $formattedValueChain = implode(', ', $valueChainArray); // Join array elements with a comma and space
                                    echo $formattedValueChain; // Output the formatted value chain
                                @endphp
                            </td>
                            {{-- <td>{{$user->Aggregator->name ?? 'N/A'}}</td> --}}
                            <td>
                                @if($user->aggregator)
                                    @php
                                        $aggregatorIds = json_decode($user->aggregator, true); // Decode JSON to array
                                        if ($aggregatorIds === null && json_last_error() !== JSON_ERROR_NONE) {
                                            // If decoding failed, assume it's a single ID
                                            $aggregatorIds = [$user->aggregator];
                                        }
                                        $aggregatorNames = [];
                                        // Check if $aggregatorIds is an array, otherwise, handle it as a single ID
                                        if (is_array($aggregatorIds)) {
                                            foreach ($aggregatorIds as $aggregatorId) {
                                                $aggregator = \App\Models\Aggregator::find($aggregatorId);
                                                if ($aggregator) {
                                                    $aggregatorNames[] = $aggregator->name;
                                                }
                                            }
                                        } else {
                                            $aggregator = \App\Models\Aggregator::find($aggregatorIds);
                                            if ($aggregator) {
                                                $aggregatorNames[] = $aggregator->name;
                                            }
                                        }
                                        echo implode(', ', $aggregatorNames);
                                    @endphp
                                @else
                                    N/A
                                @endif
                            </td>
                            
                                                       
                            <td>{{$user->SubCounty->name ?? 'N/A'}}</td>
                            <td>{{$user->Ward->name ?? 'N/A'}}</td>
                            <td>{{$user->PollingStations->name ?? 'N/A'}}</td>
                            <td>{{$user->RegistrationCentre->name ?? 'N/A'}}</td>
                            <td>
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-fill align-middle"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a href="#!" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                                        <li><a class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                        <li>
                                            <a class="dropdown-item remove-item-btn">
                                                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
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