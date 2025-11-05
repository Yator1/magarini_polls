@extends('layouts.master')
@section('title')
@lang('translation.Mango Production')
@endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')

<!-- end row -->

<div class="row">
    <div class="col-lg-12 ">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Mango Production Form</h4>
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

                <div class="flex-shrink-0">

                </div>
            </div><!-- end card header -->

            <div class="card-body">
                <div class="live-preview">

                    <form class="needs-validation" novalidate method="POST" action="{{ route('mango_production.store') }}" enctype="multipart/form-data">
                        @csrf

                        <br>
                        <div class="signin-other-title text-center">

                            <h5 class="fs-13 mb-4 title text-muted">Record Mango Farmers Harvests</h5>

                        </div>
                        <br>

                        <div class="row g-3 needs-validation">

                            <div class="col-md-4">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" id="first_first_name" placeholder="Enter First Name" required oninput="this.value = this.value.toUpperCase();">
                                @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="invalid-feedback">
                                    Please enter First Name
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" id="first_last_name" placeholder="Enter Last Name" required oninput="this.value = this.value.toUpperCase();">
                                @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="invalid-feedback">
                                    Please enter Last Name
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="phone_no" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('phone_no') is-invalid @enderror" name="phone_no" value="{{ old('phone_no') }}" id="phone_no" placeholder="Enter Phone Number" required>
                                @error('phone_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                                <div id="phone-no-error-message" class="text-danger"></div>
                                <div class="invalid-feedback">
                                    Please enter Phone Number
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="mango_kgs" class="form-label">Production(Kgs) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('mango_kgs') is-invalid @enderror" name="mango_kgs" value="{{ old('mango_kgs') }}" id="mango_kgs" placeholder="Enter mangoes kgs" required>
                                @error('mango_kgs')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                                <div id="email-error-message" class="text-danger"></div>
                                <div class="invalid-feedback">
                                    Please enter Kgs
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label for="sub_county" class="form-label">Select Sub County <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-control  @error('sub_county')  is-invalid @enderror" name="sub_county" value="{{ old('sub_county') }}" id="sub_county" required>
                                    <option selected value="">Select</option>
                                    @foreach($subCounties as $subCounty)
                                    <option value="{{ $subCounty->id }}">{{ $subCounty->name }}</option>
                                    @endforeach
                                </select>
                                @error('sub_county')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="invalid-feedback">
                                    Please select Sub County
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label for="ward" class="form-label">Select Ward <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-control @error('ward') is-invalid @enderror" name="ward" id="ward" required>
                                    <option selected value="">Select</option>
                                </select>
                                @error('ward')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="invalid-feedback">
                                    Please select Ward
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="id_no" class="form-label">Id No <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('id_no') is-invalid @enderror" name="id_no" value="{{ old('id_no') }}" id="id_no" placeholder="Enter Id No" required >
                                @error('id_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="invalid-feedback">
                                    Please enter Id Number
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="lmfcs_no" class="form-label">LMFCS No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('lmfcs_no') is-invalid @enderror" name="lmfcs_no" value="{{ old('lmfcs_no') }}" id="lmfcs_no" placeholder="Enter LMFCS No" required oninput="this.value = this.value.toUpperCase();">
                                @error('lmfcs_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="invalid-feedback">
                                    Please enter LMFCS No
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="vehicle_no" class="form-label">Vehicle Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('vehicle_no') is-invalid @enderror" name="vehicle_no" value="{{ old('vehicle_no') }}" id="vehicle_no" placeholder="Enter Vehicle Number" required oninput="this.value = this.value.toUpperCase();">
                                @error('vehicle_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                                <div id="phone-no-error-message" class="text-danger"></div>
                                <div class="invalid-feedback">
                                    Please enter Vehicle Number
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label for="payment_mode" class="form-label">Select Payment Mode <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-control  @error('payment_mode')  is-invalid @enderror" name="payment_mode" value="{{ old('payment_mode') }}" id="payment_mode" required>
                                    <option selected value="">Select</option>
                                    <option value="Bank">Bank</option>
                                    <option value="Mpesa">Mpesa</option>
                                </select>
                                @error('payment_mode')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="invalid-feedback">
                                    Please select Payment Mode
                                </div>
                            </div>

                            <div class="col-md-4" id="bank_name_div">
                                <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('bank_name') is-invalid @enderror" name="bank_name" value="{{ old('bank_name') }}" id="bank_name" placeholder="Enter Bank Name" required >
                                @error('bank_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="invalid-feedback">
                                    Please enter Bank Name
                                </div>
                            </div>

                            <div class="col-md-4" id="bank_branch_div">
                                <label for="bank_branch" class="form-label">Bank Branch <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('bank_branch') is-invalid @enderror" name="bank_branch" value="{{ old('bank_branch') }}" id="bank_branch" placeholder="Enter Bank Branch" required >
                                @error('bank_branch')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                                <div id="phone-no-error-message" class="text-danger"></div>
                                <div class="invalid-feedback">
                                    Please enter Bank Branch
                                </div>
                            </div>

                             <div class="col-lg-4">
                                <label for="polling_station" class="form-label">Select Polling Station <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-control  @error('polling_station')  is-invalid @enderror" name="polling_station" value="{{ old('polling_station') }}" id="polling_station" required>
                                    <option selected value="">Select</option>
                                    @foreach($polling_stations as $polling_station)
                                    <option value="{{ $polling_station->id }}">{{ $polling_station->name }}</option>
                                    @endforeach
                                </select>
                                @error('polling_station')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="invalid-feedback">
                                    Please select Polling Station
                                </div>
                            </div> 

                            <div class="col-md-4">
                                <label for="weighing_date" class="form-label">Date Of Weighing<span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('weighing_date') is-invalid @enderror " name="weighing_date" value="{{ old('weighing_date') }}" id="weighing_date" placeholder="Select Weighing Date" required>
                                @error('weighing_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div id="id-no-error-message" class="text-danger"></div>
                                <div class="invalid-feedback">
                                    Please Select Weighing Date
                                </div>
                            </div>

                        </div>

                        <div class="mt-4">
                            <button class="btn btn-success w-25" id="submit" type="submit" data-loading-text="Saving Data..." data-disable-with="Saving Data...">Save</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


@endsection
@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Get the CSRF token value from the meta tag
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        //select ward
        // Add event listener to sub-county select input
        $('#sub_county').change(function() {

            var subCountyId = $(this).val();
            // console.log('Selected Sub-County ID:', subCountyId);

            // Make an AJAX request to get wards for the selected sub-county
            $.ajax({
                url: '/get-wards/' + subCountyId,
                method: 'GET',
                success: function(data) {
                    // Update ward select input options
                    var wardSelect = $('#ward');
                    wardSelect.empty();
                    wardSelect.append($('<option>', {
                        value: '',
                        text: 'Select'
                    }));
                    $.each(data, function(index, ward) {
                        wardSelect.append($('<option>', {
                            value: ward.id,
                            text: ward.name
                        }));
                    });
                }
            });
        });

    });

    
    $(document).ready(function() {
    // Add event listener to payment_mode select input
    $('#payment_mode').change(function() {
        // Get the selected payment mode
        var selectedPaymentMode = $(this).val();

        // Check if the selected payment mode is bank that should display the fields
        if (selectedPaymentMode === 'Bank') {
            // Display the bank_name_div and bank_branch_div fields
            document.getElementById('bank_name_div').style.display = 'block';
            document.getElementById('bank_branch_div').style.display = 'block';
        } else {
            // Hide the bank_name_div and bank_branch_div fields for other payment modes
            document.getElementById('bank_name_div').style.display = 'none';
            document.getElementById('bank_branch_div').style.display = 'none';
        }
    });
});

</script>
<script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/form-validation.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<!--jquery cdn-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<!--select2 cdn-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>
@endsection