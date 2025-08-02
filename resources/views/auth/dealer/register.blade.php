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
                <form class="login-form w-75" action="{{ route('dealer.register.post') }}" method="POST">
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
                                        <label class="form-label" for="contact_name">Name</label>
                                        <div class="form-control-feedback form-control-feedback-start">
                                            <input type="hidden" name="role_id" value="3">
                                            <input type="text" class="form-control" name="contact_name" placeholder="Your Name..." autofocus required value="{{old('contact_name')}}">
                                            <div class="form-control-feedback-icon">
                                                <i class="ph-user-circle text-muted"></i>
                                            </div>
                                            @error('contact_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="contact_phone">Phone No.</label>
                                        <div class="form-control-feedback form-control-feedback-start">
                                            <input type="tel" class="form-control" name="contact_phone" placeholder="Your Number..." required value="{{old('contact_phone')}}">
                                            <div class="form-control-feedback-icon">
                                                <i class="ph-phone text-muted"></i>
                                            </div>
                                            @error('contact_phone')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                    <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="contact_email">Your email</label>
                                        <div class="form-control-feedback form-control-feedback-start">
                                            <input type="email" class="form-control" name="contact_email" placeholder="Your Email..." required value="{{old('contact_email')}}">
                                            <div class="form-control-feedback-icon">
                                                <i class="ph-at text-muted"></i>
                                            </div>
                                            @error('contact_email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Password</label>
                                                <div class="form-control-feedback form-control-feedback-start">
                                                    <input type="password" class="form-control" name="password" placeholder="Your Password..." required>
                                                    <div class="form-control-feedback-icon">
                                                        <i class="ph-lock text-muted"></i>
                                                    </div>
                                                    @error('password')
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
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="name">Business Name</label>
                                        <input type="text" id="name" name="name" class="form-control text-white" placeholder="Enter Dealer's Name" value="{{old('name')}}" required>
                                        <span>
                                            @error('name')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </span>                                        
                                    </div>
                                </div>
                                <div class="col-lg-4">        
                                    <div class="mb-3">
                                        <label for="name">Business Email</label>
                                        <input type="email" id="email" name="email" class="form-control  text-white" placeholder="Enter Dealer's Email"  value="{{old('email')}}"required>
                                        
                                        <span>
                                            @error('email')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="name">Business Phone</label>
                                        <input type="text" id="phone" name="phone" class="form-control text-white" placeholder="Enter Dealer's Phone Number"  value="{{old('phone')}}" required>
                                        <span>
                                            @error('phone')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </span>                                
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="country">Country</label>
                                        <select id="country" name="country" class="form-control text-white">
                                            <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->name }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                        <span>
                                            @error('country')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </span>                                                                
                                    </div>
                                </div>
                                <div class="col-lg-4">    
                                    <div class="mb-3">
                                        <label for="name">State</label>
                                        <select id="state" name="state" class="form-control text-white">
                                            <option value="">Select State</option>
                                        </select>
                                        <span>
                                            @error('state')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </span>                                
                                    </div>
                                </div>
                                <div class="col-lg-4">        
                                    <div class="mb-3">
                                        <label for="name">City</label>
                                        <select id="city" name="city" class="form-control text-white">
                                            <option value="">Select City</option>
                                        </select>
                                        <span>
                                            @error('city')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </span>                        
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="authenticated">Is Authenticated</label>
                                        <select name="authenticated" id="authenticated" class="form-control text-white" required>
                                            <option value="1"
                                            @if(old('authenticated') == 1)
                                                selected
                                            @endif>Yes</option>
                                        <option value="0"
                                            @if(old('authenticated') == 0)
                                                selected
                                            @endif>No</option>                                
                                        </select>
                                        <span>
                                            @error('authenticated')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </span>  
                                    </div>
                                </div>
                                <div class="col-lg-4">  
                                    <div class="mb-3"style="display: none" id="gst_number_group">
                                        <label for="gst_no">GSTI Number</label>
                                        <input type="text" id="gst_no" name="gst_no" class="form-control" {{old('gst_no')}} placeholder="Enter GST number" >
                                        <span>
                                            @error('gst_no')
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
<script>
    $(document).ready(function() {
        $('#country').change(function () {
            var countryId = $(this).val();
            $('#state').html('<option value="">Select State</option>');
            $('#city').html('<option value="">Select City</option>');
            console.log(countryId);
            
            if (countryId) {
                $.ajax({
                    url: '/states/' + countryId,
                    type: 'GET',
                    success: function (data) {
                        $.each(data, function (key, value) {
                            $('#state').append('<option value="'+ value.name +'">' + value.name + '</option>');
                        });
                    }
                });
            }
        });

        $('#state').change(function () {
            var stateId  = $(this).val();
            $('#city').html('<option value="">Select City</option>');
            console.log(stateId);
            
            if (stateId) {
                $.ajax({
                    url: '/cities/' + stateId,
                    type: 'GET',
                    success: function (data) {
                        $.each(data, function (key, value) {
                            $('#city').append('<option value="'+ value.name +'">' + value.name + '</option>');
                        });
                    }
                });
            }
        });

        // Toggle GST Field
        function toggleGSTField() {
            var isAuthenticated = $('#authenticated').val();
            if (isAuthenticated == '1') {
                $('#gst_number_group').show();
            } else {
                $('#gst_number_group').hide();
            }
        }

        // Initial check on page load
        toggleGSTField();

        // Event listener for change in select box
        $('#authenticated').change(function() {
            toggleGSTField();
        }); 
    });
</script>

@endsection
