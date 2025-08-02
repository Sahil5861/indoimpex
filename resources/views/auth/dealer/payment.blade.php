@extends('layout.base')
<style>
    .bg-darkcyan{
        background-color: darkcyan;
    }
</style>
@section('content')
<div class="page-content">
    @include('layouts.sidebar')
    <div class="content-wrapper">
        <div class="content-inner">
            <div class="page-header page-header-light shadow">
                <div class="page-header-content d-lg-flex">
                    <div class="d-flex">
                        <h4 class="page-title mb-0">
                            Dashboard - <span class="fw-normal">Paymnet</span>
                        </h4>
                        <a href="#page_header"
                            class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto"
                            data-bs-toggle="collapse">
                            <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="content">
                <h2>Payment for {{ $plan->name }}</h2>
                <p>Price: {{ $plan->price }}</p>
                
                <form action="{{route('dealer.payment.success')}}" method="POST">
                    @csrf
                    {{-- <button id="rzp-button1" class="btn btn-success btn-block text-white">Pay With RazorPay</button> --}}
                
                    <script src="https://checkout.razorpay.com/v1/checkout.js"
                        data-key="{{ env('RAZORPAY_KEY') }}"
                        data-amount="{{ $plan->price * 100 }}"
                        data-currency="INR"
                        data-order_id="{{ $orderId }}"
                        data-name="{{ env('COMPANY_NAME') }}"
                        data-description="Payment for {{ $plan->name }}"
                        data-image="{{ asset('your-logo.png') }}"
                        data-prefill.name="{{ $dealer->name }}"
                        data-prefill.email="{{ $dealer->email }}"
                        data-theme.color="#2a3aef">
                    </script>
                    
                    <script>
                        // Select the input element with the class 'razorpay-payment-button'
                        var razorpayButton = document.querySelector('.razorpay-payment-button');
                        
                        // Add the desired classes to the input element
                        razorpayButton.classList.add('btn', 'btn-success', 'btn-block, w-50');
                        razorpayButton.value = 'Pay with RazorPay';
                    </script>
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <input type="hidden" name="amount" value="{{ $plan->price * 100 }}">
                </form>
                                
            </div>
        </div>
    </div>
</div>


@endsection




