@extends('layout.base')

@section('content')

<!-- Page content -->
<div class="page-content">

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Inner content -->
        <div class="content-inner">

            <!-- Content area -->
            <div class="content d-flex justify-content-start align-items-center py-5" style="flex-direction: column;">
                <!-- Verify Email -->
                <h1 class="text-white text-center">{{env('COMPANY_NAME')}}</h1>
                <div class="container d-flex justify-content-start  w-50 py-3" style="flex-direction: column;">
                    <div class="container-fluid w-100 bg-primary p-3">
                        <h2 class="text-dark text-center">Thanks for Registration !</h2>
                        <h1 class="text-center">Verificay Your Email Address</h1>
                    </div>
                        <form class="bg-white p-3" {{route('dealer.email.verify.post', $user->id)}} method="post">
                            @csrf
                            <h3 class="text-light">Hello {{$user->name}}</h3>
                            @php
                                $email = $user->email;
                                $emailParts = explode('@', $email);
                                $username = substr($emailParts[0], 0, 4); // First 3 characters
                                $domain = substr($emailParts[1], -4); // Last 4 characters (.com, .org, etc.)
                            @endphp
                            <p class="text-light">Enter the OTP Send On your Email: {{ $username }}...{{ $domain }}</p>
                            <div class="form-group">
                                <input type="text" id="otp" name="otp" class="form-control text-white p-3" placeholder="Enter OTP" required autofocus>
                                <span>
                                    @error('otp')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </span>
                            </div>
                            <button type="submit" class="btn btn-success px-5 btn-lg">Verify</button>
                        </form>
                </div>
                
                <!-- /Verify Email -->

            </div>
            <!-- /content area -->

        </div>
        <!-- /inner content -->

    </div>
    <!-- /main content -->

</div>
<!-- /page content -->
@endsection




    
    

