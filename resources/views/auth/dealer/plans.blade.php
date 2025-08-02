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
            <?php 
                $colors = ['primary', 'secondary', 'teal', 'warning']
            ?>
            <div class="container w-75 m-1">
                <div class="d-flex justify-content-start align-items-center p-1" style="flex-direction: column;">
                    <h1 class="text-white text-center">{{env('COMPANY_NAME')}}</h1>
                    <p class="text-light">Pricing Plan</p>
                    <h2>Simple Prizes Flexible Options & Nothing Hidden</h2>
                </div>
                <div class="row">
                    @foreach ($plans as $i => $plan)
                    <div class="col-lg-4 col-sm-12  my-2">
                            <div class="col-sm-12 py-4 ">
                                <div class="card bg-{{$colors[$i]}}" style="display: flex; justify-content: center;align-items: center; flex-direction:column; gap:1rem;">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>{{$plan->name}}</h2>
                                        </div>
                                    </div>
                                    <?php 
                                        $plan_doller = number_format($plan->price / 82, 2);
                                    ?>
                                    <div class="card-body">
                                        <h2>${{$plan_doller}}</h2>
                                        <li class="text-black" style="list-style: none;"><i class="ph ph-check"></i> Feature 1</li>
                                        <li class="text-black" style="list-style: none;"><i class="ph ph-check"></i> Feature 2</li>
                                        <li class="text-black" style="list-style: none;"><i class="ph ph-check"></i> Feature 3</li>
                                        <li class="text-black" style="list-style: none;"><i class="ph ph-check"></i> Feature 4</li>
                                    </div>
                                    <div class="card-footer ">
                                        <a href="{{route('dealer.checkout',$plan->id)}}" class="text-white text-center px-3 py-2" style="text-decoration: none; font-size:1.5rem;">Get Plan</a>
                                    </div>
                                </div>
                                {{-- <h3 class="bg-white">{{$plan->name}}</h3>
                                <h4 class="text-dark">{{$plan->price}} <span class="p-1">{{$plan->special_price}}</span></h4> --}}
                                {{-- <img src="{{asset($plan->image)}}" alt="image" width="300px"> --}}
                                {{-- <h5 class="text-center">Valid till: <br> {{ $plan->created_at->format('d-m') }} :  {{ $plan->expiry_date->format('d-m') }}</h5> --}}
                            </div>
                    </div>    
                    @endforeach
                </div>
            </div>
            </div>
            <!-- /content area -->

        </div>
        <!-- /inner content -->

    </div>
    <!-- /main content -->

</div>
<!-- /page content -->
@endsection
