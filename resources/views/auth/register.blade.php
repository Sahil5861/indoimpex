@extends('layout.base')

@section('content')

<!-- Page content -->
<div class="page-content">
    <!-- Main content -->
    <div class="content-wrapper">
        <!-- Inner content -->
        <div class="content-inner">
            <!-- Content area -->
            <div class="content d-flex justify-content-center align-items-center">
                <!-- Registration form -->
                <form class="login-form w-50" action="{{ route('dealer.register.post') }}" method="POST">
                    @csrf <!-- CSRF Token -->
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                                    <img src="{{ asset('assets/images/logo_icon.svg') }}" class="h-48px" alt="">
                                </div>
                                <h5 class="mb-0">Register Here</h5>
                            </div>
                            <h5>Contact Information</h5>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <div class="form-control-feedback form-control-feedback-start">
                                            <input type="text" class="form-control" name="name" placeholder="Your Name..." autofocus>
                                            <div class="form-control-feedback-icon">
                                                <i class="ph-user-circle text-muted"></i>
                                            </div>
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Phone No.</label>
                                        <div class="form-control-feedback form-control-feedback-start">
                                            <input type="tel" class="form-control" name="phone" placeholder="Your Number...">
                                            <div class="form-control-feedback-icon">
                                                <i class="ph-phone text-muted"></i>
                                            </div>
                                            @error('phone')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                    <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Your email</label>
                                        <div class="form-control-feedback form-control-feedback-start">
                                            <input type="email" class="form-control" name="email" placeholder="Your Email...">
                                            <div class="form-control-feedback-icon">
                                                <i class="ph-at text-muted"></i>
                                            </div>
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Password</label>
                                                <div class="form-control-feedback form-control-feedback-start">
                                                    <input type="password" class="form-control" name="password"
                                                        placeholder="Your Password...">
                                                    <div class="form-control-feedback-icon">
                                                        <i class="ph-lock text-muted"></i>
                                                    </div>
                                                    @error('password')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Confirm Password</label>
                                                <div class="form-control-feedback form-control-feedback-start">
                                                    <input type="password" class="form-control" name="password_confirmation"
                                                        placeholder="Confirm Password...">
                                                    <div class="form-control-feedback-icon">
                                                        <i class="ph-lock text-muted"></i>
                                                    </div>
                                                    @error('password_confirmation')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
        
                                    
                                </div>
                            </div>
                            <h5>Business Information</h5>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="name">Dealer Name</label>
                                        <input type="text" id="name" name="name" class="form-control text-white" placeholder="Enter Dealer's Name" value="{{old('name')}}" >
                                        <span>
                                            @error('name')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </span>                                        
                                    </div>
                                    <div class="mb-3">
                                        <label for="name">Dealer Email</label>
                                        <input type="email" id="email" name="email" class="form-control  text-white" placeholder="Enter Dealer's Email"  value="{{old('email')}}">
                                        <span>
                                            @error('email')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name">Dealer Phone</label>
                                        <input type="text" id="phone" name="phone" class="form-control text-white" placeholder="Enter Dealer's Phone Number"  value="{{old('phone')}}" >
                                        <span>
                                            @error('phone')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </span>                                
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>

                        </div>
                    </div>
                </form>
                <!-- /registration form -->
            </div>
            <!-- /content area -->
        </div>
        <!-- /inner content -->
    </div>
    <!-- /main content -->
</div>
<!-- /page content -->

@endsection
