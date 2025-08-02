@extends('layout.base')

<style>
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

    .bag_type.active {
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


    .suggestions{
        position: absolute;
        top: 100%;
        z-index: 100;
        left: 0;
        width: 100%;        
        height: max-content;
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
    
</style>

@section('content')
<div class="page-content">
    {{-- @include('layouts.sidebar') --}}
    <div class="content-wrapper">
        <div class="content-inner">
            <div class="page-header page-header-light shadow">
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
            </div>

            <div class="content">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Job Details | {{ ucfirst($request_type)}}</h5>
                        <div class="card-tools text-end"
                            style="display: flex; align-items:center; justify-content: space-between;">
                            <div class="btns">
                                @if (hasPermission('Job Details All Save', 'Save') || hasPermission('Job Details Pending Save', 'Save'))                                    
                                @if ($request_type != 'saved')                                    
                                    <a href="#" class="text-white btn btn-primary-2" onclick="oenAddModal();">Add Job Details</a>
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
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
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
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>S.NO</th>
                                        
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

<div id="users" class="modal fade  m-auto" tabindex="-1" role="dialog" aria-labelledby="bopp"  data-bs-backdrop="static" data-bs-keyboard="false" style="display: none;">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Job Details</h4>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form action="{{route('jobdetails.save')}}" method="post" enctype="multipart/form-data" id="job_details_form">
                    @csrf 
                    
                    <input type="hidden" name="type" value="{{$request_type}}">
                    <input type="hidden" name="id" id="id">
                    <div class="form-body">
                        <div class="form-seperator-dashed"></div>
                        <div class="row">                                                       
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Job Code</label>
                                    <input type="text" id="job_code" class="form-control" name="job_code" required placeholder="Enter Job Code" value="{{ $lastID ? str_pad($lastID->id + 1, 4, '0', STR_PAD_LEFT) : '0001' }}" readonly>
                                    @error('job_code') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-4"></div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Job Date</label>
                                    <input type="date" id="job_date" class="form-control" name="submit_date" placeholder="Enter date" readonly>
                                    @error('submit_date') <small class="text-danger">{{ $message }}</small> @enderror
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            const dateInput = document.getElementById('job_date');
                                            const today = new Date().toISOString().split('T')[0]; // format: YYYY-MM-DD
                                            dateInput.value = today;
                                            dateInput.min = today;
                                        });

                                    </script>

                                </div>
                            </div>                            

                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Name <span class="text-danger">*</span></label>
                                    <input type="text" id="job_name" class="form-control" name="job_name" required placeholder="Enter Job name" style="position: relative;" autocomplete="off" oninput="checkIsExist(this);" onchange="checkIsExist(this)" onblur="this.value = this.value.toUpperCase();">
                                    {{-- <div class="invalid-feedback">This field is required.</div> --}}
                                    {{-- <div class="job_name_suggestions suggestions" style="display: none;"></div> --}}
                                    <small class="job_name_error text-danger job_error_message" style="display: none;"></small>
                                    @error('job_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>   
                            <div class="col-md-6">
                                <div class="form-group" style="position: relative;">
                                    <label>Party Name <span class="text-danger">*</span></label>
                                    <input type="text" id="party_name" class="form-control" name="party_name" required placeholder="Select Party Name" style="position: relative;" autocomplete="off">
                                    <div class="party_name_suggestions suggestions" style="display: none;"></div>
                                    <small class="party_name_error" style="display: none;"></small>
                                    @error('party_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                                                                                                           
                        </div>
                        <hr>
                        <div class="form-seperator-dashed"></div>

                        <div class="row">                            
                            <?php
                                $printingTypes = ['BOPP', 'FLEXO On BOPP', 'FLEXO', 'OFFSET'];
                                $handleTypes = ['LOOP', 'D-CUT', 'U-CUT'];
                            ?>
                            <div class="form-seperator-dashed"></div>   
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Job Type <span class="text-danger">*</span></label>
                                    <select name="job_type" id="job_type" class="form-control" required>
                                        <option value="">--Select--</option>
                                        @foreach ($jobtypes as $type)
                                            <option value="{{$type->id}}">{{$type->job_type}}</option>
                                        @endforeach
                                    </select>
                                    @error('job_type') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>  

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Printing Type <span class="text-danger">*</span></label>
                                    <input type="hidden" class="print_select" name="printing_type" id="printing_type">
                                    <div id="printing_type_group" class="row g-2">
                                        @foreach($printingTypes as $type)
                                            <div class="col-md-3">
                                                <button type="button"
                                                        class="btn btn-sm btn-rounded btn-outline-success-2 printing_type  w-100"
                                                        value="{{ $type }}"
                                                        style="white-space: nowrap;"
                                                        onclick="printing_type_error.style.display = 'none'">

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
                                    <label>Handle Type</label>
                                    <input type="hidden" name="handle_type" id="handle_type">
                                    <div class="row g-2">                                        
                                        <div class="col-md-3">
                                            <button type="button"class="btn btn-sm btn-rounded btn-outline-success-2 bag_type w-100"id="loop"value="LOOP"style="white-space: nowrap;">LOOP</button>                                            
                                        </div>                                        
                                        <div class="col-md-3">
                                            <button type="button"class="btn btn-sm btn-rounded btn-outline-success-2 bag_type w-100"id="d_cut"value="D-CUT"style="white-space: nowrap;">D-CUT</button>                                            
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button"class="btn btn-sm btn-rounded btn-outline-success-2 bag_type w-100"id="u_cut"value="U-CUT"style="white-space: nowrap;">U-CUT</button>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="form-seperator-dashed"></div>
                        <hr>
                        <input type="hidden" class="bag_select" name="job[bag_type]">
                        <div class="row">                            
                            <div class="col-md-12">
                                <h4 class="modal-title-2-2 float-xl-left">Bag</h4>
                            </div>
                            <div class="col-md-6" id="pillow_bag">
                                <div class="form-group">
                                    <label>Bag Size <span class="text-danger">*</span><small> in mm</small></label>  
                                    <div class="row mb-3 d-flex justify-content-start align-items-start">
                                        <div class="col-md-5">  
                                            <div class="input-group">                                                
                                                <input type="text" class="form-control bag_job_size" autocomplete="off"  placeholder="Width" name="job[bag_circum]" required oninput="this.value = this.value.replace(/[^0-9]/, ''); widthIninches.innerText = (this.value / 25.4).toFixed(2) + ' inches';">                                            
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">mm</span>
                                                </div>
                                            </div>                                          
                                            <p id="widthIninches" class="my-2" style="dispaly:block; height:24px; font-weight:bold;"></p>
                                        </div>
                                        <div class="col mt-1 text-center">
                                            X
                                        </div>
                                        <div class="col-md-5"> 
                                            <div class="input-group">                                                
                                                <input type="text" class="form-control bag_pet_size" autocomplete="off"  placeholder="Height" name="job[bag_pet]" required oninput="this.value = this.value.replace(/[^0-9]/, '');heightIninches.innerText = (this.value / 25.4).toFixed(2) + ' inches'">                                            
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">mm</span>
                                                </div>
                                            </div>                                           
                                            <p id="heightIninches" class="my-2" style="dispaly:block; height:24px; font-weight:bold;"></p>
                                        </div>
                                    </div>

                                    @error('bag_size') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="col-md-8" id="gaz_bag" style="display:none">
                                <div class="form-group">
                                    <label>Bag Size <span class="text-danger">*</span><small> in mm</small></label>  
                                    <div class="row mb-3 d-flex justify-content-start align-items-start">
                                        <div class="col-md-3">
                                            <div class="input-group ">
                                                <input type="text" class="form-control bag_job_size" autocomplete="off"  placeholder="Width" name="job[bag_circum]" required oninput="this.value = this.value.replace(/[^0-9]/, '');">                                            
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">mm</span>
                                                </div>
                                            </div>
                                            <div class="m-2" id="job_size" style="dispaly:block; height:24px; font-weight:bold;"></div>
                                        </div>
                                        x
                                        <div class="col-md-4">
                                            <div class=" input-group">
                                                <input type="text" class="form-control bag_pet_size" autocomplete="off" required  placeholder="Height" name="job[bag_pet]" oninput="this.value = this.value.replace(/[^0-9]/, '');">                                            
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">mm</span>
                                                </div>
                                            </div>
                                            <div class="m-2" id="bag_pet_inch" style="dispaly:block; height:24px; font-weight:bold;"></div>
                                        </div>
                                        x
                                        <div class="col-md-4">
                                                <div class=" input-group">
                                                    <input type="text" class="form-control bag_gaz_size" autocomplete="off" required  placeholder="Gusset" name="job[bag_gazette]" oninput="this.value = this.value.replace(/[^0-9]/, '');">                                                    
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">mm</span>
                                                    </div>
                                                </div>
                                                <div class="m-2" id="bag_gazette_inch" style="dispaly:block; height:24px; font-weight:bold;"></div>
                                            </div>

                                                        <!-- <div class="input-group-append">
                                                    <span class="input-group-text">mm.</span>
                                                </div> -->
                                        </div>
                                    </div>

                                </div>                                

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Weight/Bag w/Handle</label>  
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

                        <hr>
                        <div class="row">                            
                            <div class="col-md-12">
                                <h4>Cylinder</h4>
                            </div>
                        {{-- </div>
                        <div class="row">--}}
                            <div class="col-md-6 row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Job Size</label>  
                                        <div class="row mb-3 d-flex justify-content-start align-items-center">
                                            <div class="col-md-6">
                                                <input type="text" name="cylinder[job_circum]" id="circum" class="form-control c_circum" placeholder="Circum" required oninput="this.value = this.value.replace(/[^0-9]/, '');">                                            
                                            </div>
                                            X                                            
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" autocomplete="off" placeholder="Pet" name="cylinder[job_pet]" required oninput="this.value = this.value.replace(/[^0-9]/, '');">
                                            </div>
                                        </div>

                                        @error('bag_size') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Shell Size</label>  
                                        <div class="row mb-3 d-flex justify-content-start align-items-center">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" autocomplete="off" placeholder="Circum" name="cylinder[shell_circum]" required  oninput="this.value = this.value.replace(/[^0-9]/, '');">
                                            </div>
                                            X                                            
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" autocomplete="off" placeholder="Pet" name="cylinder[shell_pet]" required oninput="this.value = this.value.replace(/[^0-9]/, '');">
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
                                        <label>Coil</label>  
                                        <input type="text" class="form-control coil_cylinder" autocomplete="off" placeholder="Coil" name="cylinder[coil_cylinder]" value="1" oninput="this.value = this.value.replace(/[^0-9]/, '');">
                                        @error('coil_cylinder') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>
    
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="white-space: nowrap">Repeat Cylinder</label>  
                                        <input type="text" class="form-control repeat_cylinder" autocomplete="off" name="cylinder[cylinder_repeat]" value="1" oninput="this.value = this.value.replace(/[^0-9]/, '');">
                                        @error('repeat') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>
    
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>No of cylinders</label>  
                                        <input type="text" class="form-control" autocomplete="off"  placeholder="No. Of Cylinder" name="cylinder[cylinder_count]" value="1" oninput="this.value = this.value.replace(/[^0-9]/, '');">
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
                                <label>Job Size</label>
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

                                    <!-- <div class="input-group-append">
                                <span class="input-group-text">mm.</span>
                            </div> -->
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

                                    <!-- <div class="input-group-append">
                                <span class="input-group-text">mm.</span>
                            </div> -->
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
                        
                        <div class="form-seperator-dashed"></div>
                        <div class="row">
                            <div class="col-md-12">
                                {{-- <div class="form-group">
                                    <label for="colors">Colors</label>
                                    <div class="row g-2">
                                        <?php $count = 8 ?>
                                        @for ($i = 1; $i <= $count; $i++)
                                            <div class="col">
                                                <input type="text" name="colors[]" class="form-control" placeholder="Color {{$i}}" oninput="this.value = this.value.replace(/[0-9]/g, '');">
                                                <p id="cmykOutput_{{$i}}"></p>
                                            </div>
                                        @endfor
                                    </div>
                                </div> --}}

                                <div class="form-group">
                                    <label for="colors">Colors</label>
                                    <div class="row g-2">
                                        <?php $count = 8 ?>
                                        @for ($i = 1; $i <= $count; $i++)
                                        <div class="col mb-3 ">
                                            <small id="cmykOutput_{{ $i }}" style="font-size: .7em; height: 18px; display: inline-block;"></small>
                                            <input type="text position-relative"
                                                id="color-input-{{ $i }}"
                                                name="colors_cmyk[]"
                                                class="form-control color-input"
                                                placeholder="Color {{ $i }}"
                                                readonly
                                                data-index="{{ $i }}"
                                            >

                                            <!-- CMYK Popup -->
                                            <div class="cmyk-popup" id="cmyk-picker-{{ $i }}" data-index="{{ $i }}" style="display: none; position: absolute; top: 100%; left: %; width:100%; z-index: 1000; background: #fff; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                                                <div class="row g-1">
                                                    <div class="col-md-12"><input type="text"  autofocus id="cmyk-input-C-{{$i}}" class="form-control cmyk-input" placeholder="C" min="0" max="100" data-channel="c" oninput="this.value = this.value.replace(/[^0-9]/, '')"></div>
                                                    <div class="col-md-12"><input type="text" id="cmyk-input-M-{{$i}}" class="form-control cmyk-input" placeholder="M" min="0" max="100" data-channel="m" oninput="this.value = this.value.replace(/[^0-9]/, '')"></div>
                                                    <div class="col-md-12"><input type="text" id="cmyk-input-Y-{{$i}}" class="form-control cmyk-input" placeholder="Y" min="0" max="100" data-channel="y" oninput="this.value = this.value.replace(/[^0-9]/, '')"></div>
                                                    <div class="col-md-12"><input type="text" id="cmyk-input-K-{{$i}}" class="form-control cmyk-input" placeholder="K" min="0" max="100" data-channel="k" oninput="this.value = this.value.replace(/[^0-9]/, '')"></div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="colors[]" id="cmykOutputinput_{{ $i }}" class="cmyk_output">                                            
                                        </div>
                                        @endfor

                                    </div>
                                </div>

                            </div>                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>KLD</label>
                                    <input type="file" class="form-control image-input" id="kld_images" autocomplete="off"  placeholder="Job Name" name="kld[]" multiple onchange="previewKLDImages(this)" accept="image/*">
                                    <div id="kld_images_preview" class="imgs"></div>
                                </div>                                                              
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mockups</label>
                                    <input type="file" class="form-control image-input" id="mock_up_images" autocomplete="off"  placeholder="Job Name" name="files[]" multiple onchange="previewMockupImages(this)" accept="image/*">
                                    <div id="mock_up_images_preview" class="imgs"></div>
                                </div>                                                               
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Separation</label>
                                    <input type="file" class="form-control image-input" id="suppression_images" autocomplete="off"  placeholder="Job Name" name="suppression[]" multiple onchange="previewSuppressionImages(this)" accept="image/*">
                                    <div id="suppression_images_preview" class="imgs"></div>
                                </div>                                                             
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Approval</label>
                                    <input type="file" class="form-control image-input" id="approval_images" autocomplete="off"  placeholder="Job Name" name="approve[]" multiple onchange="previewApproveImages(this)" accept="image/*">
                                    <div id="approve_images_preview" class="imgs"></div>
                                </div>                                                              
                            </div>                            
                        </div>
                        <div id="myimgpopover" style="display: none; position: fixed; top: 50%; left: 50%; transform:translate(-50%, -50%);  
                            width: 800px; height: 600px; background: rgba(0, 0, 0, 0.7); 
                            justify-content: center; align-items: center; z-index: 10000; ">
                            <button type="button" class="close-popup" onclick="myimgpopover.style.display = 'none';" style="position: absolute; top: 20px; right: 30px; background:transparent; font-size: 30px; color: white; cursor: pointer; border:none;">&times;</button>                            
                        </div>
                        <hr>
                        <div class="row my-2" id="bopp_title">                            
                            <h4 class="modal-title-2 float-xl-left">BOPP</h4>
                        </div>
                        <div class="row mb-3" id="bopp_detail">
                            <div class="col-md-4">
                                <label>Item Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control bopp_item_code" id="bopp_item_code"  placeholder="Enter Item Code" name="bopp[bopp_item_code]" autocomplete="off">
                                    <div class="bopp-item-suggestions suggestions" style="display: none;"></div>                                    
                                </div>
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
                                        <span class="input-group-text">micron</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3"></div>
                            <div class="col-md-6 mb-3">
                                <label>BOPP Weight/Bag</label>
                                <div class=" input-group ">
                                    <input type="text" class="form-control bopp_weight" placeholder="BOPP Weight/Sheet"  tabIndex="-1" readonly name="bopp[job_bopp_weight]">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Grams</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row ml-1 my-2" id="bopp_metal">
                            <div class="col-md-6">                                
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="bopp_metal_type" id="metal" value="1">
                                    <label class="form-check-label" for="metal">Metalised</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="bopp_metal_type" id="non-metal" value="0" checked>
                                    <label class="form-check-label" for="non-metal">Non-Metalised</label>
                                </div>
                            </div>                            
                        </div>
                        <div id="metalise" style="display: none;">
                            <hr>
                            <div class="row my-2">
                                <h4 class="modal-title-2 float-xl-left">Metalise</h4>
                            </div>
                            <div class="row mb-3" id="metal_detail">
                                <div class="col-md-4">
                                    <label>Item Code</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control metal_item_code" id="metal_item_code"  placeholder="Enter Item Code" name="metal[metal_item_code]" autocomplete="off">
                                        <div class="metal-item-suggestions suggestions" style="display: none;"></div>                                    
                                    </div>
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
                                            <span class="input-group-text">micron</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3"></div>
                                <div class="col-md-6 mb-3">
                                    <label>Metalise Weight/Bag</label>
                                    <div class=" input-group ">
                                        <input type="text" class="form-control metal_weight" placeholder="BOPP Weight/Sheet" tabIndex="-1" readonly name="metal[job_metal_weight]">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Grams.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>                                                    
                        </div>
                        <hr>
                        <!-- Fabric -->
                        <div class="row my-2">
                            <h4 class="modal-title-2 float-xl-left">Fabric</h4>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Item Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control fabric_item_code" id="fabric_item_code"  placeholder="Enter Item Code" name="fabric[fabric_item_code]" autocomplete="off">
                                    <div class="fabric-item-suggestions suggestions" style="display: none;"></div>
                                </div>
                                <div class="fabric-tag"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Size</label>
                                <div class="input-group">
                                    <input type="text" class="form-control fabric_size" placeholder="Size" name="fabric[job_fabric_size]" tabIndex="-1" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Inches.</span>
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
                                <label>GSM</label>
                                <div class=" input-group ">
                                    <input type="text" class="form-control fabric_gsm" placeholder="GSM" name="fabric[job_fabric_gsm]" tabIndex="-1" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">GSM.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3"></div>
                            <div class="col-md-6 mb-3">
                                <label>Fabric Weight/Bag</label>
                                <div class=" input-group ">
                                    <input type="text" class="form-control fabric_weight" placeholder="Fabric Weight/Bag" tabIndex="-1" readonly name="fabric[job_fabric_weight]">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Grams.</span>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                        <hr>
                        <div class="row mb-3">
                            <h4 class="modal-title-2 float-xl-left">Lamination</h4>                                
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Mixture</label>
                                <div class="input-group">
                                    <input type="text" class="form-control lamination_mixture" autocomplete="off" placeholder="Mixture" name="lamination[job_lamination_mix]" oninput="this.value = this.value.replace(/[^0-9.]/, '');">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Inches</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>GSM</label>
                                <div class=" input-group ">
                                    <input type="text" class="form-control lamination_gsm" autocomplete="off"  placeholder="GSM" name="lamination[job_lamination_gsm]" oninput="this.value = this.value.replace(/[^0-9]/, '');">
                                    <div class="input-group-append">
                                        <span class="input-group-text">GSM</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3"></div>
                            <div class="col-md-6 mb-3">
                                <label>Lamination Weight/Bag</label>
                                <div class=" input-group ">
                                    <input type="text" class="form-control lamination_weight" placeholder="Lamination Weight/Bag" tabIndex="-1" readonly name="lamination[job_lamination_weight]">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Grams.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                            <div class="col-md-4">
                                <label>GSM</label>
                                <div class=" input-group ">
                                    <input type="text" class="form-control" autocomplete="off" placeholder="GSM" name="handle[job_handle_gsm]" oninput="this.value = this.value.replace(/[^0-9]/, '');">
                                    <div class="input-group-append">
                                        <span class="input-group-text">GSM.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Handle Weight/Bag</label>
                                <div class=" input-group ">
                                    <input type="text" class="form-control handle_weight" autocomplete="off" placeholder="Weight" name="handle[job_handle_weight]" oninput="this.value = this.value.replace(/[^0-9]/, '');">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Grams.</span>
                                    </div>
                                </div>
                            </div>                            
                        </div>


                        {{-- <div class="row my-2" id="cut_title" style="display: none;">
                            <h4 class="modal-title-2 float-xl-left">Cut</h4>
                        </div>
                        <div class="row mb-2" id="cut_detail" style="display: none;">
                            <div class="col-md-4">
                                <label>Cut Type</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" autocomplete="off" placeholder="Cut Type" name="cut[cut_type]">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Cut Wastage</label>
                                <div class="input-group ">
                                    <input type="text" class="form-control cut_wastage" autocomplete="off" placeholder="Weight" name="cut[cut_wastage]" oninput="this.value = this.value.replace(/[^0-9]/g, ''); // Remove digits">
                                </div>
                            </div>
                        </div> --}}

                        <div class="row">
                            <div class="col-md-12">
                                <label>Remark</label>
                                <div class="form-group">
                                    <textarea class="form-control" rows="5" name="job[job_description]" id="job_description" placeholder="Write Your Short Note Here........"></textarea>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="job_status" id="job_status">


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-rounded text-left close-modal" data-dismiss="modal">Cancel</button>
                        <button type="submit" onclick="job_status.value = 0" class="btn btn-success-2 float-right text-right submit-save-btn">Save</button>
                        <button type="submit" onclick="job_status.value = 1" class="btn btn-success-2 float-right text-right submit-save-btn">Submit</button>
                    </div>
                </form>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>    
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{asset('assets/js/jquery/jquery.validate.min.js')}}"></script>

<script>
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
        $(this).find(':input').removeClass('is-invalid');

        let isValid = true;
        

        $(this).find(':input[required]').each(function () {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            }
        });

        if (!isValid) {
            event.preventDefault(); // prevent form submission
        }

        let printingType = $('#printing_type').val();
        let total_bag_weight = $('.total_bag_weight').val();



        if (!printingType) {
            // Show error
            $('#printing_type_error').removeClass('d-none');

            // Scroll to button group
           $('#users').animate({
                scrollTop: $('#printing_type_group').position().top + $('#users').scrollTop() - 300
            }, 500);

            // Temporary red border to grab attention
            // $('#printing_type_group').addClass('border border-danger rounded');

            // Remove highlight after 2 seconds
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

    $(document).on("click", ".printing_type", function(e) {
        var val = $(this).val();
        // $(".printing_type").removeClass("selected_print");
        // $(".bag_type").removeClass("selected_bag");
        // $(".bag_type").addClass("btn-success-2");
        // $(".printing_type").addClass("btn-success-2");
        $(this).addClass("selected_print");
        $(this).removeClass("btn-success-2");
        $(".print_select").val(val);

        if ((val == "BOPP") || (val == "OFFSET")) {

            $("#u_cut").prop("disabled", "true").css("cursor", "not-allowed");
        } else {

            $("#u_cut").removeAttr("disabled").css("cursor", "pointer");
        }

        if (val == "FLEXO") {
            $("#bopp_title").css("display", "none");
            $("#bopp_detail").css("display", "none");
            $("#bopp_extra_detail").css("display", "none");
            $("#bopp_metal").css("display", "none");
        } else {
            $("#bopp_title").css("display", "flex");
            $("#bopp_detail").css("display", "flex");
            $("#bopp_extra_detail").css("display", "flex");
            $("#bopp_metal").css("display", "flex");
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
    });

    $(document).on("change","#job_type",function(){        
        var name = $("#job_type  option:selected").text();        
        if(name == 'Pillow bags'){
            $("#pillow_bag").css("display","block");
            $("#gaz_bag").css("display","none");
            $("#gaz_bag input").attr("disabled", "disabled");
            $("#pillow_bag input").removeAttr("disabled");
            $("#pillow_handle_type").css("display","flex");
            $("#gaz_handle_type").css("display","none");
        }
        else if (name == 'Leader box bag'){
            $("#gaz_bag").css("display","block");
            $("#gaz_handle_type").css("display","block");
            $("#pillow_bag").css("display","none");
            $("#pillow_bag input").attr("disabled", "disabled");
            $("#gaz_bag input").removeAttr("disabled");
            $("#pillow_handle_type").css("display","none");   
        }
    });

    $(document).on("click", ".bag_type", function(e) {

        var val = $(this).val();
        $(".bag_type").removeClass("selected_bag");
        $(".bag_type").removeClass("active");        
        $(".bag_type").addClass("btn-outline-success-2");
        $(this).addClass("selected_bag");
        $(this).addClass("active");
        $(".bag_select").val(val);

        var printing_type = $(".print_select").val();
        
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
    });

    let partySuggetions = [];
    let jobNameSuggetions = [];

    $(document).on("blur", ".bag_job_size", function(e) {
        var bag_size_value = $(this).val();
        console.log(bag_size_value);

        var convert_inch = bag_size_value / 25.4;
        console.log(convert_inch)

        $("#job_size").text(convert_inch.toFixed(2) + "inch").css("font-weight", "bold");
        $("#gaz_bag #job_size").text(convert_inch.toFixed(2) + "inch").css("font-weight", "bold");
    });

    $(document).on("blur", ".bag_pet_size", function(e) {
        var bag_size_value = $(this).val();
        console.log(bag_size_value);

        var convert_inch = bag_size_value / 25.4;
        console.log(convert_inch)

        $("#bag_pet_inch").text(convert_inch.toFixed(2) + "inch").css("font-weight", "bold");
        $("#gaz_bag #bag_pet_inch").text(convert_inch.toFixed(2) + "inch").css("font-weight", "bold");
    });
    
    $(document).on("blur", ".bag_gaz_size", function(e) {
        var bag_size_value = $(this).val();
        console.log(bag_size_value);

        var convert_inch = bag_size_value / 25.4;
        console.log(convert_inch)

        $("#bag_gazette_inch").text(convert_inch.toFixed(2) + "inch").css("font-weight", "bold");
    });

    $('#party_name').keyup(function (event){
        let query = $(this).val();
        if (event.key === 'Enter') {
            event.preventDefault();

            if (partySuggestions.length > 0) {
                // Set input to first suggestion if not already selected
                const currentVal = $('#party_name').val().toLowerCase();
                const exactMatch = partySuggestions.find(p => p.party_name.toLowerCase() === currentVal);

                if (!exactMatch) {
                    $('#party_name').val(partySuggestions[0].party_name);
                }
                $('.party_name_suggestions').hide();
            }
            return false; // Prevent AJAX from firing again on Enter
        }
        if (query.length > 1) {            
            $.ajax({
                type: 'GET',
                url: '{{route("getPartyDetails")}}',
                data: {party_name: query},
                success: function(data){                    
                    let party_name_error = $('.party_name_error');
                    
                    if (data.status == true && data.party.length > 0) {  
                        partySuggestions = data.party; // Store globally                      
                        let html = '';
                        data.party.forEach(party => {
                            html += `<li style="list-style:none; padding:10px; cursor:pointer;" onclick="upodatePartyVal(this)">${party.party_name}</li>`
                        });                    
        
                        $('.party_name_suggestions').html(html).show();
                        party_name_error.hide();
                    }
                    else{
                        // alert('Please Enter correct Party name');

                        let currentVal = $('#party_name').val(); // assuming your input has id="party_name"
                        $('#party_name').val(currentVal.slice(0, -1));
                        $('.party_name_suggestions').hide();
                        party_name_error.html('Please Enter correct Party name').show();
                    }
    
                }
            })
        }
    })

    

    $('#party_name').on('blur', function (){                
        setTimeout(() => {
            if (partySuggestions.length > 0) {
                let currentVal = $('#party_name').val();
                let exactMatch = partySuggestions.find(p => p.party_name.toLowerCase() === currentVal.toLowerCase());

                if (!exactMatch) {
                    $('#party_name').val(partySuggestions[0].party_name);
                    $('.party_name_suggestions').hide();
                }
        
            }
        }, 200);
    })

    // $('#job_name').keyup(function (event){
    //     let query = $(this).val();
    //     if (event.key === 'Enter') {
    //         event.preventDefault();

    //         if (jobNameSuggetions.length > 0) {
    //             // Set input to first suggestion if not already selected
    //             const currentVal = $('#job_name').val().toLowerCase();
    //             const exactMatch = jobNameSuggetions.find(j => j.job_name.toLowerCase() === currentVal);

    //             if (!exactMatch) {
    //                 $('#job_name').val(jobNameSuggetions[0].job_name);
    //             }
    //             $('.job_name_suggestions').hide();
    //         }
    //         return false; // Prevent AJAX from firing again on Enter
    //     }
    //     if (query.length > 1) {            
    //         $.ajax({
    //             type: 'GET',
    //             url: '{{route("getJobDetails")}}',
    //             data: {job_name: query},
    //             success: function(data){
    //                 console.log(data);
    //                 let job_name_error = $('.job_name_error');
                    
    //                 if (data.status == true && data.job.length > 0) { 
    //                     jobNameSuggetions = data.job;                        
    //                     let html = '';
    //                     data.job.forEach(job => {
    //                         html += `<li style="list-style:none; padding:10px; cursor:pointer;" onclick="upodateJobVal(this)">${job.job_name}</li>`
    //                     });                    
        
    //                     job_name_error.hide();
    //                     $('.job_name_suggestions').html(html).show();
    //                 }
    //                 else{
    //                     // alert('Please Enter correct Job name');
                        
    //                     job_name_error.html('Please Enter correct Job name').show();

    //                     let currentVal = $('#job_name').val(); // assuming your input has id="party_name"
    //                     $('#job_name').val(currentVal.slice(0, -1));
    //                     $('.job_name_suggestions').hide();
    //                 }
    
    //             }
    //         })
    //     }
    // })

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
    })

    function upodatePartyVal(elem){
        let party_name = $(elem).text();
        $('#party_name').val(party_name);
        $('.party_name_suggestions').hide();
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



    function selectBoppItem(li) {        
        $('.bopp-item-suggestions').html('').hide();
        $('#bopp_item_code').val(li.innerText);
        // $('#bopp_item_code').trigger('input'); // trigger to re-run AJAX                
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

    function updateBoppcodes(){
        let bopp_item_code = $('#bopp_item_code').val()
        $.ajax({
            type: "GET",
            url: "{{ route('check-bopp-item') }}",
            data: { bopp_item_code: bopp_item_code },
            success: function(data) {
                console.log(data);
                console.log(bopp_item_code);            
                if (data.status == true && data.item != null) {
                    $(".bopp-cat").val(data.item.bopp_category);
                    $(".bopp_size").val(data.item.bopp_size);
                    $(".bopp_micron").val(data.item.bopp_micron);

                    let b_size = Number(data.item.bopp_size) || 1;
                    let b_micron = Number(data.item.bopp_micron) || 1;
                    
                    let cylinder_circum = Number($(".c_circum").val());
                    if (cylinder_circum < 0) {
                        cylinder_circum = 1;
                    }
                    let repeat_cylinder = Number($(".repeat_cylinder").val()) || 1;
                    let coil_cylinder = Number($(".coil_cylinder").val()) || 1;

                    

                    var b_weight = (b_micron * .91 * b_size * cylinder_circum / 1550) / 645.16;
                    
                    console.log(b_size, b_micron, cylinder_circum, repeat_cylinder, coil_cylinder);
                    
                    console.log(`Multiply bopp : ${b_micron * 0.91 * b_size * cylinder_circum}`);
                    console.log(`B weight : ${b_weight}`);
                    
                    
                    

                    // Get other weights
                    let h_weight = Number($(".handle_weight").val()) || 0;
                    // let c_weight = Number($(".cut_wastage").val()) || 0;
                    let ll_weight = Number($(".lamination_weight").val()) || 0;
                    let ff_weight = Number($(".fabric_weight").val()) || 0;
                    let mm_weight = Number($(".metal_weight").val()) || 0;

                    // Adjust per cylinder repeat
                    if (repeat_cylinder > 0) {                        
                        b_weight = b_weight / repeat_cylinder;
                        ll_weight = ll_weight / repeat_cylinder;
                        ff_weight = ff_weight / repeat_cylinder;
                        mm_weight = mm_weight / repeat_cylinder;
                    }

                    if (coil_cylinder > 0) {                        
                        b_weight = b_weight / coil_cylinder;
                        ll_weight = ll_weight / coil_cylinder;
                        ff_weight = ff_weight / coil_cylinder;
                        mm_weight = mm_weight / coil_cylinder;
                    }

                    $(".bopp_weight").val(b_weight.toFixed(2));
                    // $(".bopp_weight").val(b_weight);

                    // let t_weight = h_weight + ll_weight + ff_weight + b_weight + mm_weight - c_weight;
                    let t_weight = h_weight + ll_weight + ff_weight + b_weight + mm_weight;
                    
                    console.log(`total weight by bopp code :  ${t_weight}`);
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
                console.log(data);
                if (data.status == true && data.item != null) {
                    
                    $(".pp_cat").val(data.item.category_name);
                    $(".fabric_size").val(data.check_item.non_size);
                    $(".fabric_gsm").val(data.check_item.non_gsm);

                    var f_size = data.check_item.non_size;
                    var f_gsm = data.check_item.non_gsm;
                    var cylinder_circum = $(".c_circum").val();
                    if (cylinder_circum < 0) {
                        cylinder_circum = 1;
                    }
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
                    $(".fabric_weight").val(f_weight.toFixed(2));
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

                    console.log(`total weight by fibre code :  ${t_weight}`);
                    
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

    $(".bopp_item_code").on('input', function () {        
        updateBoppSuggestions(this);
        updateBoppcodes();
    });

    $(".fabric_item_code").on('input', function (){
        updateFabricSuggestions(this);
        updateFibrecodes();
    })

    $(".bopp_item_code").on('keydown', function (e){
        if(e.keyCode == 13){
            e.preventDefault();
            const firstSuggestion = $('.bopp-item-suggestions li:first');
            if (firstSuggestion.length) {
                const value = firstSuggestion.text().trim();
                $(this).val(value);
                $('.bopp-item-suggestions').html('').hide();
                updateBoppcodes();
            }            
        }
    })

    $(".fabric_item_code").on('keydown', function (e){
        if(e.keyCode == 13){
            e.preventDefault();
            const firstSuggestion = $('.fabric-item-suggestions li:first');
            if (firstSuggestion.length) {
                const value = firstSuggestion.text().trim();
                $(this).val(value);
                $('.fabric-item-suggestions').html('').hide();
                updateFibrecodes();
            }            
        }
    })

    function updateFabricSuggestions(elem) {
        var item_code = $(elem).val();                                            

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


    $(".metal_item_code").on('input', function (){
        updateMetalSuggestions(this);
        updateMetalcodes();                
    })

    function updateMetalcodes(){
        var bopp_item_code = $(".metal_item_code").val();
        $.ajax({
            type: "GET",
            url: "{{route('check-bopp-item')}}",
            data: {bopp_item_code: bopp_item_code},
            success: function(data){
                console.log(data);
                if (data.status == true && data.item != null) {
                    
                    $(".metal_type").val(data.item.bopp_category);
                    $(".metal_size").val(data.item.bopp_size);
                    $(".metal_micron").val(data.item.bopp_micron);

                    var m_size = data.item.bopp_size || 1;
                    var m_micron = data.item.bopp_micron || 1;
                    var cylinder_circum = $(".c_circum").val();
                    if (cylinder_circum < 0) {
                        cylinder_circum = 1;
                    }
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
                    $(".metal_weight").val(m_weight.toFixed(2));
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
                    console.log(`total weight by metal item code :  ${t_weight}`);
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
    

    $("input[type=radio][name=bopp_metal_type]").on("change", function() {
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

    $("input[type=checkbox][name=bopp_metal_type]").on("change", function() {
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

        $(".bopp_weight").val(b_weight.toFixed(2));

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

        console.log(`total weight by bopp micron :  ${t_weight}`);

        $(".total_bag_weight").val(t_weight.toFixed(2));

    });


    $(".bopp_size").on("keyup", function() {
        var b_size = $(this).val() || 1;
        var b_micron = $(".bopp_micron").val() || 1;
        var cylinder_circum = $(".c_circum").val() || 1;
        var b_weight = (b_micron * .91 * b_size * cylinder_circum / 1550) / 645.16;

        $(".bopp_weight").val(b_weight.toFixed(2));

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
        console.log(`total weight by bopp size :  ${t_weight}`);
        $(".total_bag_weight").val(t_weight.toFixed(2));


    });


    $(".c_circum").on("keyup", function() {
        var cylinder_circum = $(this).val() || 1;
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
        $(".bopp_weight").val(b_weight.toFixed(2));
        $(".metal_weight").val(m_weight.toFixed(2));
        $(".fabric_weight").val(f_weight.toFixed(2));
        $(".lamination_weight").val(l_weight.toFixed(2));
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


        console.log(`total weight by circum :  ${t_weight}`);
        $(".total_bag_weight").val(t_weight.toFixed(2));
    });

    $(".repeat_cylinder").on("keyup", function() {
        var repeat_cylinder = $(this).val();
        let coil_cylinder = Number($(".coil_cylinder").val()) || 1;
        var b_micron = $(".bopp_micron").val() || 1;
        var b_size = $(".bopp_size").val() || 1;
        var cylinder_circum = $(".c_circum").val() || 1;
        var b_weight = (Number(b_micron) * 0.91 * Number(b_size) * Number(cylinder_circum) / 1550) / 645.16;
        console.log('-----------');        
        console.log(`Multiply : ${b_weight}`);
        
        var m_size = $(".metal_size").val();
        var m_micron = $(".metal_micron").val();
        var m_weight = (m_micron * .91 * m_size * cylinder_circum / 1550) / 645.16;
        var f_gsm = $(".fabric_gsm").val();
        var f_size = $(".fabric_size").val();
        var f_weight = (f_gsm * f_size * cylinder_circum / 25.4) / 1550;
        var l_gsm = $(".lamination_gsm").val();
        var l_size = $(".fabric_size").val();
        var l_weight = (l_gsm * l_size * cylinder_circum / 25.4) / 1550;
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
        $(".bopp_weight").val(b_weight.toFixed(2));
        $(".metal_weight").val(m_weight.toFixed(2));
        $(".fabric_weight").val(f_weight.toFixed(2));
        $(".lamination_weight").val(l_weight.toFixed(2));
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

        console.log(`total weight by repeat cylinder :  ${t_weight}`);
        $(".total_bag_weight").val(t_weight.toFixed(2));
    });

    $(".metal_micron").on("keyup", function() {
        var m_micron = $(this).val();
        var m_size = $(".metal_size").val();
        var cylinder_circum = $(".c_circum").val();
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
        $(".metal_weight").val(m_weight.toFixed(2));
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

        console.log(`total weight by metal micron :  ${t_weight}`);
        $(".total_bag_weight").val(t_weight.toFixed(2));

    });

    $(".metal_size").on("keyup", function() {

        var m_size = $(this).val();
        var m_micron = $(".metal_micron").val();
        var cylinder_circum = $(".c_circum").val();
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
        $(".metal_weight").val(m_weight.toFixed(2));
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


        console.log(`total weight by metal size :  ${t_weight}`);
        $(".total_bag_weight").val(t_weight.toFixed(2));

    });

    $(".fabric_gsm").on("keyup", function() {
        var f_gsm = $(this).val();
        var f_size = $(".fabric_size").val();
        var cylinder_circum = $(".c_circum").val();

        var f_weight = (f_gsm * f_size * cylinder_circum / 25.4) / 1550;

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

        console.log(`total weight by fabric gsm :  ${t_weight}`);
        $(".total_bag_weight").val(t_weight.toFixed(2));

    });

    $(".fabric_size").on("keyup", function() {
        var f_size = $(this).val() || 1;
        var f_gsm = $(".fabric_gsm").val() || 1;
        var cylinder_circum = $(".c_circum").val() || 1;
        var f_weight = (f_gsm * f_size * cylinder_circum / 25.4) / 1550;

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


        console.log(`total weight by fabric size :  ${t_weight}`);
        $(".total_bag_weight").val(t_weight.toFixed(2));
    });

    $(".lamination_gsm").on("keyup", function() {
        var l_gsm = Number($(this).val()) || 1;
        var l_size = Number($(".fabric_size").val()) || 1;
        var cylinder_circum = Number($(".c_circum").val());
        if (cylinder_circum < 0) {
            cylinder_circum = 1;
        }
        var repeat_cylinder = Number($(".repeat_cylinder").val());
        let coil_cylinder = Number($(".coil_cylinder").val()) || 1;
        var l_weight = (l_gsm * l_size * cylinder_circum / 25.4) / 1550;
        
            
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
        $(".lamination_weight").val(l_weight.toFixed(2));
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
        console.log(`total weight by lamination gsm :  ${t_weight}`);
        $(".total_bag_weight").val(t_weight.toFixed(2));
    });


    $(".handle_weight").on("keyup", function() {
        var h_weight = $(this).val();
        var ll_weight = $(".lamination_weight").val() || 0;
        var ff_weight = $(".fabric_weight").val() || 0;
        var bb_weight = $(".bopp_weight").val() || 0;
        // var c_weight = $(".cut_wastage").val() || 0;
        var mm_weight = $(".metal_weight").val() || 0;
        if (mm_weight == "") {
            var mm_weight = 0.0;
        }
        // if (c_weight == "") {
        //     var c_weight = 0.0;
        // }
        if (bb_weight == "") {
            var bb_weight = 0.0;
        }
        // var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight) - parseFloat(c_weight);
        var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight);

        console.log(h_weight, ll_weight, ff_weight, bb_weight, mm_weight);
        

        console.log(`total weight by handle weight :  ${t_weight}`);
        $(".total_bag_weight").val(t_weight.toFixed(2));

    });

    // $(".cut_wastage").on("keyup", function() {
    //     var c_weight = $(this).val();
    //     var ll_weight = $(".lamination_weight").val();
    //     var ff_weight = $(".fabric_weight").val();
    //     var bb_weight = $(".bopp_weight").val();
    //     var h_weight = $(".handle_weight").val();
    //     var mm_weight = $(".metal_weight").val();
    //     if (mm_weight == "") {
    //         var mm_weight = 0.0;
    //     }
    //     if (h_weight == "") {
    //         var h_weight = 0.0;
    //     }
    //     if (bb_weight == "") {
    //         var bb_weight = 0.0;
    //     }
    //     var t_weight = parseFloat(h_weight) + parseFloat(ll_weight) + parseFloat(ff_weight) + parseFloat(bb_weight) + parseFloat(mm_weight) - parseFloat(c_weight);

    //     console.log(`total weight by cut wastage :  ${t_weight}`);

    //     $(".total_bag_weight").val(t_weight.toFixed(2));

    // });


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
                { data: 'party_name', name: 'party_name' },                
                { data: 'job_name', name: 'job_name', width: '300px' }, 
                { data: 'art_work', name: 'art_work', width: '300px' }, 
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
                        // console.log('Status Updated !!');
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

    function oenAddModal(){
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
                console.log(response)
                let data = response.data;
                let jobdetails = data.jobdetails;
                let party = data.party;
                let jobname = data.jobname;
                let jobtype = data.jobtype;

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


                
                if (jobdetails.bag_gazette > 0) {                    
                    $('#bag_gazette_inch').text((jobdetails.bag_gazette / 25.5).toFixed(2) + ' inches');
                }
                
                $('#widthIninches').text((jobdetails.bag_circum / 25.4).toFixed(2) + ' inches');
                $('#job_size').text((jobdetails.bag_circum / 25.4).toFixed(2) + ' inches');
                
                $('#heightIninches').text((jobdetails.bag_pet / 25.4).toFixed(2) + ' inches');
                $('#bag_pet_inch').text((jobdetails.bag_pet / 25.4).toFixed(2) + ' inches');


                $('#job_description').val(jobdetails.job_description);

                var name = $("#job_type  option:selected").text(); 
                if(name == 'Pillow bags'){
                    $("#pillow_bag").css("display","block");
                    $("#gaz_bag").css("display","none");
                    $("#gaz_bag input").attr("disabled", "disabled");
                    $("#pillow_bag input").removeAttr("disabled");
                    $("#pillow_handle_type").css("display","flex");
                    $("#gaz_handle_type").css("display","none");
                }
                else if (name == 'Leader box bag'){
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
                        kld_images_preview.append(`<a href="${baseUrl}/${item.kld_images}" data-fancybox="kld_gallery" style="display:inline-block; margin-right:5px;">
                                                        <img src="${baseUrl}/${item.kld_images}" style="width:100px; height:auto; cursor:pointer;" alt="KLD Image">
                                                    </a>
                                                `);
                    });
                } 
                
                if (mockup_images) {                        
                    let baseUrl = "{{ asset('images/job-images') }}"; // Blade renders this once    
                    mockup_images.forEach(item => {
                        mock_up_images_preview.append(`
                                                        <a href="${baseUrl}/${item.job_images}" data-fancybox="mockup_gallery" style="display:inline-block; margin-right:5px;">
                                                            <img src="${baseUrl}/${item.job_images}" style="width:100px; height:auto; cursor:pointer;" alt="Mockup Image">
                                                        </a>
                                                    `);
                    });
                } 

                if (approval_images) {                        
                    let baseUrl = "{{ asset('images/job-images') }}"; // Blade renders this once    
                    approval_images.forEach(item => {
                        approve_images_preview.append(`
                                                        <a href="${baseUrl}/${item.approve_image}" data-fancybox="approve_gallery" style="display:inline-block; margin-right:5px;">
                                                            <img src="${baseUrl}/${item.approve_image}" style="width:100px; height:auto; cursor:pointer;" alt="Approval Image">
                                                        </a>
                                                    `);
                    });
                } 

                if (separation_images) {                        
                    let baseUrl = "{{ asset('images/job-images') }}"; // Blade renders this once    
                    separation_images.forEach(item => {
                        suppression_images_preview.append(`
                                                            <a href="${baseUrl}/${item.kld_images}" data-fancybox="suppression_gallery" style="display:inline-block; margin-right:5px;">
                                                                <img src="${baseUrl}/${item.kld_images}" style="width:100px; height:auto; cursor:pointer;" alt="Suppression Image">
                                                            </a>
                                                        `);
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
                                console.log(channel, value);
                                                           
                                $(`#cmyk-input-${channel}-${index + 1}`).val(value);
                            });
                            console.log(cmykcolors);                                            
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

                // First, uncheck both
                $('input[name="bopp_metal_type"]').prop('checked', false);

                // Then, check the one that matches value
                $(`input[name="bopp_metal_type"][value="${isMetal}"]`).prop('checked', true);

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

                let job_cut = data.job_cut;

                if (job_cut) {
                    // $('#cut_title').show();
                    for(const key in job_cut){
                        const inputName = `cut[${key}]`;
                        $(`input[name="${inputName}"]`).val(job_cut[key]);
                    }
                    // $('#cut_detail').show().css('display', 'flex');

                }

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
                console.log(response)
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
                    // $("#metal_item_code").val();

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

                $('#widthIninches').text((jobdetails.bag_circum / 25.4).toFixed(2) + ' inches');
                $('#heightIninches').text((jobdetails.bag_pet / 25.4).toFixed(2) + ' inches');
                if (jobdetails.bag_gazette > 0) {                    
                    $('#bag_gazette_inch').text((jobdetails.bag_gazette / 25.5).toFixed(2) + ' inches');
                }

                var name = $("#job_type  option:selected").text(); 
                if(name == 'Pillow bags'){
                    $("#pillow_bag").css("display","block");
                    $("#gaz_bag").css("display","none");
                    $("#gaz_bag input").attr("disabled", "disabled");
                    $("#pillow_bag input").removeAttr("disabled");
                    $("#pillow_handle_type").css("display","flex");
                    $("#gaz_handle_type").css("display","none");
                }
                else if (name == 'Leader box bag'){
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
                        kld_images_preview.append(`<a href="${baseUrl}/${item.kld_images}" data-fancybox="kld_gallery" style="display:inline-block; margin-right:5px;">
                                                        <img src="${baseUrl}/${item.kld_images}" style="width:100px; height:auto; cursor:pointer;" alt="KLD Image">
                                                    </a>
                                                `);
                    });
                } 
                
                if (mockup_images) {                        
                    let baseUrl = "{{ asset('images/job-images') }}"; // Blade renders this once    
                    mockup_images.forEach(item => {
                        mock_up_images_preview.append(`
                                                        <a href="${baseUrl}/${item.job_images}" data-fancybox="mockup_gallery" style="display:inline-block; margin-right:5px;">
                                                            <img src="${baseUrl}/${item.job_images}" style="width:100px; height:auto; cursor:pointer;" alt="Mockup Image">
                                                        </a>
                                                    `);
                    });
                } 

                if (approval_images) {                        
                    let baseUrl = "{{ asset('images/job-images') }}"; // Blade renders this once    
                    approval_images.forEach(item => {
                        approve_images_preview.append(`
                                                        <a href="${baseUrl}/${item.approve_image}" data-fancybox="approve_gallery" style="display:inline-block; margin-right:5px;">
                                                            <img src="${baseUrl}/${item.approve_image}" style="width:100px; height:auto; cursor:pointer;" alt="Approval Image">
                                                        </a>
                                                    `);
                    });
                } 

                if (separation_images) {                        
                    let baseUrl = "{{ asset('images/job-images') }}"; // Blade renders this once    
                    separation_images.forEach(item => {
                        suppression_images_preview.append(`
                                                            <a href="${baseUrl}/${item.kld_images}" data-fancybox="suppression_gallery" style="display:inline-block; margin-right:5px;">
                                                                <img src="${baseUrl}/${item.kld_images}" style="width:100px; height:auto; cursor:pointer;" alt="Suppression Image">
                                                            </a>
                                                        `);
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
                                console.log(channel, value);
                                                           
                                $(`#cmyk-input-${channel}-${index + 1}`).val(value);
                            });
                            console.log(cmykcolors);                                            
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

                // First, uncheck both
                $('input[name="bopp_metal_type"]').prop('checked', false);

                // Then, check the one that matches value
                $(`input[name="bopp_metal_type"][value="${isMetal}"]`).prop('checked', true);

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

                let job_cut = data.job_cut;

                if (job_cut) {
                    // $('#cut_title').show();
                    for(const key in job_cut){
                        const inputName = `cut[${key}]`;
                        $(`input[name="${inputName}"]`).val(job_cut[key]);
                    }
                    // $('#cut_detail').show().css('display', 'flex');

                }

                $('#users form :input:not(.close-popup)').prop('disabled', true);
                $('.image-input').hide();
                $('#users').modal('show');
                $('.modal-footer').hide();
                $('#users .modal-title').html('Job details');                                        
            }
        })

        
    }

    $('.close-modal').on('click', function (){    
        $('#users .modal-title').html('Add Job details');    
        $('#users form :input').prop('disabled', false); 

        let kld_images_preview = $('#kld_images_preview');
        let mock_up_images_preview = $('#mock_up_images_preview');
        let suppression_images_preview = $('#suppression_images_preview');
        let approve_images_preview = $('#approve_images_preview');
        
        kld_images_preview.empty().hide();
        mock_up_images_preview.empty().hide();
        approve_images_preview.empty().hide();
        suppression_images_preview.empty().hide();
        $('#users').modal('hide');        
        $('.image-input').show();
        $('.modal-footer').show();
        $('#users form')[0].reset();
        $('.bag_type').each(function (){
            $(this).removeClass('active');
        });

        $('.printing_type').each(function (){
            $(this).removeClass('active');
        });

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

        // Optionally, reset the CMYK popup (if visible)
        $('.cmyk-popup').hide();
    })    
</script>

{{-- <script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/lightgallery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/plugins/zoom/lg-zoom.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/plugins/fullscreen/lg-fullscreen.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/plugins/rotate/lg-rotate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/plugins/thumbnail/lg-thumbnail.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/plugins/share/lg-share.min.js"></script> --}}

{{-- <script>        
    lightGallery(document.getElementById('kld_images_preview'), {
    speed: 500,
    plugins: [lgZoom, lgFullscreen, lgRotate, lgThumbnail],
    zoom: true,
    fullscreen: true,
    rotate: true,
    thumbnail: true,
    share: true,
    actualSize: true,
});
</script> --}}
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5/dist/fancybox/fancybox.umd.js"></script>
<script>
  Fancybox.bind("[data-fancybox='kld_gallery']");
  Fancybox.bind("[data-fancybox='mockup_gallery']");
  Fancybox.bind("[data-fancybox='approve_gallery']");
  Fancybox.bind("[data-fancybox='suppression_gallery']");

  document.addEventListener("DOMContentLoaded", function () {        
    Fancybox.bind("[data-fancybox^='kld_gallery_img_']", {
        // Optional: Fancybox settings
        Thumbs: {
            autoStart: false
        },
        Toolbar: true,
        groupAll: false // This ensures each group is treated separately
    }); 
});


</script>

@include('admin.pages.jobdetails.index_script')
@endsection

