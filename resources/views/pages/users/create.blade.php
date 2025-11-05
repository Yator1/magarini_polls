@extends('layouts.master')
@section('title')
@lang('translation.validation')
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
                <h4 class="card-title mb-0 flex-grow-1">Registrations Form</h4>
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

                    <form class="needs-validation" novalidate method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                        @csrf


                        <br>
                        <div class="signin-other-title text-center">

                            <h5 class="fs-13 mb-4 title text-muted">Add User</h5>

                        </div>
                        <br>

                        <div class="row g-3 needs-validation">

                            <div class="col-md-4">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" id="first_first_name" placeholder="Enter First Name" required>
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
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" id="first_last_name" placeholder="Enter Last Name" required>
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
                                <label for="id_no" class="form-label">ID Number <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('id_no') is-invalid @enderror" name="id_no" value="{{ old('id_no') }}" id="id_no" placeholder="Enter Id Number" required>
                                @error('id_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div id="id-no-error-message" class="text-danger"></div>
                                <div class="invalid-feedback">
                                    Please enter Valid National Id Number
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
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" id="email" placeholder="Enter email address" required>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                                <div id="email-error-message" class="text-danger"></div>
                                <div class="invalid-feedback">
                                    Please enter UserEmail
                                </div>
                            </div>

                            <div class="col-lg-4" id="roleDiv">
                                <label for="role" class="form-label">Select Role <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-control @error('role') is-invalid @enderror" name="role" value="{{ old('role') }}" id="role" required>
                                    <option value="" selected disabled>Select</option>
                                    @foreach($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach

                                </select>
                                @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="invalid-feedback">
                                    Please select Role
                                </div>

                            </div>

                            <input type="text" hidden name="type" value="{{ $application_type }}">
                        </div>



                        <div class="mt-4">
                            <button class="btn btn-success w-25" id="submit" type="submit" data-loading-text="Signing Up..." data-disable-with="Signing Up...">Sign Up</button>
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

        // Check if user is already in the system
        $('#id_no, #phone_no, #useremail').on('input', function() {
            var idNo = $('#id_no').val();
            var phoneNo = $('#phone_no').val();
            var useremail = $('#useremail').val(); // Corrected variable name

            console.log('ID Number:', idNo);
            console.log('Phone Number:', phoneNo);
            console.log('User Email:', useremail);
            // Make an AJAX request to check if the data already exists in the database
            $.ajax({
                method: 'POST',
                url: "{{ route('checkExisting') }}",
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the headers
                },
                data: {
                    id_no: idNo,
                    phone_no: phoneNo,
                    useremail: useremail
                },
                success: function(data) {
                    console.log(data); // Log the response data

                    var idNoErrorMessage = '';
                    var phoneNoErrorMessage = '';
                    var emailErrorMessage = '';

                    if (data.id_no_exists) {
                        idNoErrorMessage += 'ID Number is already linked to another user. ';
                    }

                    if (data.phone_no_exists) {
                        phoneNoErrorMessage += 'Phone Number is already linked to another user. ';
                    }

                    if (data.email_exists) {
                        emailErrorMessage += 'Email is already linked to another user.';
                    }

                    $('#id-no-error-message').text(idNoErrorMessage);
                    $('#phone-no-error-message').text(phoneNoErrorMessage);
                    //$('#email-error-message').text(emailErrorMessage);

                    // Disable the submit button if any validation errors exist
                    // if (idNoErrorMessage || phoneNoErrorMessage || emailErrorMessage) {
                    //     $('#submit').prop('disabled', true);
                    // } else {
                    //     $('#submit').prop('disabled', false);
                    // }


                    // Reset the button state to normal and trigger form submission or page refresh
                    $('#submit').button('reset');
                }
            });

        });



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