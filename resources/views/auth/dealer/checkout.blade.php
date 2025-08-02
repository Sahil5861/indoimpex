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
                            Dashboard - <span class="fw-normal">Dealer Plans</span>
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
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Plans</h5>
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <?php 
                                $plan_doller = number_format($plan->price / 82, 2);
                                $specila_price_doller = number_format($plan->special_price / 82, 2);

                                $currentDate = now(); // Current date
                                $expiryDate = $plan->expiry_date; // Expiry date from the model

                            ?>                            
                            <div class="row p-2">
                                <h3>{{$plan->name}}</h3>
                                <hr>
                                <div class="col-lg-5">
                                    <h5>Price : ${{$plan_doller}} ({{plan->price}})</h5>
                                    <h5 class="text-danger">Offer Price : ${{$specila_price_doller}} ({{$plan->special_price}})</h5>
                                </div>
                                <div class="col-lg-2 d-flex align-items-center justify-content-center">
                                    <div class="border border-light" style="height: 100%; width: 1px;"></div>
                                </div>
                                <div class="col-lg-5">
                                    <h5>Start Date: {{ now()->format('d-m-Y') }}</h5>
                                    @if ($currentDate->greaterThan($expiryDate))
                                    <h5 class="text-danger" style="text-decoration: line-through;">Expired</h5>
                                    @else
                                        <h5 class="text-danger">Expiry Date: {{ $expiryDate->format('d-m-Y') }}</h5>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <form action="{{route('dealer.payment.process')}}" method="POST">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <input type="hidden" name="amount" value="{{ $plan->price * 100 }}">
                            <button type="submit" id="pay-button" class="btn btn-success btn-block">Process Payment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection