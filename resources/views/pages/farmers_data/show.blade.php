@extends('layouts.master')
@section('title')
    @lang('translation.profile')
@endsection
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <!--datatable css-->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!--datatable responsive css-->
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')

    <div class="profile-foreground position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg">
            <img src="{{ URL::asset('build/images/murangalogo.png') }}" alt="" class="profile-wid-img" />
        </div>
    </div>
    <div class="pt-4 mb-4 mb-lg-3 pb-lg-4 profile-wrapper">
        <div class="row g-4">
            {{-- <div class="col-auto">
                <div class="avatar-lg">
                    {{-- <img src="@if (Auth::user()->avatar != '') {{ URL::asset('images/' . Auth::user()->avatar) }}@else{{ URL::asset('build/images/murangalogo.png') }} @endif" alt="user-img" class="img-thumbnail rounded-circle" /> --}}
                    {{-- <img src="{{ URL::asset('build/images/murangalogo.png') }} " alt="user-img" class="img-thumbnail rounded-circle" />
                </div>
            </div> --}} 
            <!--end col-->
            <div class="col">   
                <div class="p-2">
                    <h3 class="text-white mb-1">Tender Name: {{$tender->name}}</h3>
                    <h3 class="text-white mb-1">Health Facility: {{$tender->hospital->name}}</h3>
                    
                </div>
            </div>
            <!--end col-->
            <div class="col-12 col-lg-auto order-last order-lg-0">
                <div class="row text text-white-50 text-center">
                    <div class="col-lg-12 col-4">
                        <div class="p-2">
                            <h4 class="text-white mb-1">{{$tender->Status->name}}</h4>
                            <h4 class="text-white mb-1">Tender No</h4>
                            <h4 class="text-white mb-1">{{$tender->tender_no}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->

        </div>
        <!--end row-->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div>
                <div class="d-flex profile-wrapper">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span
                                    class="d-none d-md-inline-block">Overview</span>
                            </a>
                        </li>
                    </ul>
                    <div class="flex-shrink-0">
                        <a href="{{ route('tenders.index')}}" class="btn btn-success"><i class="ri-edit-box-line align-bottom"></i> All Tenders</a>
                    </div>
                </div>
                <!-- Tab panes -->
                <div class="tab-content pt-4 text-muted">
                    <div class="tab-pane active" id="overview-tab" role="tabpanel">
                        
                        <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Description</h5>
                                        <p>{{ $tender->description }}</p>
                                        
                                        <h5 class="card-title mb-3">Approval Stages</h5>
                                        <h6 class="fs-14 mb-1">Status
                                            @switch($tender->status)
                                                @case('1')
                                                    <span class="badge bg-soft-primary text-primary align-middle">Draft</span>
                                                    @break
                                                @case('2')
                                                    <span class="badge bg-soft-warning text-warning align-middle">Pending</span>
                                                    @break
                                                @case('3')
                                                    <span class="badge bg-soft-success text-success align-middle">Open</span>
                                                    @break
                                                @case('4')
                                                    <span class="badge bg-soft-danger text-danger align-middle">Rejected</span>
                                                    @break
                                                @case('10')
                                                    <span class="badge bg-soft-info text-info align-middle">Completed</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary text-secondary align-middle">Unknown</span>
                                            @endswitch
                                        </h6>
                                        <br>

                                        @if($tender->status != 1)
                                            @can('is_staff')
                                            <div id="approvals">
                                                <h6 class="fs-14 mb-1">Chief Pharamacist Approval Status
                                                    @switch($tender->chief_pharmacist_approval_status)
                                                        @case('1')
                                                            <span class="badge bg-soft-primary text-primary align-middle">Draft</span>
                                                            @break
                                                        @case('2')
                                                            <span class="badge bg-soft-warning text-warning align-middle">Pending</span>
                                                            @break
                                                        @case('3')
                                                            <span class="badge bg-soft-success text-success align-middle">Approved</span>
                                                            @break
                                                        @case('4')
                                                            <span class="badge bg-soft-danger text-danger align-middle">Rejected</span>
                                                            @break
                                                        @case('10')
                                                            <span class="badge bg-soft-info text-info align-middle">Completed</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary text-secondary align-middle">Unknown</span>
                                                    @endswitch
                                                </h6>
                                                <h6>Chief Pharamacist Approval Date : {{ $tender->chief_pharmacist_approval_date	 }}</h6>
                                                <p>Chief Pharamacist Comments : {{ $tender->chief_pharmacist_approval_comments }}</p>


                                            @can('is_chief_pharmacist')
                                                @if($tender->chief_pharmacist_approval_status == 2)
                                                <p>Please Reject or Approve this Tender</p>
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">Approve</button>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>
                                                <br>
                                                <br>
                                                @endif
                                            @endcan

                                                <h6 class="fs-14 mb-1">Chief Officer Approval Status
                                                    @switch($tender->chief_officer_approval_status)
                                                        @case('1')
                                                            <span class="badge bg-soft-primary text-primary align-middle">Draft</span>
                                                            @break
                                                        @case('2')
                                                            <span class="badge bg-soft-warning text-warning align-middle">Pending</span>
                                                            @break
                                                        @case('3')
                                                            <span class="badge bg-soft-success text-success align-middle">Approved</span>
                                                            @break
                                                        @case('4')
                                                            <span class="badge bg-soft-danger text-danger align-middle">Rejected</span>
                                                            @break
                                                        @case('10')
                                                            <span class="badge bg-soft-info text-info align-middle">Completed</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary text-secondary align-middle">Unknown</span>
                                                    @endswitch
                                                </h6>
                                                <h6>Chief Officer Approval Date : {{ $tender->chief_pharmacist_approval_date	 }}</h6>
                                                <p>Chief Officer Comments : {{ $tender->chief_pharmacist_approval_comments }}</p>

                                                @can('is_chief_officer')
                                                    @if($tender->chief_officer_approval_status == 2)
                                                    <p>Please Reject or Approve this Tender</p>
                                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">Approve</button>
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>
                                                    <br>
                                                    <br>
                                                    @endif
                                                @endcan
                                                

                                            </div>
                                            @endcan
                                        @else
                                            @can('add_tenders')
                                                @if($tender->created_by == Auth::user()->id)
                                                <p>Please Publish Your Request to Initiate Approval Process</p>
                                                <form class="needs-validation" novalidate method="POST" action="{{ route('tenders.update', ['tender' => $tender->id]) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT') <!-- Add this line to override the form method -->
                                                    <input type="text" hidden readonly required name="status" value="2">
                                                    <!-- Form contents go here -->
                                                    <button type="submit" class="btn btn-success"><i class="ri-edit-box-line align-bottom"></i> Publish</button>
                                                </form>
                                                <br>
                                                @endif
                                            @endcan
                                        @endif
    

                                        
                                        <p></p>
                                        @can('is_staff')
                                            <div class="row">
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary shadow">
                                                                <i class="ri-user-2-fill"></i>
                                                            </div>
                                                        </div>
                                                            <div class="flex-grow-1 overflow-hidden">
                                                                <p class="mb-1">Created By :</p>
                                                                <h6 class="text-truncate mb-0">{{ $tender->createdBy->descriptiom .' '.$tender->createdBy->last_name }}</h6>
                                                            </div>
                                                    </div>
                                                </div>
                                                <!--end col-->
                                            </div>
                                        @endcan
                                        <!--end row-->
                                    </div>
                                    <!--end card-body-->


                                    <div class="col-xl-4 col-md-6">
                                        <div id="approveModal" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 overflow-hidden">
                                                    <div class="modal-header p-3">
                                                        <h4 class="card-title mb-0">Approve Tender</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="alert alert-success  rounded-0 mb-0">
                                                        <p class="mb-0">Add approval comment if any before approving this Tender </p>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="needs-validation" novalidate method="POST" action="{{ route('tenders.update', ['tender' => $tender->id]) }}" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT') <!-- Add this line to override the form method -->
                                                            <input type="text" hidden readonly required name="decision" value="approved">
                                                            <input type="text" hidden readonly required name="approval_status" value="3">
                                                            @can('is_chief_officer')
                                                             <input type="text" hidden readonly required name="approver" value="co">
                                                            @endcan
                                                            @can('is_chief_pharmacist')
                                                             <input type="text" hidden readonly required name="approver" value="cp">
                                                            @endcan
                                                            <div class="col-md-12">
                                                                <label for="description" class="form-label">Aprroval Comment <span
                                                                        class="text-danger">*</span></label>
                                                                <textarea type="text" class="form-control @error('description') is-invalid @enderror"
                                                                    name="description" value="{{ old('description') }}" id="first_description"
                                                                    placeholder="Enter Description" required></textarea>
                                                                @error('description')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                                <div class="invalid-feedback">
                                                                    Please enter Aprroval Comment
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <br>
                                                            <!-- Form contents go here -->
                                                            <button type="submit" class="btn btn-success"><i class="ri-edit-box-line align-bottom"></i> Approve</button>
                                                        </form>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div><!-- /.modal -->
                                        <div id="rejectModal" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 overflow-hidden">
                                                    <div class="modal-header p-3">
                                                        <h4 class="card-title mb-0">Reject Tender </h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="alert alert-danger  rounded-0 mb-0">
                                                        <p class="mb-0">Add Rejection Reason if any before rejecting this Tender </p>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="needs-validation" novalidate method="POST" action="{{ route('tenders.update', ['tender' => $tender->id]) }}" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT') <!-- Add this line to override the form method -->
                                                            <input type="text" hidden readonly required name="decision" value="rejected">
                                                            <input type="text" hidden readonly required name="approval_status" value="4">
                                                            @can('is_chief_officer')
                                                             <input type="text" hidden readonly required name="approver" value="co">
                                                            @endcan
                                                            @can('is_chief_pharmacist')
                                                             <input type="text" hidden readonly required name="approver" value="cp">
                                                            @endcan
                                                            <div class="col-md-12">
                                                                <label for="description" class="form-label">Rejection Reason <span
                                                                        class="text-danger">*</span></label>
                                                                <textarea type="text" class="form-control @error('description') is-invalid @enderror"
                                                                    name="description" value="{{ old('description') }}" id="first_description"
                                                                    placeholder="Enter Description" required></textarea>
                                                                @error('description')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                                <div class="invalid-feedback">
                                                                    Please enter Rejection Reason
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <br>
                                                            <!-- Form contents go here -->
                                                            <button type="submit" class="btn btn-danger"><i class="ri-edit-box-line align-bottom"></i> Reject</button>
                                                        </form>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div><!-- /.modal -->
                                    </div><!-- end col -->
                        </div><!-- end card -->
                        @if($tender->status == 1)
                        @can('add_tenders')
                        <div class="card">
                            <div class="card-body" id="add_new_form">
                                <form class="needs-validation" novalidate method="POST" action="{{ route('tender_items.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-3 needs-validation">

                                        <input type="text" name="tender_id" value="{{$tender->id}}" hidden readonly readonly>
                                        <div class="col-md-4">
                                            <label for="item_category_id" class="form-label">Select Item Category <span class="text-danger">*</span></label>
                                            <select class=" form-control  is-invalid bg-light border-0  @error('item_category_id') @enderror" name="item_category_id" 
                                            name="item_category_id" value="{{ old('item_category_id') }}" id="item_category_id" >
                                                <option value="" selected disabled>Select</option>
                                                @foreach($item_categories as $item_category)
                                                <option value="{{$item_category->id}}" >{{$item_category->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('item_category_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>    
                                        <div class="col-md-4">
                                            <label for="item_id" class="form-label">Select Item <span class="text-danger">*</span></label>
                                            <select class=" form-control is-invalid bg-light border-0 js-example-basic-single @error('item_id') @enderror" name="item_id" 
                                            name="item_id" value="{{ old('item_id') }}" id="item_id" >
                                                <option value="" selected disabled>Select</option>
                                                
                                            </select>
                                            @error('item_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="descriptiom" class="form-label">Description <span
                                                    class="text-danger">*</span></label>
                                            <textarea type="text" class="form-control @error('descriptiom') is-invalid @enderror"
                                                name="descriptiom" value="{{ old('descriptiom') }}" id="descriptiom"
                                                placeholder="Enter Description" required></textarea>
                                            @error('descriptiom')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Please enter Description
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="terms_of_ref" class="form-label">Terms Of Reference <span
                                                    class="text-danger">*</span></label>
                                            <textarea type="text" class="form-control @error('terms_of_ref') is-invalid @enderror"
                                                name="terms_of_ref" value="{{ old('terms_of_ref') }}" id="terms_of_ref"
                                                placeholder="Enter Terms Of Reference" required></textarea>
                                            @error('terms_of_ref')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Please enter Terms Of Reference
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-12 p-3">
                                            <button href="" type="submit" id="add-item" class="btn btn-success" style="float: right;"><i class="ri-add-fill me-1 align-bottom"></i> Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endcan
                        @endif
                        
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                                <div class="card-body">
                                                    <div class="table-responsive">

                                                        
                                                        @if($tender->status == 3)
                                                        @if($application_status == 'pending')
                                                            @can('is_supplier')
                                                        <form class="needs-validation" novalidate method="POST" action="{{ route('tender_applications.store') }}" enctype="multipart/form-data">
                                                            <input type="number" name="company_id" value="{{$company_id}}" placeholder="{{$company_id}}" hidden readonly>
                                                            <input type="number" name="tender_id" value="{{$tender->id}}" placeholder="{{$tender->id}}"  hidden readonly>
                                                            @csrf
                                                        @endif
                                                        @endif
                                                        @endcan
                                                            <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        {{-- <td colspan="5">
                                                                            <a href="javascript:new_link()" id="add-item" class="btn btn-soft-secondary fw-medium"><i class="ri-add-fill me-1 align-bottom"></i> Add Item</a>
                                                                        </td> --}}

                                                                        @if($tender->status == 3)
                                                                            @can('is_supplier')
                                                                                <P>Please Enter Your quote for each Item and Submit to complete your application </P>
                                                                            @endif
                                                                        @endcan
                                                                    </tr>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Category</th>
                                                                        <th>Name</th>
                                                                        <th>Pack Size</th>
                                                                        <th>Description</th>
                                                                        <th>Terms Of Reference</th>
                                                                        @if($tender->status == 1)
                                                                            @can('add_tenders')
                                                                                <th>Action</th>
                                                                            @endcan
                                                                        @endif
                                                                        
                                                        
                                                                        @if($tender->status == 3)
                                                                            @if($application_status == 'pending')
                                                                                @can('is_supplier')
                                                                                    <th>Enter Your Quote (Ksh)</th>
                                                                                @endcan
                                                                            @endif
                                                                        @endif
                                                                       
                                                                        @if($tender->status == 3)
                                                                            @if($application_status == 'applied')
                                                                                @can('is_supplier')
                                                                                    <th>Quotes</th>
                                                                                @endcan
                                                                            @endif
                                                                        @endif
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($tender_items as $tender_item)
                                                                        <tr>
                                                                            <td>{{$loop->iteration}}</td>
                                                                            <td>{{$tender_item->itemCategory->name}}</td>
                                                                            <td>{{$tender_item->Item->name}}</td>
                                                                            <td>{{$tender_item->Item->pack_size}}</td>
                                                                            <td>{{$tender_item->description}}</td>
                                                                            <td>{{$tender_item->terms_of_reference}}</td>

                                                                            @if($tender->status == 1)
                                                                                @can('add_tenders')
                                                                                <td>
                                                                                    <form class="needs-validation" novalidate method="POST" action="{{ route('tender_items.destroy', ['tender_item' => $tender_item->id]) }}" enctype="multipart/form-data">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <!-- Form contents go here -->
                                                                                        <button type="submit" class="btn btn-soft-danger fw-medium">
                                                                                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                                                                        </button>
                                                                                    </form>
                                                                                </td>
                                                                                @endcan
                                                                            @endif
                                                        
                                                                            @if($tender->status == 3)
                                                                                @if($application_status == 'pending')
                                                                                    @can('is_supplier')
                                                                                        <td>
                                                                                            <input type="tender_item_id" name="tender_item_id[]" value="{{$tender_item->id}}" placeholder="{{$tender_item->id}}" hidden  readonly>
                                                                                            {{-- <input type="number" name="amount[]" placeholder="Enter your quote in Ksh "> --}}
                                                                                            <div class="col-md-12">
                                                                                                <input type="number" class="form-control @error('amount[]') is-invalid @enderror"
                                                                                                    name="amount[]" value="{{ old('amount[]') }}" id="amount[]"
                                                                                                    placeholder="Enter Quote Ksh" required>
                                                                                                @error('amount[]')
                                                                                                    <span class="invalid-feedback" role="alert">
                                                                                                        <strong>{{ $message }}</strong>
                                                                                                    </span>
                                                                                                @enderror
                                                                                                <div class="invalid-feedback">
                                                                                                    Please enter Amount for this Item
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                    @endcan
                                                                                @endif
                                                                            @endif
                                                                            
                                                                            @if($tender->status == 3)
                                                                                @if($application_status == 'applied')
                                                                                    @can('is_supplier')
                                                                                        <td>
                                                                                            @foreach($quotes as $quote)
                                                                                                @if($quote->tender_item_id == $tender_item->id)
                                                                                                    Ksh {{$quote->quotation}}
                                                                                                @endif
                                                                                            @endforeach
                                                                                        </td>
                                                                                    @endcan
                                                                                @endif
                                                                            @endif
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                                @if($tender->status == 3)
                                                                @if($application_status == 'pending')
                                                                @can('is_supplier')
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="7">
                                                                            <div class="flex-shrink-0">
                                                                                <button type="submit" class="btn btn-success "><i class="ri-edit-box-line align-bottom"></i> Submit QUOTE</button>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                                @endif
                                                                @endif
                                                                @endcan
                                                            </table>

                                                        @if($tender->status == 3)
                                                        @if($application_status == 'pending')
                                                        @can('is_supplier')
                                                        </form>
                                                        @endif
                                                        @endif
                                                        @endcan
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end card -->
                                </div><!-- end col -->
                            </div>
                        </div>

                                
                    </div><!-- end row -->
                </div><!-- end card -->
                        <!--end row-->
            </div>

                    <!--end tab-pane-->
                </div>
                <!--end tab-content-->
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection
@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get references to the select elements
        var itemCategorySelect = document.getElementById("item_category_id");
        var itemSelect = document.getElementById("item_id");

        // Add event listener to item category select
        itemCategorySelect.addEventListener("change", function () {
            // Get the selected item category ID
            var selectedCategoryId = itemCategorySelect.value;

            // Clear the current options in the item select
            itemSelect.innerHTML = '<option value="" selected disabled>Select</option>';

            // Fetch items based on the selected category
            fetch('/api/api/items/' + selectedCategoryId)
                .then(response => response.json())
                .then(data => {
                    // Populate the item select with the fetched items
                    data.forEach(item => {
                        var option = document.createElement("option");
                        option.value = item.id;
                        option.textContent = item.name;
                        itemSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        });
    });
    






</script>


    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/tenderitemcreate.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/profile.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <!--jquery cdn-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!--select2 cdn-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>

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
@endsection
