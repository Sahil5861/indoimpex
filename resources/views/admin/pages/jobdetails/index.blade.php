@extends('layout.base')

<style>
    .modal.modal-centered .modal-dialog {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }
    .modal.modal-centered .modal-dialog .modal-content {
        width: 500px;
    }
    .btn.disabled, .btn:disabled, fieldset:disabled .btn{
        border-color: #c7c9cd;
    }
    .disabled-input{
        opacity: .8 !important;     
        background-color: #555 !important;   
        color: #000 !important;
    }    
    .disabled-input:hover{
        cursor: not-allowed !important;
        background: #555 !important;        
        background-color: #555 !important; 
    }
    .is-invalid + .invalid-feedback {
        display: block;
    }
    .error{
        color: #ef4444 !important;
    }
    .lg-container{
        display: none;
    }
    .printing_type.active {
        background-color: var(--primary-bg) !important;
        color: #fff !important;
        /* border-color: #198754; */
        border-color: var(--primary-bg) !important;
    }

    .bopp_metal_type.active{
        background-color: var(--primary-bg) !important;
        color: #fff !important;
        /* border-color: #198754; */
        border-color: var(--primary-bg) !important;
    }

    .bottom_enclave_type.active {
        background-color: var(--primary-bg) !important;
        color: #fff !important;
        /* border-color: #198754; */
        border-color: var(--primary-bg) !important;
    }

    .bag_type.active {
        background-color: var(--primary-bg) !important;
        color: #fff !important;
        /* border-color: #198754; */
        border-color: var(--primary-bg) !important;
    } 
    .job_bag_type.active {
        background-color: var(--primary-bg) !important;
        color: #fff !important;
        /* border-color: #198754; */
        border-color: var(--primary-bg) !important;
    }  
    .imgs{
        margin-top: 10px;
    }  
    .imgs img {
        height: 60px;
        width: auto;
        border: 1px solid #ccc;
        padding: 5px;
        border-radius: 6px;
        object-fit: cover;
    }
    
    .suggestions li.highlight {
        background-color: #f0f0f0;
    }    


    .suggestions{
        position: absolute;
        top: 100%;
        z-index: 100;
        left: 0;
        width: 100%;        
        height: max-content;
        max-height: 350px;
        overflow: auto;
        background-color: #fff;
        border: 1px solid #888;
        list-style: none;
    }
    .suggestions li:hover{
        outline: 1px solid #888;
    }
    .input-group-text{
        height: 86%;
    }
    .vertical_line{        
        border-right: 1px solid #c7c9cd
    }

    .fancybox__container{
        z-index: 10000 !important;
    }

    .f-carousel__toolbar.is-absolute{
        z-index: -1;
    }

    .sticky-sidebar {
        position: sticky;
        top: 20px; /* distance from top of viewport */
        z-index: 1020; /* keep above content */
    }
    
</style>

