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
                <h4 class="card-title mb-0 flex-grow-1">New Form</h4>
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

                    <form class="needs-validation" novalidate method="POST" action="{{ route('farmers_data.store') }}" enctype="multipart/form-data">
                        @csrf

                        <br>
                        <div class="signin-other-title text-center">

                            <h5 class="fs-13 mb-4 title text-muted">Record Famers</h5>

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
                                <input type="number" class="form-control @error('phone_no') is-invalid @enderror" name="phone_no" value="{{ old('phone_no') }}" id="phone_no" placeholder="Enter Phone No Format:  711111111 " required>
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
                                <label for="d_0_b" class="form-label">Date Of Birth<span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('d_0_b') is-invalid @enderror " name="d_0_b" value="{{ old('d_0_b') }}" id="d_0_b" placeholder="Select Birth Date" required>
                                @error('d_0_b')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div id="id-no-error-message" class="text-danger"></div>
                                <div class="invalid-feedback">
                                    Please Select Birth Date
                                </div>
                            </div>
                            
                            <div class="col-lg-4">
                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-control  @error('gender')  is-invalid @enderror" name="gender" value="{{ old('gender') }}" id="gender" required>
                                    <option selected disabled value="">Select</option>
                                    <option  value="M">Male</option>
                                    <option  value="F">Female</option>
                                    
                                </select>
                                @error('gender')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="invalid-feedback">
                                    Please Gender
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

                            <div class="col-lg-4">
                                <label for="registration_centre_id" class="form-label">Select Registration Centre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="registration_centre_id" value="{{ old('registration_centre_id') }}" id="registration_centre_id" placeholder="Enter Registration center" required oninput="this.value = this.value.toUpperCase();">

                                @error('registration_centre_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="invalid-feedback">
                                    Please select Registration Centre
                                </div>
                            </div>


                            <div class="col-lg-4">
                                <label for="value_chain" class="form-label">Select Value chain <span class="text-danger">*</span></label>
                                <select class="js-example-basic-multiple form-control @error('value_chain[]') is-invalid @enderror" name="value_chain[]" id="value_chain" multiple="multiple" required>
                                  
                                    <option selected disabled value="">Select</option>
                                    <option value="Milk">Milk</option>
                                    <option value="Mango">Mango</option>
                                </select>
                                @error('value_chain[]')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="invalid-feedback">
                                    Please select Value chain
                                </div>
                            </div>
                            
                            <div class="col-lg-4" id="aggregatorField" style="display: block;">
                                <label for="aggregator" class="form-label">Select Aggregator <span class="text-danger">*</span></label>
                                    <select class="js-example-basic-multiple form-control @error('aggregator[]') is-invalid @enderror" name="aggregator[]" id="aggregator" multiple="multiple" required>
                                    <option selected disabled value="">Select</option>
                                    @foreach($aggregators as $aggregator)
                                        <option value="{{ $aggregator->id }}">{{ $aggregator->name }}</option>
                                    @endforeach
                                </select>
                                @error('aggregator[]')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="invalid-feedback">
                                    Please select Aggregator
                                </div>
                            </div>
                            
                            

                            {{-- <div class="col-md-4">
                                <label for="aggregator" class="form-label">Aggregator<span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('aggregator') is-invalid @enderror" name="aggregator" value="{{ old('aggregator') }}" id="aggregator" placeholder="Enter Aggregator" required oninput="this.value = this.value.toUpperCase();">
                                @error('aggregator')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="invalid-feedback">
                                    Please enter Aggregator
                                </div>
                            </div> --}}

                          


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
    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOM loaded'); // Log message to confirm DOM is loaded
        var valueChainSelect = document.getElementById('value_chain');
        var aggregatorField = document.getElementById('aggregatorField');

        console.log('Value chain select:', valueChainSelect); // Log the value chain select element
        
        valueChainSelect.addEventListener('change', function () {
            console.log('Change event triggered'); // Log message when change event is triggered
            console.log('Selected value:', valueChainSelect.value); // Log the selected value
            if (valueChainSelect.value === 'Milk') {
                console.log('Milk selected'); // Log a message if Milk is selected
                aggregatorField.style.display = 'block';
            } else {
                console.log('Milk not selected'); // Log a message if Milk is not selected
                aggregatorField.style.display = 'none';
            }
        });
    });
</script>


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