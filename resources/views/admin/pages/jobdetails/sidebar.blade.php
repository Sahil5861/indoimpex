
<?php
    $allJobesCount = \App\Models\JobDetails::where('approval_status', '1')->where('job_status', '1')->count();
    $pendingJobesCount = \App\Models\JobDetails::where('approval_status', '0')->where('job_status', '1')->count();

    $savedJobesCount = \App\Models\JobDetails::where('job_status', '0')->where('approval_status', '0')->where('saved_by', Auth::user()->id)->count();
    if (Auth::user()->role_id == 1) {
        $savedJobesCount = \App\Models\JobDetails::where('job_status', '0')->where('approval_status', '0')->count();
    }
    
?>


<!-- Main sidebar -->
<div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg">

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- Sidebar header -->
        <div class="sidebar-section">
            <div class="sidebar-section-body d-flex justify-content-center">
                <h5 class="sidebar-resize-hide flex-grow-1 my-auto">{{$sidebarTitle}}</h5>

                {{-- <div>
                    <button type="button"
                        class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
                        <i class="ph-arrows-left-right"></i>
                    </button>

                    <button type="button"
                        class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-mobile-main-toggle d-lg-none">
                        <i class="ph-x"></i>
                    </button>
                </div> --}}
            </div>
        </div>
        <!-- /sidebar header -->

        <!-- Main navigation -->
        <div class="sidebar-section">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <!-- Main -->
                <li class="nav-item-header pt-0">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Main</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>

                <li class="nav-item">
                    <a href="{{ route('jobdetails.view') }}"
                        class="nav-link {{ request()->routeIs('jobdetails.view') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 2a1 1 0 0 0-1 1v1H6a2 2 0 0 0-2 2v14c0 1.103.897 2 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2V3a1 1 0 0 0-1-1H9zm0 2h6v1H9V4zm-3 3h12v14H6V7zm2 2v2h8V9H8zm0 4v2h8v-2H8z"/>
                        </svg>
                        <span>&nbsp;&nbsp;Main</span>
                    </a>
                </li> 

                @if (hasPermission('Job Details All View', 'View'))                                            
                <li class="nav-item">
                    <a href="{{ route('jobdetails.view.all') }}"
                        class="nav-link {{ request()->routeIs('jobdetails.view.all') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 2a1 1 0 0 0-1 1v1H6a2 2 0 0 0-2 2v14c0 1.103.897 2 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2V3a1 1 0 0 0-1-1H9zm0 2h6v1H9V4zm-3 3h12v14H6V7zm2 2v2h8V9H8zm0 4v2h8v-2H8z"/>
                        </svg>
                        <span>&nbsp;&nbsp;All Jobs</span>
                    </a>
                </li>   
                @endif
                @if (hasPermission('Job Details Pending View', 'View'))    
                <li class="nav-item">
                    <a href="{{ route('jobdetails.view.pending') }}"
                        class="nav-link {{ request()->routeIs('jobdetails.view.pending') ? 'active' : '' }}">
                        {{-- <i class="ph-house"></i> --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 1a11 11 0 1 0 11 11A11.012 11.012 0 0 0 12 1zm0 20a9 9 0 1 1 9-9 9.01 9.01 0 0 1-9 9zm1-13h-2v6h6v-2h-4z"/>
                        </svg>
                        <span>&nbsp;&nbsp;Pending Jobs</span>
                    </a>
                </li>   
                @endif
                @if (hasPermission('Job Details Saved View', 'View'))    
                <li class="nav-item">
                    <a href="{{ route('jobdetails.view.saved') }}"
                        class="nav-link {{ request()->routeIs('jobdetails.view.saved') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 2a2 2 0 0 0-2 2v18l8-5.333L20 22V4a2 2 0 0 0-2-2H6z"/>
                        </svg>
                        <span>&nbsp;&nbsp;Saved Jobs</span>
                    </a>
                </li>  
                @endif 
                {{-- <li class="nav-item nav-item-submenu">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('admin.brand') || request()->routeIs('admin.category') || request()->routeIs('admin.grouprelation') || request()->routeIs('admin.product') ? 'active' : '' }}">
                        <i class="ph-layout"></i>
                        <span>Manage Products</span>
                    </a>
                    <ul class="nav-group-sub {{request()->routeIs('admin.brand', 'admin.category', 'admin.grouprelation', 'admin.product') ? '' : 'collapse'}}">                        
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link {{ request()->routeIs('admin.category') ? 'active' : '' }}">
                                <i class="ph-layout"></i>
                                <span>Categories</span>
                            </a>
                        </li>                        
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link {{ request()->routeIs('admin.product') ? 'active' : '' }}">
                                <i class="ph-layout"></i>
                                <span>Products</span>
                            </a>
                        </li>
                    </ul>
                </li>                 --}}

            </ul>

        </div>
        <!-- /main navigation -->
    </div>
    <!-- /sidebar content -->
</div>
<!-- /main sidebar -->