@section('content')
<div class="page-content">
    @include('admin.pages.jobdetails.sidebar', ['sidebarTitle' => 'Job Details'])
    <div class="content-wrapper">
        <div class="content-inner">
            {{-- <div class="page-header page-header-light shadow">
                <div class="page-header-content d-lg-flex">
                    <div class="d-flex">
                        <h4 class="page-title mb-0">
                            Dashboard - <span class="fw-normal">Job Details | {{ucfirst($request_type)}}</span>
                        </h4>
                        <a href="#page_header"
                            class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto"
                            data-bs-toggle="collapse">
                            <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                        </a>
                    </div>
                </div>
            </div> --}}

            <div class="content">
                <div class="row">
                    {{-- <div class="col-lg-2">
                        <div class="card sticky-sidebar">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 my-2">
                                        <a href="{{route('jobdetails.view.all')}}" class="btn btn-lg w-100 bg-light {{ Route::currentRouteName() === 'jobdetails.view.all' ? 'rounded active' : '' }}">
                                            All Jobs
                                        </a>
                                    </div>                    
                                    <div class="col-md-12 my-2">
                                        <a href="{{route('jobdetails.view.pending')}}" class="btn btn-lg w-100 bg-light {{ Route::currentRouteName() === 'jobdetails.view.pending' ? 'rounded active' : '' }}">
                                            Pending Jobs
                                        </a>
                                    </div>                    
                                    <div class="col-md-12 my-2">
                                        <a href="{{route('jobdetails.view.saved')}}" class="btn btn-lg w-100 bg-light {{ Route::currentRouteName() === 'jobdetails.view.saved' ? 'rounded active' : '' }}">
                                            Saved Jobs
                                        </a>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <div class="col-lg-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Job Details | {{ ucfirst($request_type)}}</h5>
                                <div class="card-tools text-end"
                                    style="display: flex; align-items:center; justify-content: space-between;">
                                    <div class="btns">
                                        @if (hasPermission('Job Details All Save', 'Save') || hasPermission('Job Details Pending Save', 'Save'))                                    
                                        @if ($request_type != 'saved')                                    
                                            <a href="#" class="text-white btn btn-primary-2" onclick="openAddModal();">Add Job Details</a>
                                        @endif
                                        @endif
                                        @if (hasPermission('Job Details All Delete', 'Delete') || hasPermission('Job Details Pending Delete', 'Delete') || hasPermission('Job Details Saved Delete', 'Delete'))                                    
                                        <button class="btn btn-danger" id="delete-selected">Delete Selected</button>
                                        @endif
                                        <br><br>
                                        {{-- <select name="status" id="status" class="form-control">
                                            <option value="">All</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                        <button type="button" style="float: right;" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
        
                                @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                        <button type="button" style="float: right;" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
        
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>There were some problems with your input:</strong>
                                        <ul class="mb-0 mt-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
        
        
                                <div class="table-responsive">
        
                                    <table id="role-table" class="table table-bordered text-center table-striped">                                
                                        {{-- <thead>
                                            <th colspan="6" class="text-left" style="border: none;"><a href="#" class="btn btn-sm btn-teal">click me</a></th>                                    
                                        </thead> --}}
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input type="checkbox" id="select-all">
                                                    {{-- <button class="btn btn-sm btn-danger w-25"></button> --}}
                                                </th>
                                                <th>S.NO</th>
                                                <th>Job Code</th>                                        
                                                <th>Party Name</th>                                        
                                                <th>Job Name</th>                                        
                                                <th>Art Work</th>                                        
                                                @if ($request_type == 'saved')
                                                    @if (Auth::user()->role_id == 1)
                                                    <th>Saved By</th>                          
                                                    @endif
                                                @endif
                                                                      
                                                <th>Bag Weight (Gms)</th>                                                                                
                                                {{-- <th>Cerated on</th>                                         --}}
                                                <th class="text-center">Actions</th>                                                             
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- DataTables will populate this -->                                    
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="users" class="modal fade  m-auto" tabindex="-1" role="dialog" aria-labelledby="bopp"  data-bs-backdrop="static" data-bs-keyboard="false" style="display: none;">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Job Details</h4>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('jobdetails.save')}}" method="post" enctype="multipart/form-data" id="job_details_form">
                    @csrf 
                    
                    <input type="hidden" name="type" value="{{$request_type}}">
                    <input type="hidden" name="id" id="id">
                    <div class="form-body">                        
                        <div class="row">                                                       
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Job Code</label>
                                    <input type="text" id="job_code" class="form-control" name="job_code" required placeholder="Enter Job Code" value="{{ $lastID ?? '0001' }}" readonly>
                                    @error('job_code') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-4"></div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Job Date</label>
                                    <input type="text" id="job_date" class="form-control" name="submit_date" placeholder="Enter date" readonly value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                    @error('submit_date') <small class="text-danger">{{ $message }}</small> @enderror                                    
                                </div>
                            </div>                            

                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Name <span class="text-danger">*</span></label>
                                    <input type="text" id="job_name" class="form-control" name="job_name" required placeholder="Enter Job name" style="position: relative;" autocomplete="off" oninput="checkIsExist(this);" onchange="checkIsExist(this)" onblur="formatInput(this)">
                                    <script>
                                        function formatInput(el) {
                                            const value = el.value.trim();

                                            if (value === value.toUpperCase()) {
                                                // Already all uppercase — leave as is
                                                el.value = value;
                                            } else {
                                                // Convert to Title Case
                                                el.value = value.toLowerCase().replace(/\b\w/g, function(char) {
                                                    return char.toUpperCase();
                                                });
                                            }
                                        }
                                    </script>
                                    {{-- <div class="invalid-feedback">This field is required.</div> --}}
                                    {{-- <div class="job_name_suggestions suggestions" style="display: none;"></div> --}}
                                    <small class="job_name_error text-danger job_error_message" style="display: none;"></small>
                                    @error('job_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>   
                            <div class="col-md-6">
                                <div class="form-group" style="position: relative;">
                                    <label>Party Name <span class="text-danger">*</span></label>
                                    <input type="text" id="party_name" class="form-control add-data" data-id="party_name" data-popover="party_name_popup" name="party_name" required placeholder="Select Party Name" style="position: relative;" autocomplete="off">
                                    <div class="party_name_suggestions suggestions" style="display: none;"></div>
                                    <small class="party_name_error" style="display: none;"></small>
                                    @error('party_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>                                                                                                           
                        </div>
                        
                        <div class="form-seperator-dashed"></div>
                        <div class="row">                            
                            <?php
                                $printingTypes = ['BOPP', 'FLEXO On BOPP', 'FLEXO', 'OFFSET'];
                                $printingType2 = ['BOPP', 'FLEXO'];
                                $handleTypes = ['LOOP', 'LOOP w/ FLAP' ,'D-CUT', 'U-CUT'];
                                $bagTypes = ['Pillow Bag', 'Box Bag', 'Insulated Box Bag' ,'Roll Form'];
                                $bagTypes2 = ['Circular', 'Backseam'];
                                $bottomEnclave = ['Stitching', 'Pinch Bottom'];
                            ?>                             
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Job Type <span class="text-danger">*</span></label>
                                    <select name="job_type" id="job_type" class="form-control " style="height: 45px;" required onchange="resetJobTypeDependencies()">
                                        <option value="">--Select--</option>
                                        @foreach ($jobtypes as $type)
                                            <option value="{{$type->id}}">{{$type->job_type}}</option>
                                        @endforeach
                                    </select>
                                    @error('job_type') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div> 
                        </div>   

                        <div class="form-1" style="display: none;">
                            
                        <hr>
                        <div class="form-seperator-dashed"></div>  
                            <div class="row">                                                    
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Bag Type <span class="text-danger">*</span></label>
                                        <input type="hidden" class="job_bag_select" name="job_bag_type" id="job_bag_type">
                                        <div class="row g-3 bag_select_1">
                                            @foreach($bagTypes as $type)
                                                <div class="col-md-3">
                                                    <button type="button"
                                                            class="btn btn-sm btn-rounded btn-outline-success-2 job_bag_type  w-100 my-btn"
                                                            value="{{ $type }}"
                                                            style="white-space: nowrap;"
                                                            onclick="resetSectionState(['#printing_type_group','.handle_type_1', '.handle_type_2', '#bopp_metal_group'], ['#printing_type','#handle_type', '#bopp_metal_type']);"
                                                            >
    
                                                        {{ $type }}
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('bag_type') <small class="text-danger">{{ $message }}</small> @enderror
    
                                        <div class="row g-3 bag_select_2" style="display: none;">
                                            @foreach($bagTypes2 as $type)
                                                <div class="col-md-3">
                                                    <button type="button"
                                                            class="btn btn-sm btn-rounded btn-outline-success-2 job_bag_type  w-100 my-btn"
                                                            value="{{ $type }}"
                                                            style="white-space: nowrap;">
    
                                                        {{ $type }}
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>                                                                                                           
                                    </div>
                                </div> 
                                <div class="col-md-12 bottom_enclave mb-2" style="display: none;">
                                    <label>Bottom Enclousure <span class="text-danger">*</span></label>
                                    <input type="hidden" class="bottom_enclave" name="bottom_enclave" id="bottom_enclave">
                                    <div class="row g-3 ">
                                        @foreach($bottomEnclave as $type)
                                            <div class="col-md-3">
                                                <button type="button"
                                                        class="btn btn-sm btn-rounded btn-outline-success-2 bottom_enclave_type  w-100 my-btn"
                                                        value="{{ $type }}"
                                                        style="white-space: nowrap;"
                                                        onclick="resetSectionState(['#printing_type_group','.handle_type_1', '.handle_type_2', '#bopp_metal_group'], ['#printing_type','#handle_type', '#bopp_metal_type']);">
    
                                                    {{ $type }}
                                                </button>
                                            </div>
                                        @endforeach
                                    </div> 
                                </div>                                                                
                            </div>                                                        
                            <div class="row">                        
                                <input type="hidden" class="print_select" name="printing_type" id="printing_type">
                                <div class="col-md-12" id="printingTypes" style="display: none;">                                    
                                    <div class="form-group">
                                        <label>Printing Type <span class="text-danger">*</span></label>
                                        
                                        <div id="printing_type_group" class="row g-2">
                                            @foreach($printingTypes as $type)
                                                <div class="col-md-3">
                                                    <button type="button"
                                                            class="btn btn-sm btn-rounded btn-outline-success-2 printing_type  w-100 my-btn"
                                                            value="{{ $type }}"
                                                            style="white-space: nowrap;"
                                                            {{-- onclick="resetSectionState(['.handle_type_1', '.handle_type_2', '#bopp_metal_group'], ['#handle_type', '#bopp_metal_type']);" --}}
                                                            >
    
                                                        {{ $type }}
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <small id="printing_type_error" class="text-danger d-none">Printing Type is required.</small>
                                    </div>
                                </div> 
                                <div class="col-md-12" id="printingTypes2" style="display: none;">
                                    <div class="form-group">
                                        <label>Printing Type <span class="text-danger">*</span></label>                                        
                                        <div id="printing_type_group" class="row g-2">
                                            @foreach($printingType2 as $type)
                                                <div class="col-md-3">
                                                    <button type="button"
                                                            class="btn btn-sm btn-rounded btn-outline-success-2 printing_type  w-100 my-btn"
                                                            value="{{ $type }}"
                                                            style="white-space: nowrap;"
                                                            onclick="resetSectionState(['.handle_type_1', '.handle_type_2', '#bopp_metal_group'], ['#handle_type', '#bopp_metal_type']);"
                                                            >    
                                                        {{ $type }}
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <small id="printing_type_error" class="text-danger d-none">Printing Type is required.</small>
                                    </div>
                                </div>                        
    
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Handle Type <span class="text-danger">*</span></label>
                                        <input type="hidden" class="bag_select" name="job[bag_type]" id="handle_type">
                                        <div class="row g-2 handle_type_1">                                        
                                            <div class="col-md-3">
                                                <button type="button"class="btn btn-sm btn-rounded btn-outline-success-2 bag_type w-100 my-btn"id="loop"value="LOOP"style="white-space: nowrap;" onclick="resetSectionState(['#bopp_metal_group'], ['#bopp_metal_type']);">LOOP</button>                                            
                                            </div>  
                                            <div class="col-md-3">
                                                <button type="button"class="btn btn-sm btn-rounded btn-outline-success-2 bag_type w-100 my-btn"id="loop_w_flap"value="LOOP w| FLAP"style="white-space: nowrap;" onclick="resetSectionState(['#bopp_metal_group'], ['#bopp_metal_type']);">LOOP w| FLAP</button>                                            
                                            </div>                                        
                                            <div class="col-md-3">
                                                <button type="button"class="btn btn-sm btn-rounded btn-outline-success-2 bag_type w-100 my-btn"id="d_cut"value="D-CUT"style="white-space: nowrap;" onclick="resetSectionState(['#bopp_metal_group'], ['#bopp_metal_type']);">D-CUT</button>                                            
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button"class="btn btn-sm btn-rounded btn-outline-success-2 bag_type w-100 my-btn"id="u_cut"value="U-CUT"style="white-space: nowrap;" onclick="resetSectionState(['#bopp_metal_group'], ['#bopp_metal_type']);">U-CUT</button>
                                            </div>
                                        </div>
    
                                        <div class="row g-2 handle_type_2" style="display: none">                                        
                                            <div class="col-md-3">
                                                <button type="button"class="btn btn-sm btn-rounded btn-outline-success-2 bag_type w-100 my-btn"id="niwas"value="NIWAR"style="white-space: nowrap;" onclick="resetSectionState(['#bopp_metal_group'], ['#bopp_metal_type']);">NIWAR</button>                                            
                                            </div>                                        
                                            <div class="col-md-3">
                                                <button type="button"class="btn btn-sm btn-rounded btn-outline-success-2 bag_type w-100 my-btn"id="c_punch"value="C-PUNCH"style="white-space: nowrap;" onclick="resetSectionState(['#bopp_metal_group'], ['#bopp_metal_type']);">C-PUNCH</button>                                            
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button"class="btn btn-sm btn-rounded btn-outline-success-2 bag_type w-100 my-btn"id="none"value="NONE"style="white-space: nowrap;" onclick="resetSectionState(['#bopp_metal_group'], ['#bopp_metal_type']);">NONE</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                 
                                @php
                                    $metalTypes = [
                                        ['label' => 'Metalised', 'value' => 1],
                                        ['label' => 'Non-Metalised', 'value' => 0],
                                    ];
                                @endphp
    
                                <div class="col-md-12" id="bopp_metal">
                                    <div class="form-group">
                                        <label><span class="text-danger">TBD </span></label>
                                        <input type="hidden" name="bopp_metal_type" id="bopp_metal_type">
                                        <div id="bopp_metal_group" class="row g-2">
                                            @foreach($metalTypes as $metal)
                                                <div class="col-md-3">
                                                    <button type="button"
                                                            class="btn btn-sm btn-rounded btn-outline-success-2 bopp_metal_type w-100 my-btn"
                                                            value="{{ $metal['value'] }}">
                                                        {{ $metal['label'] }}
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <small id="bopp_metal_error" class="text-danger d-none">Metal Type is required.</small>
                                    </div>
                                </div>                     
                            </div> 
                        </div>
                        
                        <script>
                            function resetJobTypeDependencies() {
                                // Remove 'active' class from all buttons with class 'my-btn'
                                document.querySelectorAll('.my-btn').forEach(btn => {
                                    btn.classList.remove('active');
                                    btn.classList.remove('disabled-input');
                                    btn.classList.add('btn-outline-success-2');

                                });

                                // Clear all related hidden input fields (customize IDs as needed)
                                const inputIds = ['#printing_type', '#handle_type', '#bottom_enclave', '#job_bag_type', '#bopp_metal_type'];
                                inputIds.forEach(id => {
                                    const input = document.querySelector(id);
                                    if (input) input.value = '';
                                });
                            }


                            function resetSectionState(buttonContainerSelectors = [], inputSelectors = []) {
                                // Clear buttons
                                buttonContainerSelectors.forEach(selector => {
                                    document.querySelectorAll(`${selector} .my-btn`).forEach(btn => {
                                        btn.classList.remove('active', 'disabled-input');
                                        btn.classList.add('btn-outline-success-2');
                                    });
                                });

                                // Clear hidden inputs
                                inputSelectors.forEach(selector => {
                                    const input = document.querySelector(selector);
                                    if (input) input.value = '';
                                });
                            }

                        </script>

                        
                        <div class="form-2" style="display: none">                                                    
                            <hr>
                            {{-- form starts here --}}                            
                            <div class="row">                            
                                <div class="col-md-12">
                                    <h4 class="modal-title-2-2 float-xl-left">Bag</h4>
                                </div>
                                <div class="col-md-8" id="pillow_bag">
                                    <div class="form-group">
                                        <label>Bag Size <span class="text-danger">*</span></label>  
                                        <div class="row mb-3 d-flex justify-content-start align-items-start">
                                            <div class="col-md-5">  
                                                <p>Width</p>
                                                <div class="input-group">                                                                                                                                                        
                                                    <input type="text" class="form-control bag_job_size" autocomplete="off"  placeholder="Width" name="job[bag_circum]" required oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1'); widthIninches.innerText = (this.value / 25.4).toFixed(2) + ' inches';">                                            
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">mm</span>
                                                    </div>
                                                </div>                                          
                                                <p id="widthIninches" class="my-2 show_measure" style="dispaly:block; height:24px; font-weight:bold;"></p>
                                            </div>                                            
                                            <div class="col mt-1 text-center">
                                                <p></p><br>
                                                X
                                            </div>
                                            <div class="col-md-5"> 
                                                <p>Height</p>
                                                <div class="input-group">                                                
                                                    <input type="text" class="form-control bag_pet_size" autocomplete="off"  placeholder="Height" name="job[bag_pet]" required oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');heightIninches.innerText = (this.value / 25.4).toFixed(2) + ' inches'">                                            
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">mm</span>
                                                    </div>
                                                </div>                                           
                                                <p id="heightIninches" class="my-2 show_measure" style="dispaly:block; height:24px; font-weight:bold;"></p>
                                            </div>
                                        </div>

                                        @error('bag_size') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>

                                <div class="col-md-12" id="gaz_bag" style="display: none;">
                                    <div class="form-group">
                                        <label>Bag Size <span class="text-danger">*</span></label>
                                        <div class="row mb-3 d-flex justify-content-start align-items-start">
                                            
                                            <!-- Width -->
                                            <div class="col-md-3">
                                                <p>Width</p>
                                                <div class="input-group">
                                                    <input type="text" 
                                                        class="form-control bag_job_size" 
                                                        autocomplete="off"  
                                                        placeholder="Width" 
                                                        name="job[bag_circum]" 
                                                        required 
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1')">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">mm</span>
                                                    </div>
                                                </div>
                                                <p class="my-2 show_measure" id="job_size" style="display: block; height: 24px; font-weight: bold;"></p>
                                            </div>

                                            <!-- Multiplication Sign -->
                                            <div class="col mt-1 text-center">
                                                <p></p><br>
                                                X
                                            </div>

                                            <!-- Height -->
                                            <div class="col-md-3">
                                                <p>Height</p>
                                                <div class="input-group">
                                                    <input type="text" 
                                                        class="form-control bag_pet_size" 
                                                        autocomplete="off" 
                                                        placeholder="Height" 
                                                        name="job[bag_pet]" 
                                                        required 
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1')">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">mm</span>
                                                    </div>
                                                </div>
                                                <p class="my-2 show_measure" id="bag_pet_inch" style="display: block; height: 24px; font-weight: bold;"></p>
                                            </div>

                                            <!-- Multiplication Sign -->
                                            <div class="col mt-1 text-center">
                                                <p></p><br>
                                                X
                                            </div>

                                            <!-- Gusset -->
                                            <div class="col-md-3">
                                                <p>Gusset</p>
                                                <div class="input-group">
                                                    <input type="text" 
                                                        class="form-control bag_gaz_size" 
                                                        autocomplete="off" 
                                                        placeholder="Gusset" 
                                                        name="job[bag_gazette]" 
                                                        required 
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">mm</span>
                                                    </div>
                                                </div>
                                                <p class="my-2 show_measure" id="bag_gazette_inch" style="display: block; height: 24px; font-weight: bold;"></p>
                                            </div>

                                        </div>
                                    </div>
                                </div>   
                            </div>                                                                                                                       
                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-6 weight-bag-handle">
                                    <div class="form-group">
                                        <label class="weight_label">Weight/Bag w/Handle</label>  
                                        <div class="input-group">
                                            <input type="text" class="form-control total_bag_weight" autocomplete="off" placeholder="Weight/Sheet" tabIndex="-1" name="bag_total_weight" readonly required value="0.0">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Grams</span>
                                            </div>
                                        </div>

                                        <small class="text-danger d-none" id="total_bage_weight_error">Total Bag Weight is required</small>
                                        @error('bag_size') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>  
                            </div>
                            <div class="row" id="cylinder_div">                            
                                <hr>
                                <div class="col-md-12">
                                    <h4>Cylinder</h4>
                                </div>                            
                                <div class="col-md-6 row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Job Size <span class="text-danger">*</span></label>  
                                            <div class="row mb-3 d-flex justify-content-start align-items-center">
                                                <div class="col-md-5">
                                                    <p>Circum</p>
                                                    <input type="text" name="cylinder[job_circum]" id="circum" class="form-control c_circum" placeholder="Circum" required oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">                                            
                                                </div>
                                                <div class="col-md-1">
                                                    <p></p><br>
                                                    X
                                                </div>
                                                <div class="col-md-5">
                                                    <p>Pet</p>
                                                    <input type="text" class="form-control" autocomplete="off" placeholder="Pet" name="cylinder[job_pet]" required oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                </div>
                                            </div>

                                            @error('bag_size') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Shell Size <span class="text-danger">*</span></label>  
                                            <div class="row mb-3 d-flex justify-content-start align-items-center">
                                                <div class="col-md-5">
                                                    <p>Circum</p>
                                                    <input type="text" class="form-control" autocomplete="off" placeholder="Circum" name="cylinder[shell_circum]" required  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                </div>
                                                <div class="col-md-1">
                                                    <p></p><br>
                                                    X                                            
                                                </div>
                                                <div class="col-md-5">
                                                    <p>Pet</p>
                                                    <input type="text" class="form-control" autocomplete="off" placeholder="Pet" name="cylinder[shell_pet]" required oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                </div>
                                            </div>

                                            @error('bag_size') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div> 
                                </div>

                                <div class="col-md-1 d-flex justify-content-center">
                                    <div style="width: 1px; height: 100%; background-color: #c7c9cd;"></div>                            
                                </div>
                                
                                <div class="col-md-5 row">                                
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <p></p> <br>
                                            <label>Coil <span class="text-danger">*</span></label>  
                                            <input type="text" class="form-control coil_cylinder" autocomplete="off" placeholder="Coil" name="cylinder[coil_cylinder]" value="1" oninput="this.value = this.value.replace(/[^0-9]/, '');">
                                            @error('coil_cylinder') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
        
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <p></p> <br>
                                            <label style="white-space: nowrap">Repeat Cylinder <span class="text-danger">*</span></label>  
                                            <input type="text" class="form-control repeat_cylinder" autocomplete="off" name="cylinder[cylinder_repeat]" value="1" oninput="this.value = this.value.replace(/[^0-9]/, '');">
                                            @error('repeat') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
        
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <p></p> <br>
                                            <label>No of cylinders</label>  
                                            <input type="text" class="form-control" autocomplete="off"  placeholder="No. Of Cylinder" name="cylinder[cylinder_count]" oninput="this.value = this.value.replace(/[^0-9]/, '');">
                                            @error('repeat') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                </div>                           
                            </div>
                            <hr>                        
                            <div class="row mt-2" id="flexo_bopp" style="display: none;">                            
                                <h4 class="modal-title-2 float-xl-left">Block</h4>
                            </div>
                            <div class="row" id="flexo_bopp_detail" style="display: none;">
                                <div class="col-md-4">
                                    <label>Job Size <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="row mb-3">
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" autocomplete="off" placeholder="Circum" name="flexo_b[flexo_circum]" oninput="this.value = this.value.replace(/[^0-9]/, '');">
                                            </div>
                                            <div class="col-md-2 text-center">
                                                X
                                            </div>  
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" autocomplete="off" placeholder="Pet" name="flexo_b[flexo_pet]" oninput="this.value = this.value.replace(/[^0-9]/, '');">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label>Block Size</label>
                                    <div class="input-group">
                                        <div class="row mb-3">
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" autocomplete="off" placeholder="Circum" name="flexo_b[box_circum]" oninput="this.value = this.value.replace(/[^0-9]/, '');">
                                            </div>
                                            <div class="col-md-2 text-center">
                                                X
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" autocomplete="off" placeholder="Pet" name="flexo_b[box_pet]" oninput="this.value = this.value.replace(/[^0-9]/, '');">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label>No. Of Block :</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" autocomplete="off" placeholder="No. Of Box" name="flexo_b[box_count]" oninput="this.value = this.value.replace(/[^0-9]/, '');">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Pcs.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="row">
                                <div class="col-md-12">                               
                                    <div class="form-group">                                        
                                        <div class="row g-2" style="margin-top:0;">
                                            <?php $count = 8 ?>
                                            @for ($i = 1; $i <= $count; $i++)
                                            <div class="col mb-3 ">
                                                <small id="cmykOutput_{{ $i }}" style="font-size: .7em; height: 18px; display: inline-block; margin:10px 0;"></small>
                                                <input type="text"
                                                    id="color-input-{{ $i }}"
                                                    name="colors_cmyk[] position-relative"
                                                    class="form-control color-input"
                                                    placeholder="Color {{ $i }}"
                                                    readonly
                                                    data-index="{{ $i }}"
                                                    autocomplete="new-password"
                                                    onclick="document.getElementById('cmyk-input-C-{{ $i }}').focus(); "
                                                    onfocus="document.getElementById('cmyk-picker-{{ $i }}').show(); "
                                                >

                                                <!-- CMYK Popup -->
                                                <div class="cmyk-popup" id="cmyk-picker-{{ $i }}" data-index="{{ $i }}" style="display: none; position: absolute; top: 100%; left: %; width:100%; z-index: 1000; background: #fff; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                                                    <div class="row g-1">
                                                        <div class="col-md-12"><input type="text" id="cmyk-input-C-{{$i}}" class="form-control cmyk-input" placeholder="C" min="0" max="100" data-channel="c" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1'); if (parseFloat(this.value) > 100) this.value = 100;"></div>
                                                        <div class="col-md-12"><input type="text" id="cmyk-input-M-{{$i}}" class="form-control cmyk-input" placeholder="M" min="0" max="100" data-channel="m" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1'); if (parseFloat(this.value) > 100) this.value = 100;"></div>
                                                        <div class="col-md-12"><input type="text" id="cmyk-input-Y-{{$i}}" class="form-control cmyk-input" placeholder="Y" min="0" max="100" data-channel="y" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1'); if (parseFloat(this.value) > 100) this.value = 100;"></div>
                                                        <div class="col-md-12"><input type="text" id="cmyk-input-K-{{$i}}" class="form-control cmyk-input" placeholder="K" min="0" max="100" data-channel="k" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1'); if (parseFloat(this.value) > 100) this.value = 100;"></div>
                                                    </div>
                                                </div>

                                                <input type="hidden" name="colors[]" id="cmykOutputinput_{{ $i }}" class="cmyk_output"> 
                                                <input type="text" name="color_name[]" id="color_name_{{$i}}" class="form-control my-1 color_name" autocomplete="off">                                           
                                            </div>
                                            @endfor

                                        </div>
                                    </div>

                                </div>                            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>KLD</label>
                                        <input type="file" class="form-control image-input" id="kld_images" autocomplete="off"  placeholder="Job Name" name="kld[]" multiple onchange="previewKLDImages(this)" accept="image/*,.pdf">
                                        <div id="kld_images_preview" class="imgs"></div>
                                        <div id="kld_preview" class="imgs"></div>
                                        
                                    </div>                                                              
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mockups</label>
                                        <input type="file" class="form-control image-input" id="mock_up_images" autocomplete="off"  placeholder="Job Name" name="files[]" multiple onchange="previewMockupImages(this)" accept="image/*,.pdf">
                                        <div id="mock_up_images_preview" class="imgs"></div>
                                        <div id="mock_up_preview" class="imgs"></div>
                                    </div>                                                               
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Separation</label>
                                        <input type="file" class="form-control image-input" id="suppression_images" autocomplete="off"  placeholder="Job Name" name="suppression[]" multiple onchange="previewSuppressionImages(this)" accept="image/*,.pdf">
                                        <div id="suppression_images_preview" class="imgs"></div>
                                        <div id="suppression_preview" class="imgs"></div>
                                    </div>                                                             
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Approval</label>
                                        <input type="file" class="form-control image-input" id="approval_images" autocomplete="off"  placeholder="Job Name" name="approve[]" multiple onchange="previewApproveImages(this)" accept="image/*,.pdf">
                                        <div id="approve_images_preview" class="imgs"></div>
                                        <div id="approve_preview" class="imgs"></div>
                                    </div>                                                              
                                </div>                            
                            </div>
                            <div id="myimgpopover" style="display: none; position: fixed; top: 50%; left: 50%; transform:translate(-50%, -50%);  
                                width: 800px; height: 600px; background: rgba(0, 0, 0, 0.7); 
                                justify-content: center; align-items: center; z-index: 10000; ">
                                <button type="button" class="close-popup" onclick="myimgpopover.style.display = 'none';" style="position: absolute; top: 20px; right: 30px; background:transparent; font-size: 30px; color: white; cursor: pointer; border:none;">&times;</button>                            
                            </div>
                            <!-- bopp -->
                            <div class="row my-2" id="bopp_title">                            
                                <hr>
                                <h4 class="modal-title-2 float-xl-left">BOPP</h4>
                            </div>
                            <div class="row mb-3" id="bopp_detail">
                                <div class="col-md-4">
                                    <label>Item Code</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control bopp_item_code add-data" data-id="bopp_code" data-popover="bopp_popup"  id="bopp_item_code"  placeholder="Enter Item Code" name="bopp[bopp_item_code]" autocomplete="off" oninput="this.value = this.value.toUpperCase();">
                                        <div class="bopp-item-suggestions suggestions" style="display: none;"></div>                                    
                                    </div>
                                    <span class="text-success response-msg"></span>
                                    <div class="all-tag"></div>
                                </div>
                            </div>
                            <div class="row" id="bopp_extra_detail">
                                <div class="col-md-4 mb-3">
                                    <label>Size</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control bopp_size" placeholder="Size" name="bopp[job_bopp_size]" tabIndex="-1" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">mm</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>BOPP Type</label>
                                    <div class=" input-group ">
                                        <input type="text" class="form-control bopp-cat" placeholder="BOPP Type" name="bopp[job_bopp_type]" tabIndex="-1" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Micron</label>
                                    <div class=" input-group ">
                                        <input type="text" class="form-control bopp_micron" placeholder="Micron" name="bopp[job_bopp_micron]" tabIndex="-1" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">MCR</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3"></div>
                                <div class="col-md-6 mb-3">
                                    <label class="bopp_weight_label">BOPP Weight/Bag</label>
                                    <div class=" input-group ">
                                        <input type="text" class="form-control bopp_weight" placeholder="BOPP Weight/Sheet"  tabIndex="-1" readonly name="bopp[job_bopp_weight]">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Gms</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- bopp -->
                            
                            <!-- metalised -->
                            <div id="metalise" style="display: none;">
                                <hr>
                                <div class="row my-2">
                                    <h4 class="modal-title-2 float-xl-left">Metalise</h4>
                                </div>
                                <div class="row mb-3" id="metal_detail">
                                    <div class="col-md-4">
                                        <label>Item Code</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control metal_item_code add-data" data-id="metal_code" data-popover="metal_popup" id="metal_item_code"  placeholder="Enter Item Code" name="metal[metal_item_code]" autocomplete="off" oninput="this.value = this.value.toUpperCase();">
                                            <div class="metal-item-suggestions suggestions" style="display: none;"></div>                                    
                                        </div>
                                        <span class="text-success response-msg"></span>
                                        <div class="all-tag"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label>Size</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control metal_size" placeholder="Size" name="metal[job_metal_size]" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text">mm</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>BOPP Type</label>
                                        <div class=" input-group ">
                                            <input type="text" class="form-control metal_type" placeholder="BOPP Type" name="metal[job_metal_type]" tabIndex="-1" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>Micron</label>
                                        <div class=" input-group ">
                                            <input type="text" class="form-control metal_micron" placeholder="Micron" name="metal[job_metal_micron]" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text">MCR</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3"></div>
                                    <div class="col-md-6 mb-3">
                                        <label>Metalise Weight/Bag</label>
                                        <div class=" input-group ">
                                            <input type="text" class="form-control metal_weight" placeholder="BOPP Weight/Sheet" tabIndex="-1" readonly name="metal[job_metal_weight]">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Gms</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                                    
                            </div>
                            <!-- metalised -->
                            <hr>
                            <!-- Fabric -->
                            <div class="row my-2">
                                <h4 class="modal-title-2 float-xl-left">Fabric</h4>
                            </div>
                            <div class="row mb-3" id="shopping_bag_fabric">
                                <div class="col-md-4">
                                    <label>Item Code</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control fabric_item_code add-data" data-id="fabric_code" data-popover="fabric_popup" id="fabric_item_code"  placeholder="Enter Item Code" name="fabric[fabric_item_code]" autocomplete="off" oninput="this.value = this.value.toUpperCase();">
                                        <div class="fabric-item-suggestions suggestions" style="display: none;"></div>
                                    </div>
                                    <span class="text-success response-msg"></span>
                                    <div class="fabric-tag"></div>
                                </div>
                            </div>

                            <div class="row mb-3" id="packing_bag_fabric" style="display: none;">
                                <div class="col-md-4">
                                    <label>Item Code PP</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control fabric_item_code_pp add-data" data-id="fabric_pp_code" data-popover="fabric_pp_popup" id="fabric_item_code_pp"  placeholder="Enter Item Code" name="fabric[fabric_item_code]" autocomplete="off" oninput="this.value = this.value.toUpperCase();">
                                        <div class="fabric-item-suggestions-pp suggestions" style="display: none;"></div>
                                    </div>
                                    <span class="text-success response-msg"></span>
                                    <div class="fabric-tag"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label>Size</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control fabric_size" placeholder="Size" name="fabric[job_fabric_size]" tabIndex="-1" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Inches</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Fabric Type</label>
                                    <div class=" input-group ">                                            
                                        <input type="text" class="form-control pp_cat" placeholder="Fabric Type" name="fabric[job_fabric_type]" tabIndex="-1" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>GMS</label>
                                    <div class=" input-group ">
                                        <input type="text" class="form-control fabric_gsm" placeholder="GMS" name="fabric[job_fabric_gsm]" tabIndex="-1" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">GMS</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3"></div>
                                <div class="col-md-6 mb-3">
                                    <label class="fabric_weight_label">Fabric Weight/Bag</label>
                                    <div class=" input-group ">
                                        <input type="text" class="form-control fabric_weight" placeholder="Fabric Weight/Bag" tabIndex="-1" readonly name="fabric[job_fabric_weight]">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Gms</span>
                                        </div>
                                    </div>
                                </div>
                            </div>                        
                            <hr>
                            <!-- Fabric -->

                            {{-- lamination --}}
                            <div class="row mb-3">
                                <h4 class="modal-title-2 float-xl-left">Lamination</h4>                                
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Mixture</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control lamination_mixture" autocomplete="off" placeholder="Mixture" name="lamination[job_lamination_mix]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Inches</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>GMS</label>
                                    <div class=" input-group ">
                                        <input type="text" class="form-control lamination_gsm" autocomplete="off"  placeholder="GMS" name="lamination[job_lamination_gsm]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        <div class="input-group-append">
                                            <span class="input-group-text">GMS</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3"></div>
                                <div class="col-md-6 mb-3">
                                    <label class="lamination_weight_label">Lamination Weight/Bag</label>
                                    <div class=" input-group ">
                                        <input type="text" class="form-control lamination_weight" placeholder="Lamination Weight/Bag" tabIndex="-1" readonly name="lamination[job_lamination_weight]">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Gms</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- lamination --}}

                            
                            {{-- handle --}}
                            <div id="handle-weight-bag-div">
                                <hr>
                                <div class="row my-2" id="handle_title">
                                    <h4 class="modal-title-2 float-xl-left">Handle</h4>
                                </div>
    
                                <div class="row" id="handle_detail">
                                    <div class="col-md-4">
                                        <label>Colour</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" autocomplete="off" placeholder="Colour" name="handle[job_handle_color]" oninput="this.value = this.value.replace(/[0-9]/, '');">
                                        </div>
                                    </div>
                                    <div class="col-md-4 handle_gsm">
                                        <label>GMS</label>
                                        <div class=" input-group ">
                                            <input type="text" class="form-control" autocomplete="off" placeholder="GMS" name="handle[job_handle_gsm]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                            <div class="input-group-append">
                                                <span class="input-group-text">GMS</span>
                                            </div>
                                        </div>
                                    </div>                                                          
                                </div>
                                <div class="row my-3">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <label>Handle Weight/Bag</label>
                                        <div class=" input-group ">
                                            <input type="text" class="form-control handle_weight" autocomplete="off" placeholder="Weight" name="handle[job_handle_weight]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Gms</span>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                            {{-- handle --}}
                            
                            {{-- remarks --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Remark</label>
                                    <div class="form-group">
                                        <textarea class="form-control" rows="5" name="job[job_description]" id="job_description" placeholder="Write Your Short Note Here........"></textarea>
                                    </div>
                                </div>
                            </div>
                            {{-- remarks --}}

                            <input type="hidden" name="job_status" id="job_status">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-rounded text-left close-modal" data-dismiss="modal">Cancel</button>
                        <button type="button" onclick="job_status.value = 0; removerequredandSubmit(event);" class="btn btn-success-2 float-right text-right submit-save-btn">Save</button>
                        @if (Auth::user()->role_id == '1')
                            <button type="submit" onclick="job_status.value = 1" class="btn btn-success-2 float-right text-right submit-save-btn">Approve</button>
                        @else
                        <button type="submit" onclick="job_status.value = 1" class="btn btn-success-2 float-right text-right submit-save-btn">Submit</button>
                        @endif
                    </div>
                </form>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>    
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{asset('assets/js/jquery/jquery.validate.min.js')}}"></script>

@include('admin.pages.jobdetails.popover')

{{-- <script>
    $('#job_details_form').validate({
        highlight: function (element){
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element){
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function (error, element) {
            error.addClass('text-danger');
            error.insertAfter(element);
        }
    });
</script> --}}


<script>
  function removerequredandSubmit(event) {
    event.preventDefault();    
    const form = document.getElementById('job_details_form');

    // Remove all 'required' attributes inside the form
    form.querySelectorAll('[required]').forEach(function (input) {
      input.removeAttribute('required');
    });    
    // Submit the form
    form.submit();
  }
</script>


<script>
    $(document).on('click', function (event){
        
        if (!$(event.target).closest('.suggestions').length) {            
            // Close or hide the suggestion
            $('.suggestions').hide(); // Or any logic you use to close it
        }

        if (event.target.closest('.approve-job-btn')) {
            event.preventDefault();

            const btn = event.target.closest('.approve-job-btn');
            const url = btn.getAttribute('data-url');
            const jobId = btn.getAttribute('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to approve this job.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
        
    })    

    $('form').on('keydown', function (event) {
        if (event.key === 'Enter' && (event.target.id === 'party_name' || event.target.id === 'job_name')) {
            event.preventDefault(); // Stop native validation and submission
        }
    });
    $('#job_details_form').submit(function (event) {
        event.preventDefault();

        // Remove existing validation classes
        // $(this).find(':input').removeClass('is-invalid');

        // let isValid = true;
        

        // $(this).find(':input[required]').each(function () {
        //     if ($(this).val() == '' || !$(this).val()) {
        //         $(this).addClass('is-invalid');
        //         isValid = false;                
        //     }
        // });

        // if (!isValid) {
        //     event.preventDefault(); // prevent form submission
        // }

        let printingType = $('#printing_type').val();
        let total_bag_weight = $('.total_bag_weight').val();


        if (!printingType) {
            // Show error
            $('#printing_type_error').removeClass('d-none');

            // Scroll to button group
           $('#users').animate({
                scrollTop: $('#printing_type_group').position().top + $('#users').scrollTop() - 300
            }, 500);
            
            return false;
        }

        if (!total_bag_weight) {
            $('#total_bage_weight_error').removeClass('d-none');
             // Scroll to button group
           $('#users').animate({
                scrollTop: $('#printing_type_group').position().top + $('#users').scrollTop() - 300
            }, 500);   
        }

        // Hide error if already valid
        $('#printing_type_error').addClass('d-none');          
        document.getElementById('job_details_form').submit();
    });     

</script>
<script>
    document.querySelectorAll('.printing_type').forEach(btn => {
        btn.addEventListener('click', function () {
            // Remove active class from all
            document.querySelectorAll('.printing_type').forEach(b => b.classList.remove('active'));
            
            // Add active class to the clicked one
            this.classList.add('active');

            // Set the hidden input value
            document.getElementById('printing_type').value = this.value;
        });
    });

    document.querySelectorAll('.bopp_metal_type').forEach(btn => {
        btn.addEventListener('click', function () {
            // Remove active class from all
            document.querySelectorAll('.bopp_metal_type').forEach(b => b.classList.remove('active'));
            
            // Add active class to the clicked one
            this.classList.add('active');

            // Set the hidden input value
            document.getElementById('bopp_metal_type').value = this.value;

            openform2();

        });
    });

    document.querySelectorAll('.bottom_enclave_type').forEach(btn => {
        btn.addEventListener('click', function () {
            // Remove active class from all
            document.querySelectorAll('.bottom_enclave_type').forEach(b => b.classList.remove('active'));
            
            // Add active class to the clicked one
            this.classList.add('active');

            // Set the hidden input value
            document.getElementById('bottom_enclave').value = this.value;
        });
    });

    document.querySelectorAll('.handle_type').forEach(btn => {
        btn.addEventListener('click', function () {
            // Remove active class from all
            document.querySelectorAll('.handle_type').forEach(b => b.classList.remove('active'));
            
            // Add active class to the clicked one
            this.classList.add('active');

            // Set the hidden input value
            document.getElementById('handle_type').value = this.value;
        });
    });
    

    $(document).on("click",".job_bag_type",function(){                  
        let name = $(this).text();         
        let job_type = $('#job_type').val().trim();
        
        var val = $(this).val();
        $(".job_bag_type").removeClass("selected_bag");
        $(".job_bag_type").removeClass("active");        
        $(".job_bag_type").addClass("btn-outline-success-2");
        $(this).addClass("selected_bag");
        $(this).addClass("active");
        $(".job_bag_select").val(val);    
                
        if(name.trim() == 'Pillow Bag'){            
            $("#pillow_bag").css("display","block");
            $("#gaz_bag").css("display","none");
            $("#gaz_bag input").attr("disabled", "disabled");
            $("#pillow_bag input").removeAttr("disabled");
            $("#pillow_handle_type").css("display","flex");
            $("#gaz_handle_type").css("display","none");

            $('.lamination_weight_label').html('Lamination Weight/Bag');
            $('.bopp_weight').html('BOPP Weight/Bag');
            $('.fabric_weight_label').html('Fabric Weight/Bag');
            $('.weight_label').html('Weight/Bag w/Handle')

            // handle
            $('#handle_title').show();
            $('#handle_detail').show();
            $("#d_cut").css("cursor", "pointer").removeClass('disabled-input').addClass('btn-outline-success-2');
            $("#loop").css("cursor", "pointer").removeClass('disabled-input').addClass('btn-outline-success-2');
            $('#loop_w_flap').css("cursor", "none").removeClass('active').removeClass('btn-outline-success-2').addClass('disabled-input');

            var val = $(".print_select").val();
            if ((val == "BOPP") || (val == "OFFSET") || (val == "FLEXO On BOPP")) {
                $("#u_cut").css("cursor", "none").addClass('disabled-input').removeClass('active').removeClass('btn-outline-success-2');
            }
            else if (val == 'FLEXO'){
                $("#u_cut").css("cursor", "pointer").removeClass('disabled-input').addClass('btn-outline-success-2');
            }

            $(".printing_type").filter(function() {
                return $(this).val() === "OFFSET";
            }).closest('.col-md-3').show();  
            
            if ($(".printing_type").filter(function() {
                    return $(this).val() === "OFFSET" || $(this).val() === "Flexo";
                }).length > 0) {

                $("#cylinder_div").remove();
            }
            else{
                $("#cylinder_div").show();
            }



        }
        else if (name.trim() == 'Box Bag' || name.trim() == 'Insulated Box Bag' || job_type == '3'){            

            $("#gaz_bag").css("display","block");
            $("#gaz_handle_type").css("display","block");
            $("#pillow_bag").css("display","none");
            $("#pillow_bag input").attr("disabled", "disabled");
            $("#gaz_bag input").removeAttr("disabled");
            $("#pillow_handle_type").css("display","none");   

            $('.lamination_weight_label').html('Lamination Weight/Bag');
            $('.bopp_weight').html('BOPP Weight/Bag');
            $('.fabric_weight_label').html('Fabric Weight/Bag');
            $('.weight_label').html('Weight/Bag w/Handle')

            // handle
            $('#handle_title').show();
            $('#handle_detail').show();
            
            $("#loop").css("cursor", "pointer").removeClass('disabled-input').addClass('btn-outline-success-2');
            $("#d_cut").css("cursor", "none").addClass('disabled-input').removeClass('active').removeClass('btn-outline-success-2');
            $("#u_cut").css("cursor", "none").addClass('disabled-input').removeClass('active').removeClass('btn-outline-success-2');
            $('#loop_w_flap').css("cursor", "none").removeClass('active').removeClass('btn-outline-success-2').addClass('disabled-input');

            if (name.trim() == 'Box Bag') {
                $('#loop_w_flap').css("cursor", "pointer").addClass('btn-outline-success-2').removeClass('disabled-input');                
            }

            $(".printing_type").filter(function() {
                return $(this).val() === "OFFSET";
            }).closest('.col-md-3').hide(); 
            
            if ($(".printing_type").filter(function() {
                    return $(this).val() === "OFFSET" || $(this).val() === "Flexo";
                }).length > 0) {

                $("#cylinder_div").remove();
            }
            else{
                $("#cylinder_div").show();
            }
        }
        else if (name.trim() == 'Roll Form') {
            $("#pillow_bag").css("display","none");
            $("#gaz_bag").css("display","none");

            $('.lamination_weight_label').html('Lamination Weight/Sheet');
            $('.bopp_weight').html('BOPP Weight/Sheet');
            $('.fabric_weight_label').html('Fabric Weight/Sheet');
            $('.weight_label').html('Weight/Sheet w/Handle')

            // handle
            $('#handle_title').hide();
            $('#handle_detail').hide();
            
            $("#loop").css("cursor", "none").addClass('disabled-input').removeClass('active').removeClass('btn-outline-success-2');
            $("#u_cut").css("cursor", "none").addClass('disabled-input').removeClass('active').removeClass('btn-outline-success-2');
            $("#d_cut").css("cursor", "none").addClass('disabled-input').removeClass('active').removeClass('btn-outline-success-2');
            $('#loop_w_flap').css("cursor", "none").removeClass('active').removeClass('btn-outline-success-2').addClass('disabled-input');

            $(".printing_type").filter(function() {
                return $(this).val() === "OFFSET";
            }).closest('.col-md-3').show();
            
            if ($(".printing_type").filter(function() {
                    return $(this).val() === "OFFSET" || $(this).val() === "Flexo";
                }).length > 0) {

                $("#cylinder_div").remove();
            }
            else{
                $("#cylinder_div").show();
            }
        }    
        openform2();
    });

    $(document).on("click", ".job_bag_type", function (){
        
        let f_size = $(".fabric_size").val();
        if (f_size == '') {
            f_size = 0;
        }        
        let f_gsm = $(".fabric_gsm").val();
        if (f_gsm == '') {
            f_gsm = 0;
        }        
        let cylinder_circum = $(".c_circum").val() || 1;        

        if (cylinder_circum < 0 || cylinder_circum == '') {
            cylinder_circum = 1;
        }

        var l_gsm = $(".lamination_gsm").val();
        var l_size = $(".fabric_size").val();

        if (l_gsm == '') {
            l_gsm = 0;
        }
        if (l_size == '') {
            l_size = 0;
        }


        let f_weight = (f_gsm * f_size * cylinder_circum / 25.4) / 1550;
        let l_weight = (l_gsm * l_size * cylinder_circum / 25.4) / 1550;

        
        var name = $("#job_type  option:selected").text();        
        if (name == 'Packing Bags') {
            f_weight = (f_gsm * f_size * cylinder_circum / 25.4) / 39.37;
            l_weight = (l_gsm * l_size * cylinder_circum / 25.4) / 39.37;            
            
        }

        var bag_type = $(this).text();
        if (bag_type.trim() == 'Backseam') {
            f_weight = ((f_gsm * f_size * cylinder_circum / 25.4) / 39.37) / 2;  
            l_weight = ((l_gsm * f_size * cylinder_circum / 25.4) / 39.37) / 2;          
        }
        $(".fabric_weight").val(f_weight ? f_weight.toFixed(2) : 0);
        $(".lamination_weight").val(l_weight ? l_weight.toFixed(2) : 0);
    })



    $(document).on("click", ".printing_type", function(e) {
        var val = $(this).val();
        $(this).addClass("selected_print");
        $(this).removeClass("btn-success-2");
        $(".print_select").val(val);

        if ((val == "BOPP") || (val == "OFFSET") || (val == "FLEXO On BOPP")) {

            // $('#handle_type').val('');
            $("#u_cut").css("cursor", "none").addClass('disabled-input').removeClass('active').removeClass('btn-outline-success-2');
        } else {
            let name = $(".job_bag_select").val(); 
            if (name.trim() == 'Box Bag' || name.trim() == 'Insulated Box Bag') {
                $("#u_cut").css("cursor", "none").addClass('disabled-input').removeClass('active').removeClass('btn-outline-success-2');
            }
            else{
                $("#u_cut").css("cursor", "pointer").removeClass('disabled-input').addClass('btn-outline-success-2');
            }
        }

        if (val == "FLEXO" ) {
            $("#bopp_title").css("display", "none");
            $("#bopp_detail").css("display", "none");
            $("#bopp_extra_detail").css("display", "none");            
        } else {
            $("#bopp_title").css("display", "flex");
            $("#bopp_detail").css("display", "flex");
            $("#bopp_extra_detail").css("display", "flex");            
        }

        if ( val == 'FLEXO' || val == 'OFFSET') {
            $("#bopp_metal").css("display", "none");
        }
        else{
            $("#bopp_metal").css("display", "block");
        }

        if (val == "FLEXO On BOPP") {
            $(".hr").show();
            $("#flexo_bopp").css("display", "flex");
            $("#flexo_bopp_detail").css("display", "flex");
        } else {
            $("#flexo_bopp").css("display", "none");
            $("#flexo_bopp_detail").css("display", "none");
        }

        $("#title_print").text("Cylinder");
        $("#label_print").text("No. oF Cylinder :");
        $("#label_shell").text("Shell Size :");
        $("#label_repeat").css("display","block");

        openform2();
    });

    $(document).on("change","#job_type",function(){       
        var name = $("#job_type  option:selected").text();   
        
        if (name == 'Shopping Bags') {
            $('.bag_select_1').show();
            $('.bag_select_2').hide();
            $('.bottom_enclave').hide();

            $('.handle_type_2').hide();
            $('.handle_type_1').show();
            $('#printingTypes2').hide();          
            $('#printingTypes').show();   
            
            $('#shopping_bag_fabric').show();
            $('#packing_bag_fabric').hide();

            $('#fabric_item_code').val('');
        }
        else{
            $('.bag_select_1').hide();
            $('.bag_select_2').show();
            $('.bottom_enclave').show();

            $('.handle_type_2').show();
            $('.handle_type_1').hide();  
            $('#printingTypes2').show();          
            $('#printingTypes').hide();  
            
            $('#shopping_bag_fabric').hide();
            $('#packing_bag_fabric').show();            

            $('#fabric_item_code_pp').val('');
        }
        
        $('#job_bag_type').val('');
        $('#handle_type').val('');
        $('.form-1').show();
        openform2();        
    });

    $(document).on("click", ".bag_type", function(e) {
        var val = $(this).val();                
        
        if (val == 'NIWAR') {
            $('.handle_gsm').hide();
            $('.handle_weight').val('').prop('readonly', false);
        }
        else if (val == 'C-PUNCH') {
            $('.handle_gsm').show();
            $('.handle_weight').val('0').prop('readonly', true)    ;
        }
        else{
            $('.handle_gsm').show();
        }


        if (val == 'C-PUNCH' || val == 'NONE') {
            $('#handle-weight-bag-div').hide().find('input, select, textarea').val('').prop('checked', false).prop('selected', false);
        }
        else{
            $('#handle-weight-bag-div').show();
        }
        
        

        $(".bag_type").removeClass("selected_bag");
        $(".bag_type").removeClass("active");        
        $(".bag_type").addClass("btn-outline-success-2");
        $(this).addClass("selected_bag");
        $(this).addClass("active");
        $(".bag_select").val(val);

        var printing_type = $(".print_select").val();
        var job_type = $('#job_type').val().trim();
        
        
        if (((printing_type == "BOPP") && (val == "D-CUT")) || ((printing_type == "FLEXO") && (val == "D-CUT")) || ((printing_type == "OFFSET") && (val == "D-CUT")) || ((printing_type == "FLEXO") && (val == "U-CUT"))) {            
            $("#handle_detail").css("display", "none");
            $("#handle_title").css("display", "none");
            // $("#cut_detail").show().css("display", "flex");
            // $("#cut_title").show().css("display", "flex");
        } else if (job_type == 'Packing Bags') {
            $("#handle_detail").css("display", "flex");
            $("#handle_title").css("display", "flex");
        }
        else {
            $("#handle_detail").css("display", "flex");
            $("#handle_title").css("display", "flex");
            // $("#cut_detail").css("display", "none");
            // $("#cut_title").css("display", "none");
        }



        if (((printing_type == "FLEXO") && (val == "LOOP")) || ((printing_type == "FLEXO") && (val == "D-CUT")) || ((printing_type == "FLEXO") && (val == "D-CUT"))) {
            $("#title_print").text("Block");
            $("#label_print").text("No. oF Block :");
            $("#label_shell").text("Block Size :");
            $("#label_repeat").css("display","none");
        } else if (((printing_type == "OFFSET") && (val == "LOOP")) || ((printing_type == "OFFSET") && (val == "D-CUT"))) {
            $("#title_print").text("Plate");
            $("#label_print").text("No. oF Plate :");
            $("#label_shell").text("Plate Size :");
            $("#label_repeat").css("display","none");
        }

        if ((printing_type == "BOPP") || (printing_type == "OFFSET") || (printing_type == "FLEXO On BOPP")) {

            $("#u_cut").css("cursor", "none").addClass('disabled-input').removeClass('active');
        } else {

            $("#u_cut").css("cursor", "pointer").removeClass('disabled-input');
        }

        openform2();
    });

    function openform2(){
        let job_type = $('#job_type').val();
        let job_bag_type = $('#job_bag_type').val();
        let printing_type = $('#printing_type').val();
        let handle_type = $('#handle_type').val(); 
        
        // let metal_checked = $('#metal').is(':checked');
        // let non_metal_checked = $('#non-metal').is(':checked');

        let metal_value = $('#bopp_metal_type').val().trim();

        let bottom_enclave = $('#bottom_enclave').val().trim();

        // let job_name = $('#job_name').val().trim();
        // let party_name = $('#party_name').val().trim();
        console.log(job_type, job_bag_type, printing_type, handle_type);

        let handle_valid = false;
        let allDisabled = false;

        if (job_type == 1 && handle_type == '') {
            allDisabled = true;
            let handles = $('.handle_type_1 .bag_type');
            console.log(handles);

            handles.each(function (){
                if (!$(this).hasClass('disabled-input')) {
                    allDisabled = false;
                    return false;
                }
            })
        }

        if (allDisabled) {
            handle_valid = true;
        }
        

        let valid = job_type != '' && job_bag_type != '' && printing_type != '' && (handle_type != '' || handle_valid);


        
        if (job_type == '3') {
            valid = valid && (bottom_enclave != '');
        }

        if (printing_type != 'FLEXO' && printing_type != 'OFFSET') {
            valid = valid && (metal_value !== '');
        }
        
        if (valid) {                        
            $('.form-2').show();
        }
        else{
            $('.form-2').hide();
        }
    }


    let partySuggetions = [];
    let jobNameSuggetions = [];

    $(document).on("blur", ".bag_job_size", function(e) {
        var bag_size_value = $(this).val();    
        var convert_inch = bag_size_value / 25.4;        

        $("#job_size").text(convert_inch.toFixed(2) + "inch").css("font-weight", "bold");
        $("#gaz_bag #job_size").text(convert_inch.toFixed(2) + "inch").css("font-weight", "bold");
    });

    $(document).on("blur", ".bag_pet_size", function(e) {
        var bag_size_value = $(this).val();        
        var convert_inch = bag_size_value / 25.4;

        $("#bag_pet_inch").text(convert_inch.toFixed(2) + "inch").css("font-weight", "bold");
        $("#gaz_bag #bag_pet_inch").text(convert_inch.toFixed(2) + "inch").css("font-weight", "bold");
    });
    
    $(document).on("blur", ".bag_gaz_size", function(e) {
        var bag_size_value = $(this).val();        
        var convert_inch = bag_size_value / 25.4;

        $("#bag_gazette_inch").text(convert_inch.toFixed(2) + "inch").css("font-weight", "bold");
    });

    // $('#party_name').on('keyup',function (event){
    //     let query = $(this).val();
    //     if (event.key === 'Enter') {
    //         event.preventDefault();

    //         if (partySuggestions.length > 0) {
    //             // Set input to first suggestion if not already selected
    //             const currentVal = $('#party_name').val().toLowerCase();
    //             const exactMatch = partySuggestions.find(p => p.party_name.toLowerCase() === currentVal);

    //             if (!exactMatch) {
    //                 $('#party_name').val(partySuggestions[0].party_name);
    //             }
    //             $('.party_name_suggestions').hide();
    //         }   
    //         return false; // Prevent AJAX from firing again on Enter
    //     }
    //     if (query.length > 1) {            
    //         $.ajax({
    //             type: 'GET',
    //             url: '{{route("getPartyDetails")}}',
    //             data: {party_name: query},
    //             success: function(data){                    
    //                 let party_name_error = $('.party_name_error');
                    
    //                 if (data.status == true && data.party.length > 0) {  
    //                     partySuggestions = data.party; // Store globally                      
    //                     let html = '';
    //                     data.party.forEach(party => {
    //                         html += `<li style="list-style:none; padding:10px; cursor:pointer;" onclick="upodatePartyVal(this)">${party.party_name}</li>`
    //                     });                    
        
    //                     $('.party_name_suggestions').html(html).show();
    //                     party_name_error.hide();
    //                 }
    //                 else{
    //                     // alert('Please Enter correct Party name');

    //                     // let currentVal = $('#party_name').val(); // assuming your input has id="party_name"
    //                     // $('#party_name').val(currentVal.slice(0, -1));

    //                     $('#party_name').val(query.slice(0, -1));
    //                     // $('.party_name_suggestions').hide();
    //                     // party_name_error.html('Please Enter correct Party name').show();
    //                 }
    
    //             }
    //         })
    //     }
    // })


    const formcontrols = document.querySelectorAll('.form-control:not(.coor-inpu):not(.cmyk-input)');
    
    formcontrols.forEach((item, index) => {
        item.addEventListener('keydown', function(e) {
            if (e.key == 'Enter') {
                // e.preventDefault();                
                for (let i = index + 1; i < formcontrols.length; i++) {
                    const next = formcontrols[i];                                        
                    if (!next.hasAttribute('readonly') || next.has) {
                        next.focus();
                        break;
                    }
                }
                $('.suggestions').hide();
            }
        });
    });


    let partySuggestions = [];
    let selectedPartyIndex = -1;

    $('#party_name').on('input keydown', function (event) {        

        if (event.key === 'Backspace' || event.key === 'Delete') {
            return;
        }
         // Navigate suggestions with arrow keys
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
            let items = $('.party_name_suggestions li');            

            if (items.length === 0) return;

            // Remove existing highlight
            items.removeClass('highlight');

            if (event.key === 'ArrowDown') {                
                selectedPartyIndex = (selectedPartyIndex + 1) % items.length;
                $('#party_name').val(items.eq(selectedPartyIndex).text());
            } else if (event.key === 'ArrowUp') {

                if (selectedPartyIndex == 0) {
                    $('#party_name').focus();
                    selectedPartyIndex = -1;
                    return;
                }
                selectedPartyIndex = (selectedPartyIndex - 1 + items.length) % items.length;
                $('#party_name').val(items.eq(selectedPartyIndex).text());
            }

            // Add highlight to the selected item
            items.eq(selectedPartyIndex).addClass('highlight');
            return;
        }

        // On Enter key
        if (event.key === 'Enter') {
            event.preventDefault();
            const items = $('.party_name_suggestions li');

            partySuggestions = [];


            partySuggestions = Array.from(items).map(item => $(item).text().trim());
                    
            $('.party_name_suggestions').hide();

            if (items.length > 0 && selectedPartyIndex >= 0) {
                $('#party_name').val(items.eq(selectedPartyIndex).text());
            } else if (partySuggestions.length > 0) {
                const currentVal = $('#party_name').val().toLowerCase();
                const exactMatch = partySuggestions.find(p => p.toLowerCase() === currentVal);

                if (!exactMatch) {
                    $('#party_name').val(partySuggestions[0]);
                }
            }
            
            selectedPartyIndex = -1;                                             
            openform2();
            return false;
        }

        // Reset index
        selectedPartyIndex = -1;

    
        let query = $(this).val();        

        if (query.length == 0) {
            // $('#party_name').val('');
            $('.party_name_suggestions').hide();
            // partySuggestions = [];
            return;
        }
        if (query.length >= 1) {
            $.ajax({
                type: 'GET',
                url: '{{ route("getPartyDetails") }}',
                data: { party_name: query },
                success: function (data) {
                    let party_name_error = $('.party_name_error');

                    if (data.status === true && data.party.length > 0) {
                        partySuggestions = data.party;
                        let html = '';

                        data.party.forEach(party => {
                            html += `<li style="list-style:none; padding:10px; cursor:pointer;" onclick="updatePartyVal(this)">${party.party_name}</li>`;
                        });

                        $('.party_name_suggestions').html(html).show();
                        party_name_error.hide();
                    } else {
                        $('#party_name').val(query.slice(0, -1));
                    }
                }
            });
        } else {
            $('#party_name').val('');
            $('.party_name_suggestions').hide();
        }



       
    });

    
    $('#job_name').on('blur', function (){                
        setTimeout(() => {
            if (jobNameSuggetions.length > 0) {
                let currentVal = $('#job_name').val();
                let exactMatch = jobNameSuggetions.find(j => j.job_name.toLowerCase() === currentVal.toLowerCase());

                if (!exactMatch) {
                    $('#job_name').val(jobNameSuggetions[0].job_name);
                    $('.job_name_suggestions').hide();
                }
            }
        }, 200);
        openform2();
    })

    function updatePartyVal(elem){
        let party_name = $(elem).text();
        $('#party_name').val(party_name);
        $('.party_name_suggestions').hide();
        openform2();
    }


    function upodateJobVal(elem){
        let job_name = $(elem).text();
        $('#job_name').val(job_name);
        $('.job_name_suggestions').hide();
    }


    
    function updateBoppSuggestions(elem) {
        var item_code = $(elem).val();                                            

        $.ajax({
            type: "GET",
            url: "{{ route('bopp_item_code_suggestions') }}",
            data: { item_code: item_code },
            success: function(data) {
                let html = '';
                if (data.status == true && data.boppItems.length > 0) {                                                        
                    data.boppItems.forEach((item) => {
                        html += `<li onclick="selectBoppItem(this)" style="cursor:pointer; padding:10px;">${item.item_code}</li>`;
                    });
                    $('.bopp-item-suggestions').html(html).show();
                } else {
                    $('.bopp-item-suggestions').html('').hide();
                }
            }
        });
    }

    function updateMetalSuggestions(elem) {
        var item_code = $(elem).val();                                            

        $.ajax({
            type: "GET",
            url: "{{ route('bopp_item_code_suggestions') }}",
            data: { item_code: item_code },
            success: function(data) {
                let html = '';
                if (data.status == true && data.boppItems.length > 0) {                                                        
                    data.boppItems.forEach((item) => {
                        html += `<li onclick="selectMetalItem(this)" style="cursor:pointer; padding:10px;">${item.item_code}</li>`;
                    });
                    $('.metal-item-suggestions').html(html).show();
                } else {
                    $('.metal-item-suggestions').html('').hide();
                }
            }
        });
    }

    function updateFabricSuggestions(elem) {
        var item_code = $(elem).val();  
        
        if (item_code.length <= 0) {
            console.log('---------><---');            
            console.log(item_code);
            
            $('.fabric-item-suggestions').html('').hide();
            return ;
        }

        $.ajax({
            type: "GET",
            url: "{{ route('fabric_item_code_suggestions') }}",
            data: { item_code: item_code },
            success: function(data) {
                let html = '';
                if (data.status == true && data.fabricItems.length > 0) {                                                        
                    data.fabricItems.forEach((item) => {
                        html += `<li onclick="selectFabricItem(this)" style="cursor:pointer; padding:10px;">${item.item_code}</li>`;
                    });
                    $('.fabric-item-suggestions').html(html).show();
                } else {
                    $('.fabric-item-suggestions').html('').hide();
                }
            }
        });
    }

    function updateFabricPPSuggestions(elem) {
        var item_code = $(elem).val();  
        
        if (item_code.length <= 0) {
            console.log('---------><---');            
            console.log(item_code);
            
            $('.fabric-item-suggestions-pp').html('').hide();
            return ;
        }

        $.ajax({
            type: "GET",
            url: "{{ route('fabric_item_code_pp_suggestions') }}",
            data: { item_code: item_code },
            success: function(data) {
                let html = '';
                if (data.status == true && data.fabricItems.length > 0) {                                                        
                    data.fabricItems.forEach((item) => {
                        html += `<li onclick="selectFabricPPItem(this)" style="cursor:pointer; padding:10px;">${item.item_code}</li>`;
                    });
                    $('.fabric-item-suggestions-pp').html(html).show();
                } else {
                    $('.fabric-item-suggestions-pp').html('').hide();
                }
            }
        });
    }



    function selectBoppItem(el) {
        
        $(".bopp_item_code").val($(el).text().trim());
        $('.bopp-item-suggestions').hide().html('');
        console.log('2');
        
        updateBoppcodes();
    }

    function selectMetalItem(li) {        
        $('.metal-item-suggestions').html('').hide();
        $('#metal_item_code').val(li.innerText);
        // $('#bopp_item_code').trigger('input'); // trigger to re-run AJAX                
        updateMetalcodes();
    }

    function selectFabricItem (li) {
        $('#fabric_item_code').val(li.innerText);
        // $('#fabric_item_code').trigger('input'); // trigger to re-run AJAX        
        updateFibrecodes();
        $('.fabric-item-suggestions').html('').hide();
    }

    function selectFabricPPItem (li) {
        $('#fabric_item_code_pp').val(li.innerText);
        // $('#fabric_item_code').trigger('input'); // trigger to re-run AJAX        
        updateFibrePPcodes();
        $('.fabric-item-suggestions-pp').html('').hide();
    }

    

    $(".bopp_item_code").on('input', function () {        
        updateBoppSuggestions(this);
        updateBoppcodes();
    });

    $(".fabric_item_code").on('input', function (){
        updateFabricSuggestions(this);
        updateFibrecodes();
    })

    $(".fabric_item_code_pp").on('input', function (){
        updateFabricPPSuggestions(this);
        updateFibrePPcodes();
    })

    $(".metal_item_code").on('input', function (){
        updateMetalSuggestions(this);
        updateMetalcodes();                
    })

    function updateBoppcodes(){
        let bopp_item_code = $('#bopp_item_code').val()
            
        $.ajax({
            type: "GET",
            url: "{{ route('check-bopp-item') }}",
            data: { bopp_item_code: bopp_item_code },
            success: function(data) {                            
                if (data.status == true && data.item != null) {
                    $(".bopp-cat").val(data.item.bopp_category);
                    $(".bopp_size").val(data.item.bopp_size);
                    $(".bopp_micron").val(data.item.bopp_micron);

                    let b_size = Number(data.item.bopp_size) || 1;
                    let b_micron = Number(data.item.bopp_micron) || 1;
                    
                    let cylinder_circum = Number($(".c_circum").val());
                    if (cylinder_circum < 0 || cylinder_circum == '') {
                        cylinder_circum = 1;
                    }
                    let repeat_cylinder = Number($(".repeat_cylinder").val()) || 1;
                    let coil_cylinder = Number($(".coil_cylinder").val()) || 1;

                    var b_weight = (b_micron * .91 * b_size * cylinder_circum / 1550) / 645.16;
 
                    // Get other weights
                    let h_weight = Number($(".handle_weight").val()) || 0;
                    // let c_weight = Number($(".cut_wastage").val()) || 0;
                    let ll_weight = Number($(".lamination_weight").val()) || 0;
                    let ff_weight = Number($(".fabric_weight").val()) || 0;
                    let mm_weight = Number($(".metal_weight").val()) || 0;

                    // Adjust per cylinder repeat
                    b_weight = b_weight / repeat_cylinder / coil_cylinder;
                    ll_weight = ll_weight / repeat_cylinder / coil_cylinder;
                    ff_weight = ff_weight / repeat_cylinder / coil_cylinder;
                    mm_weight = mm_weight / repeat_cylinder / coil_cylinder;

                    $(".bopp_weight").val(b_weight ? b_weight.toFixed(2) : 0);
                    // $(".bopp_weight").val(b_weight);

                    // let t_weight = h_weight + ll_weight + ff_weight + b_weight + mm_weight - c_weight;
                    let t_weight = h_weight + ll_weight + ff_weight + b_weight + mm_weight;
                                        
                    $(".total_bag_weight").val(t_weight.toFixed(2));

                    // Enable buttons
                    $(".add-issue, .add-save").prop("disabled", false).css("cursor", "pointer");
                }
                else{
                    $(".bopp-cat").val('');
                    $(".bopp_size").val('');
                    $(".bopp_micron").val('');
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    function updateFibrecodes(){
        var bopp_item_code = $('#fabric_item_code').val();
        
        $.ajax({
            type: "GET",
            url: "{{route('check-fibre-item')}}",
            data: {item_code: bopp_item_code},
            success: function(data){                
                if (data.status == true && data.item != null) {
                    
                    $(".pp_cat").val(data.item.category_name);
                    $(".fabric_size").val(data.check_item.non_size);
                    $(".fabric_gsm").val(data.check_item.non_gsm);

                    var f_size = data.check_item.non_size;
                    var f_gsm = data.check_item.non_gsm;
                    var cylinder_circum = $(".c_circum").val() || 1;
                                        
                    var repeat_cylinder = $(".repeat_cylinder").val() || 1;
                    let coil_cylinder = Number($(".coil_cylinder").val()) || 1;



                    
                    
                    var f_weight = (f_gsm * f_size * cylinder_circum / 25.4) / 1550;                    
                    var h_weight = $(".handle_weight").val();
                    // var c_weight = $(".cut_wastage").val();
                    var ll_weight = $(".lamination_weight").val();
                    var ff_weight = f_weight.toFixed(2);
                    var bb_weight = $(".bopp_weight").val();
                    var mm_weight = $(".metal_weight").val();

                    if (repeat_cylinder >0) {
                        var f_weight = f_weight / repeat_cylinder / coil_cylinder;
                        var ll_weight = ll_weight / repeat_cylinder / coil_cylinder;
                        var ff_weight = ff_weight / repeat_cylinder / coil_cylinder;
                        var bb_weight = bb_weight / repeat_cylinder / coil_cylinder;
                        var mm_weight = mm_weight / repeat_cylinder / coil_cylinder;
                    }                                      
                    $(".fabric_weight").val(f_weight ? f_weight.toFixed(2) : 0);
                    if (mm_weight == '') {
                        var mm_weight = 0.0;
                    }
                    if (h_weight == '') {
                        var h_weight = 0.0;
                    }
                    // if (c_weight == "") {
                    //     var c_weight = 0.0;
                    // }
                    if (bb_weight == "") {
                        var bb_weight = 0.0;
                    }

                    // var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight) - parseFloat(c_weight);
                    var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight);
                    
                    
                    $(".total_bag_weight").val(t_weight.toFixed(2));
                    $(".add-issue").prop("disabled", false).css("cursor", "pointer")
                    $(".add-save").prop("disabled", false).css("cursor", "pointer")
                }
                else{
                    $(".pp_cat").val('');
                    $(".fabric_size").val('');
                    $(".fabric_gsm").val('');
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        })
    }

    function updateFibrePPcodes(){
        var bopp_item_code = $('#fabric_item_code_pp').val();
        console.log(`Bopp item code  : ${bopp_item_code}`);
        
        
        $.ajax({
            type: "GET",
            url: "{{route('check-fibre-pp-item')}}",
            data: {item_code: bopp_item_code},
            success: function(data){                
                console.log(data);
                if (data.status == true && data.item != null) {                                    
                    
                    $(".pp_cat").val(data.item.category_name);
                    $(".fabric_size").val(data.check_item.pp_size);
                    $(".fabric_gsm").val(data.check_item.pp_gms);

                    var f_size = data.check_item.pp_size;
                    var f_gsm = data.check_item.pp_gms;
                    var cylinder_circum = $(".c_circum").val() || 1;
                    
                    var repeat_cylinder = $(".repeat_cylinder").val() || 1;
                    let coil_cylinder = Number($(".coil_cylinder").val()) || 1;
                    
                    var f_weight = (f_gsm * f_size * cylinder_circum / 25.4) / 39.37;

                    var job_bag_type = $('#job_bag_type').val();        
                    if (job_bag_type.trim() == 'Backseam') {            
                        f_weight = ((f_gsm * f_size * cylinder_circum / 25.4) / 39.37) / 2;                                    
                    }
                

                    var h_weight = $(".handle_weight").val();
                    // var c_weight = $(".cut_wastage").val();
                    var ll_weight = $(".lamination_weight").val();
                    var ff_weight = f_weight.toFixed(2);
                    var bb_weight = $(".bopp_weight").val();
                    var mm_weight = $(".metal_weight").val();

                    if (repeat_cylinder >0) {
                        var f_weight = f_weight / repeat_cylinder / coil_cylinder;
                        var ll_weight = ll_weight / repeat_cylinder / coil_cylinder;
                        var ff_weight = ff_weight / repeat_cylinder / coil_cylinder;
                        var bb_weight = bb_weight / repeat_cylinder / coil_cylinder;
                        var mm_weight = mm_weight / repeat_cylinder / coil_cylinder;
                    }                    
                    $(".fabric_weight").val(f_weight ? f_weight.toFixed(2) : 0);
                    if (mm_weight == '') {
                        var mm_weight = 0.0;
                    }
                    if (h_weight == '') {
                        var h_weight = 0.0;
                    }
                    // if (c_weight == "") {
                    //     var c_weight = 0.0;
                    // }
                    if (bb_weight == "") {
                        var bb_weight = 0.0;
                    }

                    // var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight) - parseFloat(c_weight);
                    var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight);
                    
                    
                    $(".total_bag_weight").val(t_weight.toFixed(2));
                    $(".add-issue").prop("disabled", false).css("cursor", "pointer")
                    $(".add-save").prop("disabled", false).css("cursor", "pointer")
                }
                else{
                    $(".pp_cat").val('');
                    $(".fabric_size").val('');
                    $(".fabric_gsm").val('');
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        })
    }

    function updateMetalcodes(){
        var bopp_item_code = $(".metal_item_code").val();
        $.ajax({
            type: "GET",
            url: "{{route('check-bopp-item')}}",
            data: {bopp_item_code: bopp_item_code},
            success: function(data){                
                if (data.status == true && data.item != null) {
                    
                    $(".metal_type").val(data.item.bopp_category);
                    $(".metal_size").val(data.item.bopp_size);
                    $(".metal_micron").val(data.item.bopp_micron);

                    var m_size = data.item.bopp_size || 1;
                    var m_micron = data.item.bopp_micron || 1;
                    var cylinder_circum = $(".c_circum").val() || 1;
                    
                    var repeat_cylinder = $(".repeat_cylinder").val() || 1;
                    let coil_cylinder = Number($(".coil_cylinder").val()) || 1;
                    var m_weight = (Number(m_micron) * 0.91 * Number(m_size) * Number(cylinder_circum) / 1550) / 645.16;                                        
                    
                    var h_weight = $(".handle_weight").val() || 0;
                    // var c_weight = $(".cut_wastage").val();
                    var ll_weight = $(".lamination_weight").val() || 0;
                    var ff_weight = $(".fabric_weight").val() || 0;
                    var bb_weight = $(".bopp_weight").val() || 0;
                    var mm_weight = m_weight.toFixed(2) || 0;

                    if (repeat_cylinder >0) {
                        var b_weight = bb_weight / repeat_cylinder / coil_cylinder;
                        var ll_weight = ll_weight / repeat_cylinder / coil_cylinder;
                        var ff_weight = ff_weight / repeat_cylinder / coil_cylinder;
                        var bb_weight = bb_weight / repeat_cylinder / coil_cylinder;
                        var mm_weight = mm_weight / repeat_cylinder / coil_cylinder;
                    }                    
                    $(".metal_weight").val(m_weight ? m_weight.toFixed(2) : 0);
                    if (mm_weight == '') {
                        var mm_weight = 0.0;
                    }
                    if (h_weight == '') {
                        var h_weight = 0.0;
                    }
                    // if (c_weight == "") {
                    //     var c_weight = 0.0;
                    // }
                    if (bb_weight == "") {
                        var bb_weight = 0.0;
                    }

                    // var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight) - parseFloat(c_weight);
                    var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight);                    
                    $(".total_bag_weight").val(t_weight.toFixed(2));
                    $(".add-issue").prop("disabled", false).css("cursor", "pointer")
                    $(".add-save").prop("disabled", false).css("cursor", "pointer")
                }
                else{
                    $(".metal_type").val('');
                    $(".metal_size").val('');
                    $(".metal_micron").val('');
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        })
    }

    
    let selectedBoppIndex = -1;
    let boppSuggestions = [];
    $(".bopp_item_code").on('keydown', function (e) {
        const $input = $(this);
        let items = $('.bopp-item-suggestions li');
        
        

        // Arrow navigation
        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
            e.preventDefault();

            if (items.length === 0) return;

            // Remove existing highlights
            items.removeClass('highlight');

            if (e.key === 'ArrowDown') {
                selectedBoppIndex = (selectedBoppIndex + 1) % items.length;
            } else if (e.key === 'ArrowUp') {
                if (selectedBoppIndex == 0) {
                    $('#bopp_item_code').focus();
                    selectedBoppIndex = -1;
                    return;
                }
                selectedBoppIndex = (selectedBoppIndex - 1 + items.length) % items.length;
            }

            const selectedText = items.eq(selectedBoppIndex).text().trim();
            $input.val(selectedText);

            items.eq(selectedBoppIndex).addClass('highlight');
            return;
        }

        // Enter key to select item
        if (e.key === 'Enter') {
            e.preventDefault();


            boppSuggestions = [];

            boppSuggestions = Array.from(items).map(item => $(item).text().trim());
                    
            if (items.length > 0 && selectedBoppIndex >= 0) {
                let selectedText = items.eq(selectedBoppIndex).text().trim();
                $input.val(selectedText);
            } else if (boppSuggestions.length > 0) {
                const currentVal = $input.val().toLowerCase();
                const exactMatch = boppSuggestions.find(b => b.toLowerCase() === currentVal);
                
                if (!exactMatch) {
                    $input.val(boppSuggestions[0]);
                }
            }

            $('.bopp-item-suggestions').hide().html('');
            selectedBoppIndex = -1;
            updateBoppcodes();
            return false;
        }

        // Reset index for any other key
        selectedBoppIndex = -1;
    });
        

    let selectedFabricIndex = -1;
    let fabricSuggestions = [];
    $(".fabric_item_code").on('keydown', function (e){
        const $input = $(this);
        const items = $('.fabric-item-suggestions li');

        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
            e.preventDefault();

            if (items.length === 0) return;

            // Remove existing highlights
            items.removeClass('highlight');

            if (e.key === 'ArrowDown') {
                selectedFabricIndex = (selectedFabricIndex + 1) % items.length;
            } else if (e.key === 'ArrowUp') {
                if (selectedFabricIndex == 0) {
                    $('#fabric_item_code').focus();
                    selectedFabricIndex = -1;
                    return;
                }
                selectedFabricIndex = (selectedFabricIndex - 1 + items.length) % items.length;
            }

            const selectedText = items.eq(selectedFabricIndex).text().trim();
            $input.val(selectedText);

            items.eq(selectedFabricIndex).addClass('highlight');
            return;
        }
        if(e.keyCode == 13){
            e.preventDefault();

            fabricSuggestions = [];

            fabricSuggestions = Array.from(items).map(item => $(item).text().trim());

            if (items.length > 0 && selectedFabricIndex >= 0) {
                const selectedText = items.eq(selectedFabricIndex).text().trim();
                $input.val(selectedText);
            } else if (fabricSuggestions.length > 0) {
                const currentVal = $input.val().toLowerCase();
                const exactMatch = fabricSuggestions.find(b => b.toLowerCase() === currentVal);
                if (!exactMatch) {
                    $input.val(fabricSuggestions[0]);
                }
            }            
            $('.fabric-item-suggestions').html('').hide();
            selectedFabricIndex = -1;
            updateFibrecodes();
        }
        selectedFabricIndex = -1;
    })


    let selectedFabricPPIndex = -1;
    let fabricSuggestionsPP = [];
    $(".fabric_item_code_pp").on('keydown', function (e){
        const $input = $(this);
        const items = $('.fabric-item-suggestions-pp li');

        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
            e.preventDefault();

            if (items.length === 0) return;

            // Remove existing highlights
            items.removeClass('highlight');

            if (e.key === 'ArrowDown') {
                selectedFabricPPIndex = (selectedFabricPPIndex + 1) % items.length;
            } else if (e.key === 'ArrowUp') {
                if (selectedFabricPPIndex == 0) {
                    $('#fabric_item_code_pp').focus();
                    selectedFabricPPIndex = -1;
                    return;
                }
                selectedFabricPPIndex = (selectedFabricPPIndex - 1 + items.length) % items.length;
            }

            const selectedText = items.eq(selectedFabricPPIndex).text().trim();
            $input.val(selectedText);

            items.eq(selectedFabricPPIndex).addClass('highlight');
            return;
        }
        if(e.keyCode == 13){
            e.preventDefault();

            fabricSuggestionsPP = [];

            fabricSuggestionsPP = Array.from(items).map(item => $(item).text().trim());

            if (items.length > 0 && selectedFabricPPIndex >= 0) {
                const selectedText = items.eq(selectedFabricPPIndex).text().trim();
                $input.val(selectedText);
            } else if (fabricSuggestionsPP.length > 0) {
                const currentVal = $input.val().toLowerCase();
                const exactMatch = fabricSuggestionsPP.find(b => b.toLowerCase() === currentVal);
                if (!exactMatch) {
                    $input.val(fabricSuggestionsPP[0]);
                }
            }            
            $('.fabric-item-suggestions-pp').html('').hide();
            selectedFabricPPIndex = -1;
            updateFibrePPcodes();
        }
        selectedFabricPPIndex = -1;
    })

    let selectedMetalIndex = -1;
    let metalSuggestions = [];
    $(".metal_item_code").on('keydown', function (e) {
        const $input = $(this);
        const items = $('.metal-item-suggestions li');

        // Arrow navigation
        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
            e.preventDefault();

            if (items.length === 0) return;

            // Remove existing highlights
            items.removeClass('highlight');

            if (e.key === 'ArrowDown') {
                selectedMetalIndex = (selectedMetalIndex + 1) % items.length;
            } else if (e.key === 'ArrowUp') {
                if (selectedMetalIndex == 0) {
                    $('#metal_item_code').focus();
                    selectedMetalIndex = -1;
                    return;
                }
                selectedMetalIndex = (selectedMetalIndex - 1 + items.length) % items.length;
            }

            const selectedText = items.eq(selectedMetalIndex).text().trim();
            $input.val(selectedText);

            items.eq(selectedMetalIndex).addClass('highlight');
            return;
        }

        // Enter key to select item
        if (e.key === 'Enter') {
            // alert('hii')
            e.preventDefault();

            metalSuggestions = [];

            metalSuggestions = Array.from(items).map(item => $(item).text().trim());

            if (items.length > 0 && selectedMetalIndex >= 0) {
                const selectedText = items.eq(selectedMetalIndex).text().trim();
                $input.val(selectedText);
            } else if (metalSuggestions.length > 0) {
                const currentVal = $input.val().toLowerCase();
                const exactMatch = metalSuggestions.find(b => b.toLowerCase() === currentVal);
                if (!exactMatch) {
                    $input.val(metalSuggestions[0]);
                }
            }

            $('.metal-item-suggestions').hide().html('');
            selectedMetalIndex = -1;
            updateMetalcodes();
            return false;
        }

        // Reset index for any other key
        selectedMetalIndex = -1;
    });

        
    $(".bopp_metal_type").on("click", function() {
        if (this.value == "1") {
            $("#metalise").css("display", "block");
            $("#metal").prop("checked", true);
            $("#non-metal").prop("checked", false);
        } else if (this.value == "0") {
            $("#metalise").css("display", "none");
            $("#metal").prop("checked", false);
            $("#non-metal").prop("checked", true);
        }
    });


    $(document).on("click", ".job_tag", function() {

        var product_job = $(this).text();
        $("#job_name").val(product_job);
        $("#job-name-list").html("");

        if (product_job != "") {

            $.ajax({
                type: "post",
                url: "private/ajax/get-job-variant",
                data: {
                    size: product_job,
                },
                success: function(response) {
                    $("#job_variant").html(response);
                }
            });
        }
    });

    $(document).on("keyup", ".bopp_micron", function() {
        var b_micron = $(this).val() || 1;
        var b_size = $(".bopp_size").val() || 1;
        var cylinder_circum = $(".c_circum").val() || 1;
        
        var b_weight = (b_micron * .91 * b_size * cylinder_circum / 1550) / 645.16;

        $(".bopp_weight").val(b_weight ? b_weight.toFixed(2) : 0);

        var h_weight = $(".handle_weight").val();
        // var c_weight = $(".cut_wastage").val();
        var ll_weight = $(".lamination_weight").val();
        var ff_weight = $(".fabric_weight").val();
        var bb_weight = b_weight.toFixed(2);
        var mm_weight = $(".metal_weight").val();
        if (mm_weight == "") {
            var mm_weight = 0.0;
        }
        if (h_weight == "") {
            var h_weight = 0.0;
        }
        // if (c_weight == "") {
        //     var c_weight = 0.0;
        // }
        // var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight) - parseFloat(c_weight);
        var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight);
        
        $(".total_bag_weight").val(t_weight.toFixed(2));

    });


    $(".bopp_size").on("keyup", function() {
        var b_size = $(this).val() || 1;
        var b_micron = $(".bopp_micron").val() || 1;
        var cylinder_circum = $(".c_circum").val() || 1;

        var b_weight = (b_micron * .91 * b_size * cylinder_circum / 1550) / 645.16;

        $(".bopp_weight").val(b_weight ? b_weight.toFixed(2) : 0);

        var h_weight = $(".handle_weight").val();
        // var c_weight = $(".cut_wastage").val();
        var ll_weight = $(".lamination_weight").val();
        var ff_weight = $(".fabric_weight").val();
        var bb_weight = b_weight.toFixed(2);
        var mm_weight = $(".metal_weight").val();
        if (mm_weight == "") {
            var mm_weight = 0.0;
        }
        if (h_weight == "") {
            var h_weight = 0.0;
        }
        // if (c_weight == "") {
        //     var c_weight = 0.0;
        // }
        // var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight) - parseFloat(c_weight);
        var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight);        
        $(".total_bag_weight").val(t_weight.toFixed(2));


    });


    $(".c_circum").on("keyup", function() {
        var cylinder_circum = $(this).val();
        if (cylinder_circum == '') {
            cylinder_circum == 1;
        }
        var b_micron = $(".bopp_micron").val() || 1;
        var b_size = $(".bopp_size").val() || 1;
        var repeat_cylinder = $(".repeat_cylinder").val() || 1;
        let coil_cylinder = Number($(".coil_cylinder").val()) || 1;
        var b_weight = (Number(b_micron) * 0.91 * Number(b_size) * Number(cylinder_circum) / 1550) / 645.16;
        var m_size = $(".metal_size").val();
        var m_micron = $(".metal_micron").val();
        var m_weight = (m_micron * .91 * m_size * cylinder_circum / 1550) / 645.16;
        var f_gsm = $(".fabric_gsm").val();
        var f_size = $(".fabric_size").val();

        var f_weight = (f_gsm * f_size * cylinder_circum / 25.4) / 1550;

        var l_gsm = $(".lamination_gsm").val();
        var l_size = $(".fabric_size").val();
        var l_weight = (l_gsm * l_size * cylinder_circum / 25.4) / 1550;
        var name = $("#job_type  option:selected").text();
        if (name == 'Packing Bag') {
            f_weight = (f_gsm * f_size * cylinder_circum / 25.4) / 39.37;
            l_weight = (l_gsm * l_size * cylinder_circum / 25.4) / 39.37;
        }

        var job_bag_type = $('#job_bag_type').val();        
        if (job_bag_type.trim() == 'Backseam') {            
            f_weight = ((f_gsm * f_size * cylinder_circum / 25.4) / 39.37) / 2;            
            l_weight = ((l_gsm * l_size * cylinder_circum / 25.4) / 39.37) / 2;
        }
        var h_weight = $(".handle_weight").val();
        // var c_weight = $(".cut_wastage").val();
        var ll_weight = l_weight.toFixed(2)
        var ff_weight = f_weight.toFixed(2)
        var bb_weight = b_weight.toFixed(2)
        var mm_weight = m_weight.toFixed(2)
        if (repeat_cylinder >0) {
            var b_weight = b_weight / repeat_cylinder / coil_cylinder;
            var m_weight = m_weight / repeat_cylinder / coil_cylinder;
            var l_weight = l_weight / repeat_cylinder / coil_cylinder;
            var f_weight = f_weight / repeat_cylinder / coil_cylinder;
            var ll_weight = ll_weight / repeat_cylinder / coil_cylinder;
            var ff_weight = ff_weight / repeat_cylinder / coil_cylinder;
            var bb_weight = bb_weight / repeat_cylinder / coil_cylinder;
            var mm_weight = mm_weight / repeat_cylinder / coil_cylinder;
        }
        $(".bopp_weight").val(b_weight ? b_weight.toFixed(2) : 0);
        $(".metal_weight").val(m_weight ? m_weight.toFixed(2) : 0);
        $(".fabric_weight").val(f_weight ? f_weight.toFixed(2) : 0);
        $(".lamination_weight").val(l_weight ?  l_weight.toFixed(2) : 0);
        if (mm_weight == "") {
            var mm_weight = 0.0;
        }
        if (h_weight == "") {
            var h_weight = 0.0;
        }
        // if (c_weight == "") {
        //     var c_weight = 0.0;
        // }
        if (bb_weight == "") {
            var bb_weight = 0.0;
        }
        // var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight) - parseFloat(c_weight);
        var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight);

        
        $(".total_bag_weight").val(t_weight.toFixed(2));
    });

    $(".repeat_cylinder").on("keyup", function() {
        var repeat_cylinder = $(this).val();
        let coil_cylinder = Number($(".coil_cylinder").val()) || 1;
        var b_micron = $(".bopp_micron").val() || 1;
        var b_size = $(".bopp_size").val() || 1;
        var cylinder_circum = $(".c_circum").val() || 1;
        var b_weight = (Number(b_micron) * 0.91 * Number(b_size) * Number(cylinder_circum) / 1550) / 645.16;                
        
        var m_size = $(".metal_size").val();
        var m_micron = $(".metal_micron").val();
        var m_weight = (m_micron * .91 * m_size * cylinder_circum / 1550) / 645.16;
        var f_gsm = $(".fabric_gsm").val();
        var f_size = $(".fabric_size").val();
        var f_weight = (f_gsm * f_size * cylinder_circum / 25.4) / 1550;
        var name = $("#job_type  option:selected").text();
        var l_gsm = $(".lamination_gsm").val();
        var l_size = $(".fabric_size").val();
        var l_weight = (l_gsm * l_size * cylinder_circum / 25.4) / 1550;
        if (name == 'Packing Bag') {
            f_weight = (f_gsm * f_size * cylinder_circum / 25.4) / 39.37;
            l_weight = (l_gsm * l_size * cylinder_circum / 25.4) / 39.37;
        }

        var job_bag_type = $('#job_bag_type').val();
        if (job_bag_type.trim() == 'Backseam') {
            f_weight = ((f_gsm * f_size * cylinder_circum / 25.4) / 39.37) / 2;
            l_weight = ((l_gsm * l_size * cylinder_circum / 25.4) / 39.37) / 2;
        }
        var h_weight = $(".handle_weight").val();
        // var c_weight = $(".cut_wastage").val();
        var ll_weight = l_weight.toFixed(2)
        var ff_weight = f_weight.toFixed(2)
        var bb_weight = b_weight.toFixed(2)
        var mm_weight = m_weight.toFixed(2)
        if (repeat_cylinder > 0) {
            var b_weight = b_weight / repeat_cylinder / coil_cylinder;
            var m_weight = m_weight / repeat_cylinder / coil_cylinder;
            var l_weight = l_weight / repeat_cylinder / coil_cylinder;
            var f_weight = f_weight / repeat_cylinder / coil_cylinder;
            var ll_weight = ll_weight / repeat_cylinder / coil_cylinder;
            var ff_weight = ff_weight / repeat_cylinder / coil_cylinder;
            var bb_weight = bb_weight / repeat_cylinder / coil_cylinder;
            var mm_weight = mm_weight / repeat_cylinder / coil_cylinder;
        }
        $(".bopp_weight").val(b_weight ? b_weight.toFixed(2) : 0);
        $(".metal_weight").val(m_weight ? m_weight.toFixed(2) : 0);
        $(".fabric_weight").val(f_weight ? f_weight.toFixed(2) : 0);
        $(".lamination_weight").val(l_weight ?  l_weight.toFixed(2) : 0);
        if (mm_weight == "") {
            var mm_weight = 0.0;
        }
        if (h_weight == "") {
            var h_weight = 0.0;
        }
        // if (c_weight == "") {
        //     var c_weight = 0.0;
        // }
        if (bb_weight == "") {
            var bb_weight = 0.0;
        }
        // var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight) - parseFloat(c_weight);
        var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight);
        
        $(".total_bag_weight").val(t_weight.toFixed(2));
    });

    $(".metal_micron").on("keyup", function() {
        var m_micron = $(this).val();
        var m_size = $(".metal_size").val();
        var cylinder_circum = $(".c_circum").val() || 1;
        
        var repeat_cylinder = $(".repeat_cylinder").val() || 1;
        let coil_cylinder = Number($(".coil_cylinder").val()) || 1;
        var m_weight = (m_micron * .91 * m_size * cylinder_circum / 1550) / 645.16;
        var h_weight = $(".handle_weight").val();
        // var c_weight = $(".cut_wastage").val();
        var ll_weight = $(".lamination_weight").val();
        var ff_weight = $(".fabric_weight").val();
        var bb_weight = $(".bopp_weight").val();
        var mm_weight = m_weight.toFixed(2)
        if (repeat_cylinder > 0) {
            var m_weight = m_weight / repeat_cylinder / coil_cylinder;
            var ll_weight = ll_weight / repeat_cylinder / coil_cylinder;
            var ff_weight = ff_weight / repeat_cylinder / coil_cylinder;
            var bb_weight = bb_weight / repeat_cylinder / coil_cylinder;
            var mm_weight = mm_weight / repeat_cylinder / coil_cylinder;
        }
        $(".metal_weight").val(m_weight ? m_weight.toFixed(2) : 0);
        if (mm_weight == "") {
            var mm_weight = 0.0;
        }
        if (h_weight == "") {
            var h_weight = 0.0;
        }
        // if (c_weight == "") {
        //     var c_weight = 0.0;
        // }
        // var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight) - parseFloat(c_weight);
        var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight);
        
        $(".total_bag_weight").val(t_weight.toFixed(2));

    });

    $(".metal_size").on("keyup", function() {

        var m_size = $(this).val();
        var m_micron = $(".metal_micron").val();
        var cylinder_circum = $(".c_circum").val() || 1;
        if (cylinder_circum == '') {
            cylinder_circum == 1;
        }
        var repeat_cylinder = $(".repeat_cylinder").val() || 1;
        let coil_cylinder = Number($(".coil_cylinder").val()) || 1;
        var m_weight = (m_micron * .91 * m_size * cylinder_circum / 1550) / 645.16;
        var h_weight = $(".handle_weight").val();
        // var c_weight = $(".cut_wastage").val();
        var ll_weight = $(".lamination_weight").val();
        var ff_weight = $(".fabric_weight").val();
        var bb_weight = $(".bopp_weight").val();
        var mm_weight = m_weight.toFixed(2)
        if (repeat_cylinder >0) {
            var m_weight = m_weight / repeat_cylinder / coil_cylinder;
            var ll_weight = ll_weight / repeat_cylinder / coil_cylinder;
            var ff_weight = ff_weight / repeat_cylinder / coil_cylinder;
            var bb_weight = bb_weight / repeat_cylinder / coil_cylinder;
            var mm_weight = mm_weight / repeat_cylinder / coil_cylinder;
        }
        $(".metal_weight").val(m_weight ? m_weight.toFixed(2) : 0);
        if (mm_weight == "") {
            var mm_weight = 0.0;
        }
        if (h_weight == "") {
            var h_weight = 0.0;
        }
        // if (c_weight == "") {
        //     var c_weight = 0.0;
        // }

        // var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight) - parseFloat(c_weight);
        var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight);

        
        $(".total_bag_weight").val(t_weight.toFixed(2));

    });

    $(".fabric_gsm").on("keyup", function() {
        var f_gsm = $(this).val();
        var f_size = $(".fabric_size").val();
        var cylinder_circum = $(".c_circum").val() || 1;

        if (cylinder_circum == '') {
            cylinder_circum == 1;
        }

        var f_weight = (f_gsm * f_size * cylinder_circum / 25.4) / 1550;

        var name = $("#job_type  option:selected").text();
        if (name == 'Packing Bag') {
            f_weight = (f_gsm * f_size * cylinder_circum / 25.4) / 39.37;
        }

        $(".fabric_weight").val(f_weight.toFixed(2));
        var h_weight = $(".handle_weight").val();
        // var c_weight = $(".cut_wastage").val();
        var ll_weight = $(".lamination_weight").val();
        var ff_weight = f_weight.toFixed(2);
        var bb_weight = $(".bopp_weight").val();
        var mm_weight = $(".metal_weight").val();
        if (mm_weight == "") {
            var mm_weight = 0.0;
        }
        if (h_weight == "") {
            var h_weight = 0.0;
        }
        // if (c_weight == "") {
        //     var c_weight = 0.0;
        // }
        if (bb_weight == "") {
            var bb_weight = 0.0;
        }
        // var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight) - parseFloat(c_weight);
        var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight);
        
        $(".total_bag_weight").val(t_weight.toFixed(2));

    });

    $(".fabric_size").on("keyup", function() {
        var f_size = $(this).val() || 1;
        var f_gsm = $(".fabric_gsm").val() || 1;
        var cylinder_circum = $(".c_circum").val() || 1;
        var f_weight = (f_gsm * f_size * cylinder_circum / 25.4) / 1550;

        var name = $("#job_type  option:selected").text();
        if (name == 'Packing Bag') {
            f_weight = (f_gsm * f_size * cylinder_circum / 25.4) / 39.37;
        }

        $(".fabric_weight").val(f_weight ? f_weight.toFixed(2) : 0);

        var h_weight = $(".handle_weight").val();
        // var c_weight = $(".cut_wastage").val();
        var ll_weight = $(".lamination_weight").val();
        var ff_weight = f_weight.toFixed(2);
        var bb_weight = $(".bopp_weight").val();
        var mm_weight = $(".metal_weight").val();
        if (mm_weight == "") {
            var mm_weight = 0.0;
        }
        if (h_weight == "") {
            var h_weight = 0.0;
        }
        // if (c_weight == "") {
        //     var c_weight = 0.0;
        // }
        if (bb_weight == "") {
            var bb_weight = 0.0;
        }
        // var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight) - parseFloat(c_weight);
        var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight);
        
        $(".total_bag_weight").val(t_weight.toFixed(2));
    });

    $(".lamination_gsm").on("keyup", function() {
        var l_gsm = Number($(this).val()) || 0;
        var l_size = Number($(".fabric_size").val()) || 0;
        var cylinder_circum = $(".c_circum").val() || 1;
        if (cylinder_circum < 0 || cylinder_circum == '') {
            cylinder_circum = 1;
        }
        
        var repeat_cylinder = Number($(".repeat_cylinder").val());
        let coil_cylinder = Number($(".coil_cylinder").val()) || 1;
        var l_weight = (l_gsm * l_size * cylinder_circum / 25.4) / 1550;

        l_weight = (l_gsm * l_size * cylinder_circum / 25.4) / 39.37;
        

        var name = $("#job_type  option:selected").text();
        if (name == 'Packing Bag') {            
            l_weight = (l_gsm * l_size * cylinder_circum / 25.4) / 39.37;
        }
        
        
        var job_bag_type = $('#job_bag_type').val();
        if (job_bag_type.trim() == 'Backseam') {            
            l_weight = ((l_gsm * l_size * cylinder_circum / 25.4) / 39.37) / 2;
        }
        
            
        var h_weight = $(".handle_weight").val();
        // var c_weight = $(".cut_wastage").val();
        var ll_weight = l_weight.toFixed(2)
        var ff_weight = $(".fabric_weight").val();
        var bb_weight = $(".bopp_weight").val();
        var mm_weight = $(".metal_weight").val();
        if (repeat_cylinder > 0) {
            var l_weight = l_weight / repeat_cylinder;
            var ll_weight = ll_weight / repeat_cylinder;
            var ff_weight = ff_weight / repeat_cylinder;
            var bb_weight = bb_weight / repeat_cylinder;
            var mm_weight = mm_weight / repeat_cylinder;
        }

        if (coil_cylinder > 0) {
            var l_weight = l_weight / coil_cylinder;
            var ll_weight = ll_weight / coil_cylinder;
            var ff_weight = ff_weight / coil_cylinder;
            var bb_weight = bb_weight / coil_cylinder;
            var mm_weight = mm_weight / coil_cylinder;
        }
        $(".lamination_weight").val( l_weight ? l_weight.toFixed(2) : 0);
        if (mm_weight == "") {
            var mm_weight = 0.0;
        }
        if (h_weight == "") {
            var h_weight = 0.0;
        }
        // if (c_weight == "") {
        //     var c_weight = 0.0;
        // }
        if (bb_weight == "") {
            var bb_weight = 0.0;
        }        
        var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight);        
        $(".total_bag_weight").val(t_weight.toFixed(2));
    });


    $(".handle_weight").on("keyup", function() {
        var h_weight = $(this).val();
        var ll_weight = $(".lamination_weight").val();
        var ff_weight = $(".fabric_weight").val();
        var bb_weight = $(".bopp_weight").val();
        // var c_weight = $(".cut_wastage").val() || 0;
        var mm_weight = $(".metal_weight").val();
        if (h_weight == "") {
            var h_weight = 0.0;
        }
        if (ll_weight == "") {
            var ll_weight = 0.0;
        }
        if (ff_weight == "") {
            var ff_weight = 0.0;
        }
        if (mm_weight == "") {
            var mm_weight = 0.0;
        }
        if (bb_weight == "") {
            var bb_weight = 0.0;
        }
                
        var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight);
        
        $(".total_bag_weight").val(t_weight.toFixed(2));

    });

