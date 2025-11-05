@extends('layouts.master-without-nav')
@section('title')
@lang('translation.signup')
@endsection
@section('content')

<div class="auth-page-wrapper pt-5">
    <!-- auth page bg -->
    <!-- <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div> -->

    <!-- auth page content -->
    <div class="auth-page-content">
        <div class="container">
            {{-- <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-black-50">
                            <div>
                                <a href="index" class="d-inline-block auth-logo">
                                    <img src="{{ URL::asset('build/images/murangalogo.png') }}" alt="" height="120">
                                </a>
                            </div>
                            <p class="mt-3 fs-15 fw-medium">Murang'a County Government E-Procurement </p>
                        </div>
                    </div>
                </div> --}}
            <!-- end row -->

            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-20 col-xl-10">
                    <div class="card mt-4">

                        <div class="card-body p-4">
                            <div class="p-2 mt-4">

                                <form class="needs-validation" novalidate method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                                    @csrf


                                    <br>
                                    <div class="signin-other-title text-center">

                                        <h5 class="fs-13 mb-4 title text-muted">Register</h5>

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


{{-- 
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
                                        </div> --}}

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
                                                <option value="6" selected @readonly(true)>Agent</option>
                                                {{-- @foreach($roles as $role)
                                                <option value="{{$role->id}}">{{$role->name}}</option>
                                                @endforeach --}}

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
                                        <div class="col-md-4">
                                            <label for="id_no" class="form-label">Password<span class="text-danger">*</span></label>
                                            <input type="password" class="form-control @error('id_no') is-invalid @enderror" name="id_no" value="{{ old('id_no') }}" id="id_no" placeholder="Enter Password" required>
                                            @error('id_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                            <div id="password-error-message" class="text-danger"></div>
                                            <div class="invalid-feedback">
                                                Please Enter Password
                                            </div>
                                        </div>

                                        {{-- <input type="text" hidden name="type" value="{{ $application_type }}"> --}}
                                    </div>



                                    <div class="mt-4">
                                        <button class="btn btn-success w-25" id="submit" type="submit" data-loading-text="Signing Up..." data-disable-with="Signing Up...">Sign Up</button>
                                    </div>


                                </form>



                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->

                    <div class="mt-4 text-center">
                        <p class="mb-0">Already have an account ? <a href="auth-signin-basic" class="fw-semibold text-primary text-decoration-underline"> Signin </a> </p>
                    </div>

                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end auth page content -->

    <!-- footer -->
    <!-- <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> Velzon. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand</p>
                    </div>
                </div>
            </div>
        </div>
    </footer> -->
    <!-- end Footer -->
</div>
<!-- end auth-page-wrapper -->



@endsection


</script>
@section('script')
<script src="{{ URL::asset('build/libs/particles.js/particles.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/particles.app.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/form-validation.init.js') }}"></script>
@endsection