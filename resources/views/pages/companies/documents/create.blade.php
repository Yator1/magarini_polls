@extends('layouts.master')
@section('title')
    @lang('translation.overview')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card mt-n4 mx-n4">
                <div class="bg-soft-warning">
                    <div class="card-body pb-0 px-4">
                        <div class="row mb-3">
                            <div class="col-md">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-auto">
                                        <div class="avatar-md">
                                            <div class="avatar-title bg-white rounded-circle">
                                                <img src="{{ URL::asset('/documents/logo/'.$application->company_logo) }}" alt="" class="avatar-xs">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div>
                                            <h4 class="fw-bold">{{$application->name}} COMPANY DETAILS</h4>
                                            <div class="hstack gap-3 flex-wrap">
                                                <div><i class="ri-building-line align-bottom me-1"></i> </div>
                                                <div class="vr"></div>
                                                <div>Application Date : <span class="fw-medium"></span>{{$application->created_at}}</div>
                                                <div class="vr"></div>
                                                <div>Approval Date : <span class="fw-medium"></span></div>
                                                <div class="vr"></div>
                                                <div>Application Status :
                                                    @if($application->status == 2)
                                                        <div class="badge rounded-pill badge-soft-info fs-12">Pending</div>
                                                    @elseif($application->application_status == 1)
                                                        <div class="badge rounded-pill badge-soft-success fs-12">Initial Application</div>
                                                    @elseif($application->application_status == 3)
                                                        <div class="badge rounded-pill badge-soft-danger fs-12">Approved</div>
                                                    @elseif($application->application_status == 4)
                                                        <div class="badge rounded-pill badge-soft-danger fs-12">Rejected</div>
                                                    @else
                                                        <div class="badge rounded-pill badge-soft-danger fs-12">Invalid Application</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- application fee payment modal -->
                                            <!-- staticBackdrop Modal -->
                                            <!-- modal to pay application fee -->

                                            {{--                                            application fee payment modal--}}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <ul class="nav nav-tabs-custom border-bottom-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active fw-semibold" data-bs-toggle="tab" href="#project-overview" role="tab">
                                    Overview
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#project-documents" role="tab">
                                    Documents
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- end card body -->
                </div>
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content text-muted">
                <div class="tab-pane fade show active" id="project-overview" role="tabpanel">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-muted">
                                        <h6 class="mb-3 fw-semibold text-uppercase">Summary</h6>
                                        <p>
                                            {{$application->description}}
                                        </p>

                                        <h6 class="mb-3 fw-semibold text-uppercase">Contact Details</h6>
                                        <div class="hstack gap-3 flex-wrap">
                                            <div class="vr"></div>
                                            <div>Name: {{$application->name}}</div>
                                            <div class="vr"></div>
                                            <div>Email: {{$application->email}}</div>
                                            <div class="vr"></div>
                                            <div>Phone Number: {{$application->phone_no}}</div>
                                            <div class="vr"></div>
                                            <div>Tel No: {{$application->tel_no}}</div>
                                            <div class="vr"></div>
                                        </div><br>

                                        <h6 class="mb-3 fw-semibold text-uppercase">Other Details</h6>

                                        <div class="hstack gap-3 flex-wrap">
                                            <div class="vr"></div>
                                            <div>Company Registration No: {{$application->company_registartion_no}}</div>
                                            <div class="vr"></div>
                                            <div>KRA Pin: {{$application->kra_pin}}</div>
                                            <div class="vr"></div>
                                            <div>Contact Person Name: {{$application->contact_person_name}}</div>
                                            <div class="vr"></div>
                                            <div>Ownership Type: {{$application->ownership_type}}</div>
                                            <div class="vr"></div>
                                        </div><br><br>

                                        <div class="hstack gap-3 flex-wrap">
                                            <div class="vr"></div>
                                            <div>Services Offered: {{$application->services_offered}}</div>
                                            <div class="vr"></div>
                                            <div>Supplier Category: {{$application->supplier_category}}</div>
                                            <div class="vr"></div>
                                            <div>Country Of Operation: {{$application->country_of_operation}}</div>
                                            <div class="vr"></div>
                                            <div>City/Town: {{$application->city_town}}</div>
                                            <div class="vr"></div>
                                        </div><br><br>

                                        <div class="hstack gap-3 flex-wrap">
                                            <div class="vr"></div>
                                            <div>Street Address: {{$application->street_address}}</div>
                                            <div class="vr"></div>
                                            <div>Postal Address: {{$application->postal_address}}</div>
                                            <div class="vr"></div>
                                            <div>Company Category: {{\App\Models\CompanyCategory::where('id',$application->company_category_id)->value('name')}}</div>
                                            <div class="vr"></div>
                                            <div class="vr"></div>
                                        </div><br><br>

                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                            @if($application->status == 4)
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Appeal Application Rejection</h4>
                                        <div class="flex-shrink-0">
                                            <div class="dropdown card-header-dropdown">
                                                <h6 class="card-title mb-0 flex-grow-1">Rejection Count :{{$application->rejection_count}}</h6>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                        </div>
                                    </div><!-- end card header -->

                                    <div class="card-body">

                                        <div data-simplebar style="height: 300px;" class="px-3 mx-n3 mb-2">
                                            <div class="d-flex mb-4">
                                                {{-- user profile photo --}}
                                                {{-- <div class="flex-shrink-0">
                                                            <img src="{{ URL::asset('build/images/users/avatar-8.jpg') }}" alt=""
                                                class="avatar-xs rounded-circle" />
                                            </div> --}}
                                                <div class="flex-grow-1 ms-3">
                                                    <h5 class="fs-13">Rejected by: {{$application->assessor->fname ?? 'No Assessor Assigned', $application->assessor->lname?? 'No Assessor Assigned'}} <small class="text-muted ms-2">{{$application->rejection_date}}</small></h5>
                                                    <p class="text-muted">Rejection Reason : {{$application->rejection_reason?? 'No Rejection Reason'}}</p>

                                                    <div class="d-flex mt-4">
                                                        {{-- user profile photo --}}
                                                        {{-- <div class="flex-shrink-0">
                                                                        <img src="{{ URL::asset('build/images/users/avatar-10.jpg') }}" alt=""
                                                        class="avatar-xs rounded-circle" />
                                                    </div> --}}
                                                        <div class="flex-grow-1 ms-3">
                                                            <h5 class="fs-13">Appealed by {{$application->owner->fname , $application->owner->lname}} <small class="text-muted ms-2">Appeal Date : {{$application->updated_at}}</small></h5>
                                                            <p class="text-muted">Appeal Reason : {{$application->rejection_reason?? 'No Rejection Reason'}}.</p>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <form class="mt-4">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label for="exampleFormControlTextarea1" class="form-label text-body">Leave
                                                        a Comments</label>
                                                    <textarea class="form-control bg-light border-light" id="exampleFormControlTextarea1" rows="3" placeholder="Enter your comment..."></textarea>
                                                </div>
                                                <div class="col-12 text-end">
                                                    <button type="button" class="btn btn-ghost-secondary btn-icon waves-effect me-1"><i class="ri-attachment-line fs-16"></i></button>
                                                    <a href="javascript:void(0);" class="btn btn-success">Submit</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- end card body -->
                                </div>
                            @endif
                        </div>
                        <!-- ene col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end tab pane -->
                <div class="tab-pane fade" id="project-documents" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <h5 class="card-title flex-grow-1">Documents</h5>
                            </div>

                            @if($application->document_status == 1)
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive table-card">
                                            <table class="table table-borderless align-middle mb-0">
                                                <thead class="table-light">
                                                <tr>
                                                    <th scope="col">File Name</th>
                                                    <th scope="col">Type</th>
                                                    <th scope="col">Upload Date</th>
                                                    <th scope="col" style="width: 120px;">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                {{-- ownership document --}}

                                                @for($i = 0;$i < count($columns);$i++){
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm">
                                                                <div class="avatar-title bg-light text-danger rounded fs-24">
                                                                    <i class="ri-file-pdf-fill"></i>
                                                                </div>
                                                            </div>
                                                            <div class="ms-3 flex-grow-1">
                                                                <h5 class="fs-14 mb-0">
                                                                        <a href="{{ asset('/documents/'.$columns[$i].'/'.$application[$columns[$i]]) }}" download class="text-dark">
                                                                            {{$company_documents[$columns[$i]]}}
                                                                        </a>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>PDF File</td>
                                                    <td>{{$application->created_at}}</td>
                                                    <td>
                                                        <button onclick="window.open('{{ asset('/documents/'.$columns[$i].'/'.$company_documents[$columns[$i]]) }}','popUpWindow','height=800,width=600,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');" type="button" class="btn btn-primary btn-label waves-effect right waves-light">
                                                            <i class="ri-eye-fill label-icon align-middle fs-16 ms-2"></i> View
                                                        </button>
                                                    </td>
                                                </tr>


                                                @endfor

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-center mt-3">
                                            <a href="javascript:void(0);" class="text-success "><i class="mdi mdi-loading mdi-spin fs-20 align-middle me-2"></i> Load more
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            @else
                                <div class="card-body">
                                    <div id="form-messages">
                                        @include('components.partials.alerts.flash')
                                        @include('components.partials.alerts.errors')
                                    </div>

                                    <form action="{{url('documents.store')}}" method="POST" class="form-steps needs-validation" autocomplete="off" enctype="multipart/form-data" data-submit-loader="true">
                                        @csrf
                                        <div class="text-center pt-3 pb-4 mb-1">
                                            <h3>Upload Company Documents</h3>
                                        </div>

                                        {{-- progress bar --}}

                                        <div class="tab-content">
                                            {{-- File Uploads --}}
                                            <div class="tab-pane fade show active" id="pills-success" role="tabpanel" aria-labelledby="pills-success-tab">
                                                <div>
                                                    <div class="mb-4">
                                                        <div>
                                                            <p class="text-muted">Upload all the documents listed below </p>
                                                        </div>
                                                    </div>
                                                    <div class="row">


                                                            <div class="col xl-6">

                                                                <div class="row mb-3">
                                                                    @for($i = 0;$i<count($columns);$i++)
                                                                    <h5 style="color:#405189;">{{Str::of($columns[$i])
    ->replace('_', ' ')
    ->title()}}</h5>
                                                                    <div class="col-xl-12">
                                                                        <label for="{{$columns[$i]}}" class="form-label">Upload {{Str::of($columns[$i])
    ->replace('_', ' ')
    ->title()}}</label>
                                                                        <input value="old{{$columns[$i]}}" name="{{$columns[$i]}}" class="form-control @error($columns[$i]) is-invalid @enderror" type="file" id="{{$columns[$i]}}" accept="application/pdf" @if($columns[$i] == 'agpo_certificate') @else required @endif />
                                                                    </div>
                                                                        <hr style="margin-top: 10px; margin-bottom: 10px; color:#405189; font-weight: bold;">
                                                                        @error($columns[$i])
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                        @enderror
                                                                        <div class="invalid-feedback">
                                                                            Please Choose File
                                                                        </div>
                                                                    @endfor

                                                                </div>
                                                                <input value="{{$application->id}}" name="company_id" hidden>

                                                            </div>
                                                    </div>

                                                </div>
                                                </div>
                                                <div class="d-flex align-items-center gap-3 mt-4">
                                                    {{-- start spinner loader button  --}}
                                                    <button type="submit" class="btn btn-success btn-label right ms-auto nexttab nexttab" id="submit-button">
                                                        <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Submit
                                                    </button>

                                                    {{-- end spinner loader button --}}
                                                </div>
                                            </div>
                                            <!-- end tab pane -->
                                        </div>
                                        <!-- end tab content -->
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>

    <!-- MODALS -->
    <div class="modal fade" id="rejectionModal" aria-hidden="true" aria-labelledby="..." tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f7b84b,secondary:#405189" style="width:130px;height:130px">
                    </lord-icon>
                    <div class="mt-4 pt-3">
                        <h4>Are You Sure You Want to Reject This Application!</h4>
                        <form class="row g-3 needs-validation" novalidate class="mt-4" action="{{url('update-building-plan-status')}}" method="POST">
                            @csrf
                            @if(Gate::allows('isChiefOfficer'))
                                <input type="text" hidden value="chief_officer" name="type" id="">
                            @elseif(Gate::allows('isPhysicalPlanner'))
                                @if($application->planner_status == 0)
                                    <input type="text" hidden value="planner_vet" name="type" id="">
                                @elseif($application->planner_status != 0 && $application->s_b_committee_status == 0)
                                    <input type="text" hidden value="sub_county_committee" name="type" id="">
                                @endif
                            @elseif(Gate::allows('isCec'))
                                <input type="text" hidden value="county_committee" name="type" id="">
                            @elseif(Gate::allows('isPublicHealthOfficer'))
                                <input type="text" hidden value="public_health_officer" name="type" id="">
                            @elseif(Gate::allows('isPublicWorksOfficer'))
                                <input type="text" hidden value="public_works_officer" name="type" id="">
                            @endif
                            <input type="text" hidden value="rejection" name="form" id="">
                            <input type="text" hidden value="2" name="approval_status" id="">
                            <input type="text" hidden value="{{$application->id}}" name="application_id" id="">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="rejection_reason" class="form-label text-body">Enter Rejection Reason</label>
                                    <textarea name="rejection_reason" class="form-control bg-light border-light" id="rejection_reason" rows="3" placeholder="Enter your rejection reason here..."></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="hstack gap-2 justify-content-center">
                                        <!-- Toogle to first dialog, `data-bs-dismiss` attribute can be omitted - clicking on link will close dialog anyway -->
                                        <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                                        <button class="btn btn-info" data-bs-toggle="modal" data-bs-dismiss="modal">Submit </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- chief officer modal to assign physical planner -->
    <div class="modal fade" id="chiefOfficerModal1" aria-hidden="true" aria-labelledby="..." tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <div class="mt-4 pt-3">
                        <h4>Select Physical Planner Below</h4>
                        <form class="mt-4" action="{{url('assign-building-plan')}}" method="POST">
                            @csrf
                            <input type="text" hidden value="assign" name="type" id="">
                            <input type="text" hidden value="3" name="approval_status" id="">
                            <input type="text" hidden value="{{$application->id}}" name="application_id" id="">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="physical_planner" class="form-label">Physical Planner</label>
                                    <select class="form-select" id="physical_planner" name="physical_planner" required>
                                        <option selected disabled value="">Physical Planner
                                        </option>

                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="architect" class="form-label">Architect</label>
                                    <select class="form-select" id="architect" name="architect">
                                        <option selected disabled value="">Choose Architect
                                        </option>

                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="structural_engineer" class="form-label">Structural Engineer</label>
                                    <select class="form-select" id="structural_engineer" name="structural_engineer">
                                        <option selected disabled value="">Structural Engineer
                                        </option>

                                    </select>
                                </div>
                                <div class="hstack gap-2 justify-content-center">
                                    <!-- Toogle to first dialog, `data-bs-dismiss` attribute can be omitted - clicking on link will close dialog anyway -->
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-dismiss="modal">Assign </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- physical planner modal to add structural fee and select circulation -->
    <!-- public health modal, other, and public works modal to add fee and approve -->
    <!-- chief officer modal to confirm amount payable set -->
    <div class="modal fade" id="chiefOfficerModal2" aria-hidden="true" aria-labelledby="..." tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:120px;height:120px">
                    </lord-icon>
                    <div class="mt-4 pt-4">
                        <h5>Are You Sure You Want to Approve This Fee!</h5>
                        <!-- Toogle to second dialog -->
                        <br>
                        <form action="{{url('update-building-plan-status')}}" id="myForm" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="row g-3  bg-light border-light">
                                        <div class="col-6">
                                            <label for="structural_fee" class="form-label text-body mt-2">Structural Fee:</label><span>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" class="form-control bg-light border-light" value="{{$application->structural_fee ?? '0'}}" name="structural_fee" id="">

                                        </div>
                                    </div>
                                    <div class="row g-3  bg-light border-light">
                                        <div class="col-6">
                                            <label for="public_health_fee" class="form-label text-body mt-2">Public Health Fee:</label><span>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" class="form-control bg-light border-light" value="{{$application->public_health_fee ?? '0'}}" name="public_health_fee" id="">

                                        </div>
                                    </div>
                                    <div class="row g-3  bg-light border-light">
                                        <div class="col-6">
                                            <label for="public_works_fee" class="form-label text-body mt-2">Public Works Fee:</label><span>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" class="form-control bg-light border-light" value="{{$application->public_works_fee ?? '0'}}" name="public_works_fee" id="">

                                        </div>
                                    </div>
                                    </span>
                                </div>
                                <div class="col-12">
                                    <label for="chief_officer_payment_approval_comment" class="form-label text-body">Add Comment</label>
                                    <textarea required name="chief_officer_payment_approval_comment" class="form-control bg-light border-light" id="chief_officer_payment_approval_comment" rows="3" placeholder="Enter Comment..."></textarea>
                                </div>
                            </div>
                            <br>
                            <br>
                            <input type="text" hidden value="1" name="chief_officer_payment_approval_status" id="">
                            <input type="text" hidden value="chief_officer_payment_approval" name="type" id="">
                            <input type="text" hidden value="{{$application->id}}" name="application_id" id="">
                            <button class="btn btn-soft-danger" data-bs-target="#rejectionModal" data-bs-toggle="modal" data-bs-dismiss="modal">
                                NO! Reject
                            </button>
                            <button type="submit" class="btn btn-success">
                                Pre Vet
                            </button>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Subcounty commitee modal to upload minutes -->
    <div class="modal fade" id="subCountyModal" aria-hidden="true" aria-labelledby="..." tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:120px;height:120px">
                    </lord-icon>
                    <div class="mt-4 pt-4">
                        <h5>Are You Sure You Want to Approve This Application!</h5>
                        <!-- Toogle to second dialog -->
                        <br>
                        <form action="{{url('update-building-plan-status')}}" id="myForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="sub_county_committee_comment" class="form-label text-body">Add Comment</label>
                                    <textarea required name="sub_county_committee_comment" class="form-control bg-light border-light" id="sub_county_committee_comment" rows="3" placeholder="Enter Comment..."></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <h5 style="color:#405189;">Sub County Committee Minutes</h5>
                                <div class="col-xl-9">
                                    <label for="sub_county_committee_minutes_file" class="form-label">Upload Sub County Committee Minutes</label>
                                    <input value="old{{'sub_county_committee_minutes_file'}}" name="sub_county_committee_minutes_file" class="form-control" type="file" id="sub_county_committee_minutes_file" accept="application/pdf" required>
                                </div>
                            </div>
                            <br>
                            <br>
                            <input type="text" hidden value="1" name="sub_county_committee_status" id="">
                            <input type="text" hidden value="sub_county_committee" name="type" id="">
                            <input type="text" hidden value="{{$application->id}}" name="application_id" id="">
                            <button class="btn btn-soft-danger" data-bs-target="#rejectionModal" data-bs-toggle="modal" data-bs-dismiss="modal">
                                NO! Reject
                            </button>
                            <button type="submit" class="btn btn-success">
                                Pre Vet
                            </button>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- county committee modal to upload minutes -->
    <div class="modal fade" id="countyModal" aria-hidden="true" aria-labelledby="..." tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:120px;height:120px">
                    </lord-icon>
                    <div class="mt-4 pt-4">
                        <h5>Are You Sure You Want to Approve This Application!</h5>
                        <!-- Toogle to second dialog -->
                        <br>
                        <form action="{{url('update-building-plan-status')}}" id="myForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="county_committee_comment" class="form-label text-body">Add Comment</label>
                                    <textarea required name="county_committee_comment" class="form-control bg-light border-light" id="county_committee_comment" rows="3" placeholder="Enter Comment..."></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <h5 style="color:#405189;">County Committee Minutes</h5>
                                <div class="col-xl-9">
                                    <label for="county_committee_minutes_file" class="form-label">Upload County Committee Minutes</label>
                                    <input value="old{{'county_committee_minutes_file'}}" name="county_committee_minutes_file" class="form-control" type="file" id="county_committee_minutes_file" accept="application/pdf" required>
                                </div>
                            </div>
                            <br>
                            <br>
                            <input type="text" hidden value="1" name="county_committee_status" id="">
                            <input type="text" hidden value="county_committee" name="type" id="">
                            <input type="text" hidden value="{{$application->id}}" name="application_id" id="">
                            <button class="btn btn-soft-danger" data-bs-target="#rejectionModal" data-bs-toggle="modal" data-bs-dismiss="modal">
                                NO! Reject
                            </button>
                            <button type="submit" class="btn btn-success">
                                Pre Vet
                            </button>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <script>
        // Function to check if the phone number starts with 0
        function checkPhoneNumber() {
            var phoneNumberInput = document.getElementById('phone_number');
            var payButton = document.getElementById('payButton');
            var warningMessage = document.getElementById('warning');

            var phoneNumber = phoneNumberInput.value.trim();
            if (phoneNumber.length === 0 || phoneNumber.startsWith('0')) {
                payButton.disabled = false;
                warningMessage.textContent = '';
            } else {
                payButton.disabled = true;
                warningMessage.textContent = 'The phone number must begin with 0.';
            }
        }

        // Attach an event listener to the phone number input field
        document.getElementById('phone_number').addEventListener('input', function() {
            checkPhoneNumber();
        });

        // Call the function initially to set the initial state of the PAY button and warning message
        checkPhoneNumber();
    </script>
    <!-- end modal -->
@endsection
@section('script')
    <script src="{{ URL::asset('build/js/pages/project-overview.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/form-validation.init.js') }}"></script>

@endsection

<script>
    // Add a script to show the modal on page load
    document.addEventListener("DOMContentLoaded", function() {
        var showModalBtn = document.getElementById("showModalBtn");
        var modal = new bootstrap.Modal(document.getElementById("staticBackdrop"));
        modal.show();
    });

    // Add a script to validate the form
    document.getElementById("myForm").addEventListener("submit", function(event) {
        var checkboxes = document.querySelectorAll('input[name="circulation"]:checked');
        if (checkboxes.length === 0) {
            event.preventDefault(); // Prevent form submission
            alert("Please select at least one option.");
        }
    });
</script>