</script>

<script>
    let type = '{{$request_type}}';
    let url = '';
    if (type == 'all') {        
        url = "{{ route('jobdetails.view.all') }}";    
    }
    else if (type == 'pending') {
        url = "{{ route('jobdetails.view.pending') }}";    
    }
    else{
        url = "{{ route('jobdetails.view.saved') }}";
    }
    $(document).ready(function () {
        var UserTable = $('#role-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
                data: function (d) {
                    d.status = $('#status').val();
                }
            },
            lengthMenu: [[100, 150, 200], [100, 150, 200]], // 👈 custom pagination lengths
            pageLength: 100, // 👈 default number of rows to show
            columns: [
                {
                    data: null,
                    name: 'select',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return '<input type="checkbox" class="select-row" value="' + row.id + '">';
                    }
                },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: true, searchable: false  },                
                { data: 'job_unique_code', name: 'job_unique_code', orderable: true, searchable: false  },                
                { data: 'party_name', name: 'party_name' },                
                { data: 'job_name', name: 'job_name', width: '300px' }, 
                { data: 'art_work', name: 'art_work'}, 
                @if ($request_type == 'saved')
                    @if (Auth::user()->role_id == 1)                                           
                    { data: 'saved_by', name: 'saved_by', width: '300px' }, 
                    @endif
                @endif
                { data: 'total_weight', name: 'total_weight', width:'300px' },                                            
                // { data: 'created_at', name: 'created_at', width:'200px' },
                { data: 'action', name: 'action', orderable: false, searchable: false }                                                             
            ],

            order: [[1, 'asc']],
            drawCallback: function (settings) {
                $('#select-all').on('click', function () {
                    var isChecked = this.checked;
                    $('#role-table .select-row').each(function () {
                        $(this).prop('checked', isChecked);
                    });
                });

                $('#delete-selected').on('click', function () {
                    var selectedIds = $('.select-row:checked').map(function () {
                        return this.value;
                    }).get();

                    if (selectedIds.length > 0) {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#006db5',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('jobdetails.deletemulti') }}",
                                    method: 'POST',
                                    data: { selectedIds : selectedIds },
                                    success: function (response) {
                                        UserTable.ajax.reload(); // Refresh the page
                                        Swal.fire(
                                            'Deleted!',
                                            response.success,
                                            'success'
                                        );
                                    },
                                    error: function (xhr) {
                                        Swal.fire(
                                            'Error!',
                                            'Something went wrong.',
                                            'error'
                                        );
                                    }
                                });
                            }
                        })


                    }
                    else {
                        Swal.fire(
                            'Error!',
                            'Please select at least one role to delete.',
                            'error'
                        );
                    }
                })


                $('.status-toggle').on('click', function () {
                    var roleId = $(this).data('id');
                    var status = $(this).is(':checked') ? 1 : 0;
                    updateStatus(roleId, status);
                });
            }
        });

        $('#status').on('change', function () {
            UserTable.ajax.reload();
        });

        $(document).ready(function () {
            $('#export-roles').on('click', function () {
                var status = $('#status').val();
                var url = "{{ route('admin.role.export') }}";
                if (status) {
                    url += "?status=" + status;
                }
                window.location.href = url;
            });
        });


        function updateStatus(roleId, status) {
            $.ajax({
                url: `{{ url('admin/role/update-status') }}/${roleId}`,
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify({ status: status }),
                success: function (data) {
                    if (data.success) {                        
                        Swal.fire(
                            'Updated!',
                            'Status Updated',
                            'success'
                        );
                        // alert('Status Updated !!');

                        // location.reload(); // Refresh the page
                        UserTable.ajax.reload();
                    } else {
                        alert('Failed to update status.');
                    }

                },
                error: function (error) {
                    console.error('Error:', error);
                }
            });
        }
    });

    function openAddModal(){
        $('#users').modal('show');
    }

    function editUser(element){
        var id = $(element).data('id');  
        
        $.ajax({
            url: `{{ route('getJobdetailsEdit') }}`,
            data:{
                id:id,
            },
            method: 'GET',
            success: function(response){  

                console.log(response.data);
                
                            
                let data = response.data;
                let jobdetails = data.jobdetails;
                let party = data.party;
                let jobname = data.jobname;
                let jobtype = data.jobtype;            

                if (jobtype.id == '3') {
                    $('.bag_select_2').show();
                    $('.bottom_enclave').show();
                    $('.bag_select_1').hide();
                    $('.handle_type_1').hide();
                    $('.handle_type_2').show();

                    $('#bottom_enclave').val(jobdetails.bottom_enclave);
                }
                else{
                    $('.bag_select_2').hide();
                    $('.bottom_enclave').hide();
                    $('.bag_select_1').show();
                    $('#bottom_enclave').val('');
                    $('.handle_type_1').show();
                    $('.handle_type_2').hide();
                }

                $('#id').val(jobdetails.id);


                $('#job_date').val(jobdetails.submit_date);
                $('#job_code').val(jobdetails.job_unique_code);
                $('#party_name').val(party.party_name);
                $('#job_name').val(jobname.job_name);
                $('#job_type').val(jobtype.id);
                $('#printing_type').val(jobdetails.printing_type);
                $('.bag_select').val(jobdetails.bag_type);
                $('.bag_pet_size').val(jobdetails.bag_pet);
                $('.bag_job_size').val(jobdetails.bag_circum);
                $('.bag_gaz_size').val(jobdetails.bag_gazette);
                
                $('.total_bag_weight').val(jobdetails.bag_total_weight);

                $('#job_bag_type').val(jobdetails.bag_job_type);

                if ($('#job_bag_type').val() == 'Box Bag' || $('#job_bag_type').val() == 'Insulated Box Bag') {
                    $("#u_cut").css("cursor", "none").addClass('disabled-input').removeClass('active').removeClass('btn-outline-success-2');
                    $("#d_cut").css("cursor", "none").addClass('disabled-input').removeClass('active').removeClass('btn-outline-success-2');
                    $('#gaz_bag').show();
                    $('#pillow_bag').hide();
                }
                else{
                    $("#u_cut").css("cursor", "pointer").removeClass('disabled-input').addClass('btn-outline-success-2');
                    $("#d_cut").css("cursor", "pointer").removeClass('disabled-input').addClass('btn-outline-success-2');
                    $('#gaz_bag').hide();
                    $('#pillow_bag').show();
                }
        
                
                if (jobdetails.bag_gazette > 0) {                    
                    $('#bag_gazette_inch').text((jobdetails.bag_gazette / 25.5).toFixed(2) + ' inches');
                }
                
                $('#widthIninches').text((jobdetails.bag_circum / 25.4).toFixed(2) + ' inches');
                $('#job_size').text((jobdetails.bag_circum / 25.4).toFixed(2) + ' inches');
                
                $('#heightIninches').text((jobdetails.bag_pet / 25.4).toFixed(2) + ' inches');
                $('#bag_pet_inch').text((jobdetails.bag_pet / 25.4).toFixed(2) + ' inches');


                $('#job_description').val(jobdetails.job_description);

                var name = $('#job_bag_type').val(); 
                if(name == 'Pillow bags'){
                    $("#pillow_bag").css("display","block");
                    $("#gaz_bag").css("display","none");
                    $("#gaz_bag input").attr("disabled", "disabled");
                    $("#pillow_bag input").removeAttr("disabled");
                    $("#pillow_handle_type").css("display","flex");
                    $("#gaz_handle_type").css("display","none");
                }
                else if (name == 'Leader box bag' || jobtype.id == '3'){
                    $("#gaz_bag").css("display","block");
                    $("#gaz_handle_type").css("display","block");
                    $("#pillow_bag").css("display","none");
                    $("#pillow_bag input").attr("disabled", "disabled");
                    $("#gaz_bag input").removeAttr("disabled");
                    $("#pillow_handle_type").css("display","none");   
                }

                const colorInputs = document.querySelectorAll('input[name="colors[]"]');

                colorInputs.forEach((input, index) => {
                    if (response.data.colors[index]) {
                        input.value = response.data.colors[index].trim(); // set value and remove leading/trailing space
                    }
                });

                // images
                let kld_images_preview = $('#kld_images_preview');
                let kld_preview = $('#kld_preview');
                let mock_up_images_preview = $('#mock_up_images_preview');
                let suppression_images_preview = $('#suppression_images_preview');
                let approve_images_preview = $('#approve_images_preview');

                let kld_images = data.kld_images;
                let mockup_images = data.mockup_images;
                let approval_images = data.approval_images;
                let separation_images = data.separation_images;

                kld_images_preview.empty();
                kld_preview.empty();

                mock_up_images_preview.empty();
                approve_images_preview.empty();
                suppression_images_preview.empty();

                if (kld_images) {                        
                    let baseUrl = "{{ asset('images/job-images') }}"; // Blade renders this once    
                    kld_images.forEach(item => {
                        const fileName = item.kld_images;
                        const fileUrl = `${baseUrl}/${fileName}`;
                        const fileExtension = fileName.split('.').pop().toLowerCase();

                        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
                            kld_images_preview.append(`
                                <div class="image-wrapper" style="position: relative; display: inline-block; margin-right: 5px;">
                                    <span class="close-btn" data-table="job_kld_images" data-id="${item.id}" onclick="this.parentElement.remove(); removeImage(this)" 
                                        style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; font-size: 14px; cursor: pointer; display:flex;justify-content:center; align-items:center">&times;</span>
                                    <a href="${fileUrl}" data-fancybox="kld_gallery">
                                        <img src="${fileUrl}" style="width:100px; height:auto; cursor:pointer;" alt="Mockup Image">
                                    </a>
                                </div>
                            `);
                        } else if (fileExtension === 'pdf') {
                            kld_preview.append(`
                                <div class="pdf-wrapper" style="position: relative; display: inline-block; margin-right: 5px; padding: 5px; border: 1px solid #ccc;">
                                    <span class="close-btn" data-table="job_kld_images" data-id="${item.id}" onclick="this.parentElement.remove(); removeImage(this)" 
                                        style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; font-size: 14px; cursor: pointer;display:flex;justify-content:center; align-items:center">&times;</span>
                                    <a href="${fileUrl}" data-fancybox="kld_gallery" target="_blank" style="text-decoration: none; color: #007bff;">
                                        ${fileName}
                                    </a>
                                </div>
                            `);
                        }
                    });
                }

                
                
                if (mockup_images) {
                    let baseUrl = "{{ asset('images/job-images') }}";
                    mockup_images.forEach((item, index) => {
                        const fileName = item.job_images;
                        const fileUrl = `${baseUrl}/${fileName}`;
                        const fileExtension = fileName.split('.').pop().toLowerCase();

                        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
                            mock_up_images_preview.append(`
                                <div class="image-wrapper" style="position: relative; display: inline-block; margin-right: 5px;">
                                    <span class="close-btn" data-table="job_images" data-id="${item.id}" onclick="this.parentElement.remove(); removeImage(this)" 
                                        style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; font-size: 14px; cursor: pointer;display:flex;justify-content:center; align-items:center">&times;</span>
                                    <a href="${baseUrl}/${item.job_images}" data-fancybox="mockup_gallery">
                                        <img src="${baseUrl}/${item.job_images}" style="width:100px; height:auto; cursor:pointer;" alt="Mockup Image">
                                    </a>
                                </div>
                            `);
                        }
                        else{
                            mock_up_images_preview.append(`
                                <div class="pdf-wrapper" style="position: relative; display: inline-block; margin-right: 5px; padding: 5px; border: 1px solid #ccc;">
                                    <span class="close-btn" data-table="job_kld_images" data-id="${item.id}" onclick="this.parentElement.remove(); removeImage(this)" 
                                        style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; font-size: 14px; cursor: pointer; display:flex;justify-content:center; align-items:center">&times;</span>
                                    <a href="${fileUrl}" data-fancybox="mockup_gallery" target="_blank" style="text-decoration: none; color: #007bff;">
                                        ${fileName}
                                    </a>
                                </div>
                            `);
                        }


                    });
                }

                if (approval_images) {
                    let baseUrl = "{{ asset('images/job-images') }}";
                                    
                    approval_images.forEach((item, index) => {
                        const fileName = item.approve_image;
                        const fileUrl = `${baseUrl}/${fileName}`;
                        const fileExtension = fileName.split('.').pop().toLowerCase();

                        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
                            approve_images_preview.append(`
                                <div class="image-wrapper" style="position: relative; display: inline-block; margin-right: 5px;">
                                    <span class="close-btn" data-table="job_approve_image" data-id="${item.id}" onclick="this.parentElement.remove(); removeImage(this)" 
                                        style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; font-size: 14px; cursor: pointer;display:flex;justify-content:center; align-items:center">&times;</span>
                                    <a href="${baseUrl}/${item.approve_image}" data-fancybox="approve_gallery">
                                        <img src="${baseUrl}/${item.approve_image}" style="width:100px; height:auto; cursor:pointer;" alt="Approval Image">
                                    </a>
                                </div>
                            `);
                        }
                        else{
                            approve_images_preview.append(`
                                <div class="pdf-wrapper" style="position: relative; display: inline-block; margin-right: 5px; padding: 5px; border: 1px solid #ccc;">
                                    <span class="close-btn" data-table="job_kld_images" data-id="${item.id}" onclick="this.parentElement.remove(); removeImage(this)" 
                                        style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; font-size: 14px; cursor: pointer;display:flex;justify-content:center; align-items:center">&times;</span>
                                    <a href="${fileUrl}" data-fancybox="approve_gallery" target="_blank" style="text-decoration: none; color: #007bff;">
                                        ${fileName}
                                    </a>
                                </div>
                            `);
                        }

                    });

                }

                if (separation_images) {
                    let baseUrl = "{{ asset('images/job-images') }}";

                    separation_images.forEach((item, index) => {
                        const fileName = item.approve_image;
                        const fileUrl = `${baseUrl}/${fileName}`;
                        const fileExtension = fileName.split('.').pop().toLowerCase();
                        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
                            suppression_images_preview.append(`
                                <div class="image-wrapper" style="position: relative; display: inline-block; margin-right: 5px;">
                                    <span class="close-btn" data-table="job_suppression_images" data-id="${item.id}" onclick="this.parentElement.remove(); removeImage(this)" 
                                        style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; font-size: 14px; cursor: pointer;display:flex;justify-content:center; align-items:center">&times;</span>
                                    <a href="${baseUrl}/${item.kld_images}" data-fancybox="suppression_gallery">
                                        <img src="${baseUrl}/${item.kld_images}" style="width:100px; height:auto; cursor:pointer;" alt="Suppression Image">
                                    </a>
                                </div>
                            `);
                        }
                        else{
                            suppression_images_preview.append(`
                                <div class="pdf-wrapper" style="position: relative; display: inline-block; margin-right: 5px; padding: 5px; border: 1px solid #ccc;">
                                    <span class="close-btn" data-table="job_kld_images" data-id="${item.id}" onclick="this.parentElement.remove(); removeImage(this)" 
                                        style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; font-size: 14px; cursor: pointer;display:flex;justify-content:center; align-items:center">&times;</span>
                                    <a href="${fileUrl}" data-fancybox="suppression_gallery" target="_blank" style="text-decoration: none; color: #007bff;">
                                        ${fileName}
                                    </a>
                                </div>
                            `);
                        }
                    });
                }
 

                kld_images_preview.show();
                mock_up_images_preview.show();
                approve_images_preview.show();
                suppression_images_preview.show();

                // colors 

                const colors = data.colors;
                const colors_cmyk = data.colors_cmyk;  
                const color_name = data.color_name;
                if (colors_cmyk) {
                    
                    const colorCMYKInputs = $('.color-input');
                    colorCMYKInputs.each((index, input) =>{
                        if (colors_cmyk[index] && colors_cmyk[index].trim() !== '') {                            
                            $(input).css('background-color', colors[index]).css('color' , colors[index]).val(colors_cmyk[index]);
                            $(`#cmykOutput_${ index+1 }`).text(colors_cmyk[index])
    
                            let cmykcolors = colors_cmyk[index].split(' ');
                            cmykcolors.forEach((col) => {
                                const [channel, value] = col.split(':');                             
                                                           
                                $(`#cmyk-input-${channel}-${index + 1}`).val(value);
                            });                        
                        }
                        else{
                             $(input).val('').css('background-color', '').css('color', '');
                            $(`#cmykOutput_${index + 1}`).text('');
                            ['C', 'M', 'Y', 'K'].forEach(channel => {
                                $(`#cmyk-input-${channel}-${index + 1}`).val('');
                            });
                        }
                             
                    })
                    
                }

                if (colors) {
                    const colorInputs = $('.cmyk_output');
                    colorInputs.each((index, input) =>{
                        $(input).val(colors[index]);
                        
                    })                    
                }

                if (color_name) {                    
                    
                    const colornameInputs = $('.color_name');
                    colornameInputs.each((index, input) =>{
                        $(input).val(color_name[index]);
                        
                    })  
                }
                

                $('.printing_type').each( function (){
                    const typeBtn = $(this);                     
                    if(typeBtn.val() === jobdetails.printing_type){                        
                        typeBtn.addClass('active');
                    }
                })

                var name = $("#job_type  option:selected").text();
                if (name == 'Shopping Bags') {
                    $('#printingTypes2').hide();          
                    $('#printingTypes').show(); 
                    $('#packing_bag_fabric').hide();                    
                    $('#shopping_bag_fabric').show();
                }
                else{
                    $('#printingTypes2').show();          
                    $('#printingTypes').hide();  
                    $('#packing_bag_fabric').show();
                    $('#shopping_bag_fabric').hide();
                }


                if (jobtype.id == '3') {
                $('.bottom_enclave_type').each( function (){
                        const typeBtn = $(this);                     
                        if(typeBtn.val() === jobdetails.bottom_enclave){                        
                            typeBtn.addClass('active');
                        }
                    })  
                }

                $('.job_bag_type').each( function (){
                    const typeBtn = $(this);                     
                    if(typeBtn.val() === jobdetails.bag_job_type){                        
                        typeBtn.addClass('active');
                    }
                })

                $('.bag_type').each(function (){
                    const typeBtn = $(this);
                    if (typeBtn.val() === jobdetails.bag_type) {
                        typeBtn.addClass('active');
                    }

                })

                let printing_type = jobdetails.printing_type;
                let val = jobdetails.bag_type;

                if (((printing_type == "BOPP") && (val == "D-CUT")) || ((printing_type == "FLEXO") && (val == "D-CUT")) || ((printing_type == "OFFSET") && (val == "D-CUT")) || ((printing_type == "FLEXO") && (val == "U-CUT"))) {                
                    $('#handle-weight-bag-div').css('display', 'none');
                    // $("#cut_detail").show().css("display", "flex");
                    // $("#cut_title").show().css("display", "flex");
                } else {
                    $('#handle-weight-bag-div').css("display", "block");                    
                    // $("#cut_detail").css("display", "none");
                    // $("#cut_title").css("display", "none");
                }

                if (jobdetails.bag_job_type == 'Roll Form') {                    
                    $('#handle-weight-bag-div').css("display", "none");                    

                    $("#loop").css("cursor", "none").addClass('disabled-input').removeClass('active').removeClass('btn-outline-success-2');
                    $("#u_cut").css("cursor", "none").addClass('disabled-input').removeClass('active').removeClass('btn-outline-success-2');
                    $("#d_cut").css("cursor", "none").addClass('disabled-input').removeClass('active').removeClass('btn-outline-success-2');
                    $('#loop_w_flap').css("cursor", "none").removeClass('active').removeClass('btn-outline-success-2').addClass('disabled-input');

                }
                else{
                    $('#handle-weight-bag-div').css("display", "block");                    
                }


                if (printing_type == 'BOPP' || printing_type == 'FLEXXO on BOPP' || printing_type == 'OFFSET') {
                    $("#u_cut").css("cursor", "none").addClass('disabled-input').removeClass('active').removeClass('btn-outline-success-2');
                }
                else{
                    $("#u_cut").css("cursor", "pointer").removeClass('disabled-input').addClass('btn-outline-success-2');
                }

                let jobcylinder = data.job_cylinder;
                if (jobcylinder) {
                    for (const key in jobcylinder) {
                        const inputName = `cylinder[${key}]`;
                        $(`input[name="${inputName}"]`).val(jobcylinder[key]);
                    }
                } 
                
                let bopp = response.data.job_bopp;

                if (bopp) {
                    for (const key in bopp) {
                        const inputName = `bopp[${key}]`;
                        $(`input[name="${inputName}"]`).val(bopp[key]);
                    }
                }

                let isMetal = jobdetails.is_metallized;
                if (isMetal == '') {
                    isMetal = 0;
                }

                if (jobdetails.bag_type == 'NIWAR') {
                    $('.handle_gsm').hide();
                }
                else{
                    $('.handle_gsm').show();
                }

                $('#bopp_metal_type').val(isMetal);

                $('.bopp_metal_type').each( function (){
                    const typeBtn = $(this);                     
                    if(typeBtn.val() === isMetal){                        
                        typeBtn.addClass('active');
                    }
                })

                if (jobdetails.is_metallized == '1') {                    
                    $('#metalise').show();
                    let job_metalised = data.job_metalised;
                    $("#metal_item_code").val(job_metalised.metal_item_code);
                    $(".metal_size").val(job_metalised.job_metal_size);
                    $(".metal_type").val(job_metalised.job_metal_type);
                    $(".metal_micron").val(job_metalised.job_metal_micron);
                    $(".metal_weight").val(job_metalised.job_metal_weight);                    
                }


                

                // First, uncheck both
                // $('input[name="bopp_metal_type"]').prop('checked', false);

                // // Then, check the one that matches value
                // $(`input[name="bopp_metal_type"][value="${isMetal}"]`).prop('checked', true);




                let fabric = data.job_fabric;
                if (fabric) {
                    for(const key in fabric){
                        const inputName = `fabric[${key}]`;
                        $(`input[name="${inputName}"]`).val(fabric[key]);
                    }
                }

                // 

                



                let lamination = data.job_lamination;
                if (lamination) {
                    for(const key in lamination){
                        const inputName = `lamination[${key}]`;
                        $(`input[name="${inputName}"]`).val(lamination[key]);
                    }
                }

                let handle = data.job_handle;
                if (handle) {
                    for(const key in handle){
                        const inputName = `handle[${key}]`;
                        $(`input[name="${inputName}"]`).val(handle[key]);
                    }
                }

                if ($('#handle_type').val() == 'C-PUNCH') {
                    $('.handle_weight').val('0').prop('readonly', true);
                }                

                let job_cut = data.job_cut;

                if (job_cut) {
                    // $('#cut_title').show();
                    for(const key in job_cut){
                        const inputName = `cut[${key}]`;
                        $(`input[name="${inputName}"]`).val(job_cut[key]);
                    }
                    // $('#cut_detail').show().css('display', 'flex');

                }


                
                let cylinder_circum = Number($(".c_circum").val());
                if (cylinder_circum == '') {
                    cylinder_circum == 1;
                }
                let repeat_cylinder = Number($(".repeat_cylinder").val()) || 1;
                let coil_cylinder = Number($(".coil_cylinder").val()) || 1;


                let b_micron = $('.bopp_micron').val();
                let b_size = $('.bopp_size').val();

                let m_micron = $(".metal_micron").val();
                let m_size = $(".metal_size").val();

                let f_gsm = $(".fabric_gsm").val();
                let f_size = $(".fabric_size").val();

                let l_gsm = $(".lamination_gsm").val();
                var l_size = $(".fabric_size").val();


                var b_weight = (b_micron * .91 * b_size * cylinder_circum / 1550) / 645.16;
                var m_weight = (Number(m_micron) * 0.91 * Number(m_size) * Number(cylinder_circum) / 1550) / 645.16;                                        
                var f_weight = (f_gsm * f_size * cylinder_circum / 25.4) / 1550;
                var l_weight = (l_gsm * l_size * cylinder_circum / 25.4) / 1550;


                if (repeat_cylinder > 0) {                        
                    b_weight = b_weight / repeat_cylinder;
                    l_weight = l_weight / repeat_cylinder;
                    f_weight = f_weight / repeat_cylinder;
                    m_weight = m_weight / repeat_cylinder;
                }

                if (coil_cylinder > 0) {                        
                    b_weight = b_weight / coil_cylinder;
                    l_weight = l_weight / coil_cylinder;
                    f_weight = f_weight / coil_cylinder;
                    m_weight = m_weight / coil_cylinder;
                }

                var h_weight = $('.handle_weight').val();
                if (h_weight == '') {
                    h_weight = 0;
                }                

                // var t_weight = parseFloat(h_weight) + parseFloat(l_weight) + parseFloat(f_weight) + parseFloat(b_weight) + parseFloat(m_weight);        
                // $('.total_bag_weight').val(t_weight.toFixed(2));
                $('.total_bag_weight').val(jobdetails.bag_total_weight);


                $('#bopp_weight').val(b_weight) 
                $('#metal_weight').val(m_weight) 
                $('#fabric_weight').val(f_weight) 
                $('#lamination_weight').val(l_weight) 

                $('.form-2').show();
                $('.form-1').show();
                $('#users').modal('show');
                $('#users .modal-title').html('Edit Job details');            

            }
        })  
    }

    function showJob(element){
        var id = $(element).data('id');  
        
        $.ajax({
            url: `{{ route('getJobdetailsEdit') }}`,
            data:{
                id:id,
            },
            method: 'GET',
            success: function(response){                                
                let data = response.data;
                let jobdetails = data.jobdetails;
                let party = data.party;
                let jobname = data.jobname;
                let jobtype = data.jobtype;

                $('#id').val(jobdetails.id);
                if (jobdetails.is_metallized == '1') {                    
                    $('#metalise').show();
                    let job_metalised = data.job_metalised;
                    $("#metal_item_code").val(job_metalised.metal_item_code);
                    $(".metal_size").val(job_metalised.job_metal_size);
                    $(".metal_type").val(job_metalised.job_metal_type);
                    $(".metal_micron").val(job_metalised.job_metal_micron);
                    $(".metal_weight").val(job_metalised.job_metal_weight);                    
                }

                if (jobtype.id == '3') {
                    $('.bag_select_2').show();
                    $('.bottom_enclave').show();
                    $('.bag_select_1').hide();
                    $('.handle_type_1').hide();
                    $('.handle_type_2').show();

                    $('#bottom_enclave').val(jobdetails.bottom_enclave);
                }
                else{
                    $('.bag_select_2').hide();
                    $('.bottom_enclave').hide();
                    $('.bag_select_1').show();
                    $('#bottom_enclave').val('');
                    $('.handle_type_1').show();
                    $('.handle_type_2').hide();
                }


                $('#job_date').val(jobdetails.submit_date);
                $('#job_code').val(jobdetails.job_unique_code);
                $('#party_name').val(party.party_name);
                $('#job_name').val(jobname.job_name);
                $('#job_type').val(jobtype.id);
                $('#printing_type').val(jobdetails.printing_type);
                $('.bag_select').val(jobdetails.bag_type);
                $('.total_bag_weight').val(jobdetails.bag_total_weight);
                $('.bag_pet_size').val(jobdetails.bag_pet);
                $('.bag_job_size').val(jobdetails.bag_circum);
                $('.bag_gaz_size').val(jobdetails.bag_gazette);
                $('#job_description').val(jobdetails.job_description);

                $('#job_bag_type').val(jobdetails.bag_job_type);

                let job_bage_type_name = jobdetails.bag_job_type;

                if (job_bage_type_name.trim() == 'Box Bag') {
                    $(".printing_type").filter(function() {
                        return $(this).val() === "OFFSET";
                    }).closest('.col-md-3').hide();
                }  
                else{
                    $(".printing_type").filter(function() {
                        return $(this).val() === "OFFSET";
                    }).closest('.col-md-3').show();

                }

                if ($(".printing_type").filter(function() {
                    return $(this).val() === "OFFSET" || $(this).val() === "Flexo";
                    }).length > 0) {

                    $("#cylinder_div").remove();
                }
                else{
                    $("#cylinder_div").show();
                }

                if ($('#job_bag_type').val() == 'Box Bag' || $('#job_bag_type').val() == 'Insulated Box Bag') {
                    $("#u_cut").css("cursor", "none").addClass('disabled-input').removeClass('active').removeClass('btn-outline-success-2');
                    $("#d_cut").css("cursor", "none").addClass('disabled-input').removeClass('active').removeClass('btn-outline-success-2');
                }
                else{
                    $("#u_cut").css("cursor", "pointer").removeClass('disabled-input').addClass('btn-outline-success-2');
                    $("#d_cut").css("cursor", "pointer").removeClass('disabled-input').addClass('btn-outline-success-2');
                }
                
                $('#widthIninches').text((jobdetails.bag_circum / 25.4).toFixed(2) + ' inches');
                $('#heightIninches').text((jobdetails.bag_pet / 25.4).toFixed(2) + ' inches');
                if (jobdetails.bag_gazette > 0) {                    
                    $('#bag_gazette_inch').text((jobdetails.bag_gazette / 25.5).toFixed(2) + ' inches');
                }
                

                const colorInputs = document.querySelectorAll('input[name="colors[]"]');

                colorInputs.forEach((input, index) => {
                    if (response.data.colors[index]) {
                        input.value = response.data.colors[index].trim(); // set value and remove leading/trailing space
                    }
                });

                // images
                let kld_images_preview = $('#kld_images_preview');
                let mock_up_images_preview = $('#mock_up_images_preview');
                let suppression_images_preview = $('#suppression_images_preview');
                let approve_images_preview = $('#approve_images_preview');

                let kld_images = data.kld_images;
                let mockup_images = data.mockup_images;
                let approval_images = data.approval_images;
                let separation_images = data.separation_images;

                kld_images_preview.empty();
                mock_up_images_preview.empty();
                approve_images_preview.empty();
                suppression_images_preview.empty();



                if (kld_images) {                        
                    let baseUrl = "{{ asset('images/job-images') }}"; // Blade renders this once    
                    kld_images.forEach(item => {
                        const fileName = item.kld_images;
                        const fileUrl = `${baseUrl}/${fileName}`;
                        const fileExtension = fileName.split('.').pop().toLowerCase();

                        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
                            kld_images_preview.append(`
                                <div class="image-wrapper" style="position: relative; display: inline-block; margin-right: 5px;">
                                    <span class="close-btn" data-table="job_kld_images" data-id="${item.id}" onclick="this.parentElement.remove(); removeImage(this)" 
                                        style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; font-size: 14px; cursor: pointer;">&times;</span>
                                    <a href="${fileUrl}" data-fancybox="kld_gallery">
                                        <img src="${fileUrl}" style="width:100px; height:auto; cursor:pointer;" alt="Mockup Image">
                                    </a>
                                </div>
                            `);
                        } else if (fileExtension === 'pdf') {
                            kld_preview.append(`
                                <div class="pdf-wrapper" style="position: relative; display: inline-block; margin-right: 5px; padding: 5px; border: 1px solid #ccc;">
                                    <span class="close-btn" data-table="job_kld_images" data-id="${item.id}" onclick="this.parentElement.remove(); removeImage(this)" 
                                        style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; font-size: 14px; cursor: pointer;">&times;</span>
                                    <a href="${fileUrl}" data-fancybox="kld_gallery" target="_blank" style="text-decoration: none; color: #007bff;">
                                        ${fileName}
                                    </a>
                                </div>
                            `);
                        }
                    });
                }

                
                
                if (mockup_images) {
                    let baseUrl = "{{ asset('images/job-images') }}";
                    mockup_images.forEach((item, index) => {
                        const fileName = item.job_images;
                        const fileUrl = `${baseUrl}/${fileName}`;
                        const fileExtension = fileName.split('.').pop().toLowerCase();

                        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
                            mock_up_images_preview.append(`
                                <div class="image-wrapper" style="position: relative; display: inline-block; margin-right: 5px;">
                                    <span class="close-btn" data-table="job_images" data-id="${item.id}" onclick="this.parentElement.remove(); removeImage(this)" 
                                        style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; font-size: 14px; cursor: pointer;">&times;</span>
                                    <a href="${baseUrl}/${item.job_images}" data-fancybox="mockup_gallery">
                                        <img src="${baseUrl}/${item.job_images}" style="width:100px; height:auto; cursor:pointer;" alt="Mockup Image">
                                    </a>
                                </div>
                            `);
                        }
                        else{
                            mock_up_images_preview.append(`
                                <div class="pdf-wrapper" style="position: relative; display: inline-block; margin-right: 5px; padding: 5px; border: 1px solid #ccc;">
                                    <span class="close-btn" data-table="job_kld_images" data-id="${item.id}" onclick="this.parentElement.remove(); removeImage(this)" 
                                        style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; font-size: 14px; cursor: pointer;">&times;</span>
                                    <a href="${fileUrl}" data-fancybox="mockup_gallery" target="_blank" style="text-decoration: none; color: #007bff;">
                                        ${fileName}
                                    </a>
                                </div>
                            `);
                        }


                    });
                }

                if (approval_images) {
                    let baseUrl = "{{ asset('images/job-images') }}";
                                    
                    approval_images.forEach((item, index) => {
                        const fileName = item.approve_image;
                        const fileUrl = `${baseUrl}/${fileName}`;
                        const fileExtension = fileName.split('.').pop().toLowerCase();

                        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
                            approve_images_preview.append(`
                                <div class="image-wrapper" style="position: relative; display: inline-block; margin-right: 5px;">
                                    <span class="close-btn" data-table="job_approve_image" data-id="${item.id}" onclick="this.parentElement.remove(); removeImage(this)" 
                                        style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; font-size: 14px; cursor: pointer;">&times;</span>
                                    <a href="${baseUrl}/${item.approve_image}" data-fancybox="approve_gallery">
                                        <img src="${baseUrl}/${item.approve_image}" style="width:100px; height:auto; cursor:pointer;" alt="Approval Image">
                                    </a>
                                </div>
                            `);
                        }
                        else{
                            approve_images_preview.append(`
                                <div class="pdf-wrapper" style="position: relative; display: inline-block; margin-right: 5px; padding: 5px; border: 1px solid #ccc;">
                                    <span class="close-btn" data-table="job_kld_images" data-id="${item.id}" onclick="this.parentElement.remove(); removeImage(this)" 
                                        style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; font-size: 14px; cursor: pointer;">&times;</span>
                                    <a href="${fileUrl}" data-fancybox="approve_gallery" target="_blank" style="text-decoration: none; color: #007bff;">
                                        ${fileName}
                                    </a>
                                </div>
                            `);
                        }

                    });

                }

                if (separation_images) {
                    let baseUrl = "{{ asset('images/job-images') }}";

                    separation_images.forEach((item, index) => {
                        const fileName = item.approve_image;
                        const fileUrl = `${baseUrl}/${fileName}`;
                        const fileExtension = fileName.split('.').pop().toLowerCase();
                        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
                            suppression_images_preview.append(`
                                <div class="image-wrapper" style="position: relative; display: inline-block; margin-right: 5px;">
                                    <span class="close-btn" data-table="job_suppression_images" data-id="${item.id}" onclick="this.parentElement.remove(); removeImage(this)" 
                                        style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; font-size: 14px; cursor: pointer;">&times;</span>
                                    <a href="${baseUrl}/${item.kld_images}" data-fancybox="suppression_gallery">
                                        <img src="${baseUrl}/${item.kld_images}" style="width:100px; height:auto; cursor:pointer;" alt="Suppression Image">
                                    </a>
                                </div>
                            `);
                        }
                        else{
                            suppression_images_preview.append(`
                                <div class="pdf-wrapper" style="position: relative; display: inline-block; margin-right: 5px; padding: 5px; border: 1px solid #ccc;">
                                    <span class="close-btn" data-table="job_kld_images" data-id="${item.id}" onclick="this.parentElement.remove(); removeImage(this)" 
                                        style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; font-size: 14px; cursor: pointer;">&times;</span>
                                    <a href="${fileUrl}" data-fancybox="suppression_gallery" target="_blank" style="text-decoration: none; color: #007bff;">
                                        ${fileName}
                                    </a>
                                </div>
                            `);
                        }
                    });
                }

                kld_images_preview.show();
                mock_up_images_preview.show();
                approve_images_preview.show();
                suppression_images_preview.show();

                // colors 

                const colors = data.colors;
                const colors_cmyk = data.colors_cmyk;  
                if (colors_cmyk) {
                    
                    const colorCMYKInputs = $('.color-input');
                    colorCMYKInputs.each((index, input) =>{
                        if (colors_cmyk[index] && colors_cmyk[index].trim() !== '') {                            
                            $(input).css('background-color', colors[index]).css('color' , colors[index]).val(colors_cmyk[index]);
                            $(`#cmykOutput_${ index+1 }`).text(colors_cmyk[index])
    
                            let cmykcolors = colors_cmyk[index].split(' ');
                            cmykcolors.forEach((col) => {
                                const [channel, value] = col.split(':');                                                                                            
                                $(`#cmyk-input-${channel}-${index + 1}`).val(value);
                            });                                                                      
                        }
                        else{
                             $(input).val('').css('background-color', '').css('color', '');
                            $(`#cmykOutput_${index + 1}`).text('');
                            ['C', 'M', 'Y', 'K'].forEach(channel => {
                                $(`#cmyk-input-${channel}-${index + 1}`).val('');
                            });
                        }
                             
                    })
                    
                }

                if (colors) {
                    const colorInputs = $('.cmyk_output');
                    colorInputs.each((index, input) =>{
                        $(input).val(colors[index]);
                        
                    })                    
                }



                $('.printing_type').each( function (){
                    const typeBtn = $(this);                     
                    if(typeBtn.val() === jobdetails.printing_type){                        
                        typeBtn.addClass('active');
                    }
                })

                if (jobtype.id == '3') {
                $('.bottom_enclave_type').each( function (){
                        const typeBtn = $(this);                     
                        if(typeBtn.val() === jobdetails.bottom_enclave){                        
                            typeBtn.addClass('active');
                        }
                    })  
                }

                $('.job_bag_type').each( function (){
                    const typeBtn = $(this);                     
                    if(typeBtn.val() === jobdetails.bag_job_type){                        
                        typeBtn.addClass('active');
                    }
                })

                $('.bag_type').each(function (){
                    const typeBtn = $(this);
                    if (typeBtn.val() === jobdetails.bag_type) {
                        typeBtn.addClass('active');
                    }

                    if (jobdetails.printing_type == 'BOPP' || jobdetails.printing_type == 'OFFSET') {
                        if (typeBtn.val() == 'U-CUT') {                            
                            typeBtn.prop('disabled', true);
                        }
                    }
                })

                let printing_type = jobdetails.printing_type;
                let val = jobdetails.bag_type;

                if (((printing_type == "BOPP") && (val == "D-CUT")) || ((printing_type == "FLEXO") && (val == "D-CUT")) || ((printing_type == "OFFSET") && (val == "D-CUT")) || ((printing_type == "FLEXO") && (val == "U-CUT"))) {                
                    $("#handle_detail").css("display", "none");
                    $("#handle_title").css("display", "none");
                    // $("#cut_detail").show().css("display", "flex");
                    // $("#cut_title").show().css("display", "flex");
                } else {
                    $("#handle_detail").css("display", "flex");
                    $("#handle_title").css("display", "flex");
                    // $("#cut_detail").css("display", "none");
                    // $("#cut_title").css("display", "none");
                }

                let jobcylinder = data.job_cylinder;
                if (jobcylinder) {
                    for (const key in jobcylinder) {
                        const inputName = `cylinder[${key}]`;
                        $(`input[name="${inputName}"]`).val(jobcylinder[key]);
                    }
                } 
                
                let bopp = response.data.job_bopp;

                if (bopp) {
                    for (const key in bopp) {
                        const inputName = `bopp[${key}]`;
                        $(`input[name="${inputName}"]`).val(bopp[key]);
                    }
                }

                let isMetal = jobdetails.is_metallized;
                if (isMetal == '') {
                    isMetal = 0;
                }

                $('#bopp_metal_type').val(isMetal);

                $('.bopp_metal_type').each( function (){
                    const typeBtn = $(this);                     
                    if(typeBtn.val() === isMetal){                        
                        typeBtn.addClass('active');
                    }
                })

                // First, uncheck both
                // $('input[name="bopp_metal_type"]').prop('checked', false);

                // Then, check the one that matches value
                // $(`input[name="bopp_metal_type"][value="${isMetal}"]`).prop('checked', true);

                let fabric = data.job_fabric;
                if (fabric) {
                    for(const key in fabric){
                        const inputName = `fabric[${key}]`;
                        $(`input[name="${inputName}"]`).val(fabric[key]);
                    }
                }

                let lamination = data.job_lamination;
                if (lamination) {
                    for(const key in lamination){
                        const inputName = `lamination[${key}]`;
                        $(`input[name="${inputName}"]`).val(lamination[key]);
                    }
                }

                let handle = data.job_handle;
                if (handle) {
                    for(const key in handle){
                        const inputName = `handle[${key}]`;
                        $(`input[name="${inputName}"]`).val(handle[key]);
                    }
                }

                if ($('#handle_type').val() == 'C-PUNCH') {
                    $('.handle_weight').val('0').prop('readonly', true);
                }
                // else{
                //     $('.handle_weight').val('').prop('readonly', false);
                // }

                let job_cut = data.job_cut;

                if (job_cut) {
                    // $('#cut_title').show();
                    for(const key in job_cut){
                        const inputName = `cut[${key}]`;
                        $(`input[name="${inputName}"]`).val(job_cut[key]);
                    }
                    // $('#cut_detail').show().css('display', 'flex');

                }
                // .css('border', 'none').css('background', 'transparent')                                                                       
            }            
        })

        $('.form-2').show();
        $('.form-1').show();
        $('.image-input').hide();
        $('#users').modal('show');
        $('.modal-footer').hide();
        $('#users .modal-title').html('Job details'); 
        $('#users form :input:not(.close-popup)').prop('disabled', true);

        
    }

    // function removeImage(elem){
    //     let imageId = elem.getAttribute('data-id');
    //     let table = elem.getAttribute('data-table');

    //     if (!imageId || !table) {
    //         console.error('Image ID not found');
    //         return;
    //     }
    //     if (!confirm("Are you sure you want to delete this image?")) return;

    //     $.ajax({
    //         type: 'POST',
    //         url: "{{route('jobdetails.remove-image')}}",
    //         data: {
    //         _token: $('meta[name="csrf-token"]').attr('content'), // For CSRF protection
    //             id: imageId,
    //             table: table
    //         },
    //         success: function(data) {
    //             console.log(data);

    //             if (data.success) {
    //                 alert('Image Deleted !!')
    //             }
    //             else{
    //                 alert('Error deleting image !!')
    //             }
    //         }
    //     })
    // }

    function removeImage(elem) {
        const filename = elem.dataset.filename;
        const inputId = elem.dataset.input;
        console.log(inputId);
        
        const input = document.getElementById(inputId);
        const dt = new DataTransfer();


        if (input) {
            if (input.files.length > 0) {                
                Array.from(input.files).forEach(file => {
                    if (file.name !== filename) {
                        dt.items.add(file);
                    }
                });
                input.files = dt.files;                                       
            }
        }

        elem.parentElement.remove();

        console.log(`Removed file "${filename}" from input "${inputId}"`);
        



        // Optional AJAX to remove from DB
        const table = elem.dataset.table;
        const fileId = elem.dataset.id;
        // if (table && fileId) {
        //     fetch('/delete-file', {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json',
        //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        //         },
        //         body: JSON.stringify({ id: fileId, table: table })
        //     }).then(res => res.json())
        //       .then(data => console.log('DB file deleted', data))
        //       .catch(err => console.error('Delete failed', err));
        // }

        if (table && fileId) { 
            if (!confirm("Are you sure you want to delete this image?")) return;           
            $.ajax({
                type: 'POST',
                url: "{{route('jobdetails.remove-image')}}",
                data: {
                _token: $('meta[name="csrf-token"]').attr('content'), // For CSRF protection
                    id: fileId,
                    table: table
                },
                success: function(data) {
                    console.log(data);
    
                    if (data.success) {
                        alert('Image Deleted !!')
                    }
                    else{
                        alert('Error deleting image !!')
                    }
                }
            })
        }

    }

    $('.close-modal').on('click', function (){    
        $('#users .modal-title').html('Add Job details');    
        $('#users form :input').prop('disabled', false); 

        $("#cylinder_div").show();

        $('#gaz_bag').hide().find('input, select, textarea').val('').prop('checked', false).prop('selected', false);
        $('#pillow_bag').show().find('input, select, textarea').val('').prop('checked', false).prop('selected', false);;

        let kld_images_preview = $('#kld_images_preview');
        let mock_up_images_preview = $('#mock_up_images_preview');
        let suppression_images_preview = $('#suppression_images_preview');
        let approve_images_preview = $('#approve_images_preview');
        
        kld_images_preview.empty().hide();
        mock_up_images_preview.empty().hide();
        approve_images_preview.empty().hide();
        suppression_images_preview.empty().hide();
        $('#users').modal('hide');   
        $('#job_details_form')[0].reset();

        $('.show_measure').text('');

        $("#u_cut").css("cursor", "pointer").removeClass('disabled-input').addClass('btn-outline-success-2');
        $("#d_cut").css("cursor", "pointer").removeClass('disabled-input').addClass('btn-outline-success-2');

        $('.image-input').show();
        $('.modal-footer').show();
        $('#users form')[0].reset();
        $('.bag_type').each(function (){
            $(this).removeClass('active').removeClass('disabled-input');
        });

        $('.printing_type').each(function (){
            $(this).removeClass('active').removeClass('disabled-input');
        });

        $('.job_bag_type').each(function (){
            $(this).removeClass('active').removeClass('disabled-input');
        })

        // Reset visible color inputs
        $('.color-input').each(function () {
            $(this).val('').css('background-color', '').css('color', '');
        });

        // Reset small CMYK output preview
        $('[id^="cmykOutput_"]').each(function () {
            $(this).text('');
        });

        // Reset hidden CMYK values
        $('[id^="cmykOutputinput_"]').each(function () {
            $(this).val('');
        });

        // Reset each individual CMYK channel input
        $('[id^="cmyk-input-C-"], [id^="cmyk-input-M-"], [id^="cmyk-input-Y-"], [id^="cmyk-input-K-"]').each(function () {
            $(this).val('');
        });

        $('.handle_weight').val('').prop('readonly', false);

        // Optionally, reset the CMYK popup (if visible)
        $('.cmyk-popup').hide();
        $('.form-2').hide();
        $('.form-1').hide();

    })    
</script>

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.umd.js"></script>
{{-- <script>
    document.addEventListener("DOMContentLoaded", function () {        
        Fancybox.bind("[data-fancybox='kld_gallery']", {
            on: {
                reveal : (fancybox, slide) =>{
                    console.log('Fancy box opened : ', Fancybox.getInstance());                
                },
                destroy : ()=>{
                    console.log('Fancybox closed');
                }
            },
            Carousel: {
                    Toolbar: {
                    display: {
                        left: ["counter"],
                        middle: [
                        "zoomIn",
                        "zoomOut",
                        "toggle1to1",
                        "rotateCCW",
                        "rotateCW",
                        "flipX",
                        "flipY",
                        ],
                        right: ["autoplay", "thumbs", "download", "close"],
                    },
                    },
                },
            Thumbs: false,
            dragToClose: false,
            placeFocusBack: false,
            Image: {
                zoom: true,
            },
        });
        Fancybox.bind("[data-fancybox='mockup_gallery']", {
            Carousel: {
                    Toolbar: {
                    display: {
                        left: ["counter"],
                        middle: [
                        "zoomIn",
                        "zoomOut",
                        "toggle1to1",
                        "rotateCCW",
                        "rotateCW",
                        "flipX",
                        "flipY",
                        ],
                        right: ["autoplay", "thumbs", "download", "close"],
                    },
                    },
                },
            Thumbs: false,
            dragToClose: false,
            placeFocusBack: false,
            Image: {
                zoom: true,
            },
        });
        Fancybox.bind("[data-fancybox='approve_gallery']", {
            Carousel: {
                    Toolbar: {
                    display: {
                        left: ["counter"],
                        middle: [
                        "zoomIn",
                        "zoomOut",
                        "toggle1to1",
                        "rotateCCW",
                        "rotateCW",
                        "flipX",
                        "flipY",
                        ],
                        right: ["autoplay", "thumbs", "download", "close"],
                    },
                    },
                },
            Thumbs: false,
            dragToClose: false,
            placeFocusBack: false,
            Image: {
                zoom: true,
            },
        });
        Fancybox.bind("[data-fancybox='suppression_gallery']", {
            // Toolbar: {
            //     display: [
            //     "zoom",
            //     "fullscreen",
            //     "download",
            //     "close",
            //     ],
            // },
            Carousel: {
                    Toolbar: {
                    display: {
                        left: ["counter"],
                        middle: [
                        "zoomIn",
                        "zoomOut",
                        "toggle1to1",
                        "rotateCCW",
                        "rotateCW",
                        "flipX",
                        "flipY",
                        ],
                        right: ["autoplay", "thumbs", "download", "close"],
                    },
                    },
                },
            Thumbs: {
                autoStart: false
            },
            dragToClose: false,
            placeFocusBack: false,
            Image: {
                zoom: true,
            },
        });
        Fancybox.bind("[data-fancybox^='kld_gallery_img_']", {
            Carousel: {
                Toolbar: {
                display: {
                    left: ["counter"],
                    middle: [
                    "zoomIn",
                    "zoomOut",
                    "toggle1to1",
                    "rotateCCW",
                    "rotateCW",
                    "flipX",
                    "flipY",
                    ],
                    right: ["autoplay", "thumbs", "download", "close"],
                },
                },
            },
        });
    });  
</script> --}}

<script>
document.addEventListener("DOMContentLoaded", function () {
    const galleries = [
        'kld_gallery',
        'mockup_gallery',
        'approve_gallery',
        'suppression_gallery'
    ];

    const commonConfig = {
        on: {
            ready: (fancybox, slide) => {
                console.log('Fancy box opened: ', Fancybox.getInstance());

                console.log(fancybox.getSlide);
                const src = slide.src || '';
                const isImage = /\.(jpg|jpeg|png|gif|webp|bmp)$/i.test(src);
                
                

                // Hide toolbar for non-images (like PDFs)
                const toolbar = document.querySelector('.fancybox__toolbar');
                if (toolbar) {
                    toolbar.style.display = isImage ? 'flex' : 'none';
                }
            },
            destroy: () => {
                console.log('Fancybox closed');
            }
        },
        Carousel: {
            Toolbar: {
                display: {
                    left: ["counter"],
                    middle: [
                        "zoomIn", "zoomOut", "toggle1to1",
                        "rotateCCW", "rotateCW", "flipX", "flipY"
                    ],
                    right: ["autoplay", "thumbs", "download", "close"],
                },
            },
        },
        Thumbs: false,
        dragToClose: false,
        placeFocusBack: false,
        Image: {
            zoom: true,
        },
    };

    // Loop through each gallery type and bind Fancybox
    galleries.forEach(gallery => {
        const config = { ...commonConfig };

        // Optional: customize per gallery if needed
        if (gallery === 'suppression_gallery') {
            config.Thumbs = { autoStart: false };
        }

        Fancybox.bind(`[data-fancybox='${gallery}']`, config);
    });

    // Handle individually named kld_gallery_img_*
    Fancybox.bind("[data-fancybox^='kld_gallery_img_']", {
        Carousel: {
            Toolbar: {
                display: {
                    left: ["counter"],
                    middle: [
                        "zoomIn", "zoomOut", "toggle1to1",
                        "rotateCCW", "rotateCW", "flipX", "flipY"
                    ],
                    right: ["autoplay", "thumbs", "download", "close"],
                },
            },
        },
    });
});
</script>


@include('admin.pages.jobdetails.index_script')
@endsection

