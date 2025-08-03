
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
                    <a href="{{ route('admin.masters') }}"
                        class="nav-link {{ request()->routeIs('admin.masters') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 2a1 1 0 0 0-1 1v1H6a2 2 0 0 0-2 2v14c0 1.103.897 2 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2V3a1 1 0 0 0-1-1H9zm0 2h6v1H9V4zm-3 3h12v14H6V7zm2 2v2h8V9H8zm0 4v2h8v-2H8z"/>
                        </svg>
                        <span>&nbsp;&nbsp;Main</span>
                    </a>
                </li> 
                
                @if (hasPermission('Bopp Stock Items View', 'View') ||  hasPermission('Bopp Stock Categories View', 'View'))								
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link {{ request()->routeIs('boppstock.items.view') || request()->routeIs('bopp-stock.categories.view') ? 'active' : '' }}">
                        <i class="ph-squares-four"></i>
                        <span>Bopp</span>
                    </a>
                    <ul class="nav-group-sub {{request()->routeIs('boppstock.items.view', 'bopp-stock.categories.view') ? '' : 'collapse'}}">                        
                        @if (hasPermission('Bopp Stock Items View', 'View'))								
                        <li class="nav-item">
                            <a href="{{route('boppstock.items.view')}}"
                                class="nav-link {{ request()->routeIs('boppstock.items.view') ? 'active' : '' }}">
                                <i class="ph-cube"></i> 
                                <span>Items</span>
                            </a>
                        </li>      
                        @endif
                        @if (hasPermission('Bopp Stock Categories View', 'View'))								                  
                        <li class="nav-item">
                            <a href="{{route('bopp-stock.categories.view')}}"
                                class="nav-link {{ request()->routeIs('bopp-stock.categories.view') ? 'active' : '' }}">
                                <i class="ph-dots-nine"></i> 
                                <span>Categories</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li> 
                @endif
                
                @if (hasPermission('Non Woven Fabric Items View', 'View') ||  hasPermission('Non Woven Fabric Categories View', 'View'))								
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link {{ request()->routeIs('non-wovenfabricstock.items.view') || request()->routeIs('non-wovenfabricstock.categories.view') ? 'active' : '' }}">
                        <i class="ph-stack"></i>
                        <span>Non Woven</span>
                    </a>
                    <ul class="nav-group-sub {{request()->routeIs('non-wovenfabricstock.items.view', 'non-wovenfabricstock.categories.view') ? '' : 'collapse'}}">                        
                        @if (hasPermission('Non Woven Fabric Items View', 'View'))								
                        <li class="nav-item">
                            <a href="{{route('non-wovenfabricstock.items.view')}}"
                                class="nav-link {{ request()->routeIs('non-wovenfabricstock.items.view') ? 'active' : '' }}">
                                <i class="ph-cube"></i> 
                                <span>Items</span>
                            </a>
                        </li>      
                        @endif
                        @if (hasPermission('Non Woven Fabric Categories View', 'View'))								                  
                        <li class="nav-item">
                            <a href="{{route('non-wovenfabricstock.categories.view')}}"
                                class="nav-link {{ request()->routeIs('non-wovenfabricstock.categories.view') ? 'active' : '' }}">
                                <i class="ph-dots-nine"></i> 
                                <span>Categories</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li> 
                @endif

                @if (hasPermission('PP Woven Fabric stock Items View', 'View') || hasPermission('PP Woven Fabric stock Categories View', 'View'))									
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link {{ request()->routeIs('ppwovenfabricstock.items.view') || request()->routeIs('ppwovenfabricstock.categories.view') ? 'active' : '' }}">
                        <i class="ph-folders"></i>  
                        <span>PP Woven</span>
                    </a>
                    <ul class="nav-group-sub {{request()->routeIs('ppwovenfabricstock.items.view', 'ppwovenfabricstock.categories.view') ? '' : 'collapse'}}">                        
                        @if (hasPermission('PP Woven Fabric stock Items View', 'View'))										
                        <li class="nav-item">
                            <a href="{{route('ppwovenfabricstock.items.view')}}"
                                class="nav-link {{ request()->routeIs('ppwovenfabricstock.items.view') ? 'active' : '' }}">
                                <i class="ph-cube"></i> 
                                <span>Items</span>
                            </a>
                        </li>      
                        @endif
                        @if (hasPermission('PP Woven Fabric stock Categories View', 'View'))
                        <li class="nav-item">
                            <a href="{{route('ppwovenfabricstock.categories.view')}}"
                                class="nav-link {{ request()->routeIs('ppwovenfabricstock.categories.view') ? 'active' : '' }}">
                                <i class="ph-dots-nine"></i> 
                                <span>Categories</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li> 
                @endif

                @if (hasPermission('Party View', 'View'))									
                <li class="nav-item">
                    <a href="{{route('admin.party')}}"
                        class="nav-link {{ request()->routeIs('admin.party') ? 'active' : '' }}">
                        <i class="ph-user-circle"></i>
                        <span>Party</span>
                    </a>
                </li>      
                @endif

                @if (hasPermission('Job Type View', 'View'))											
                <li class="nav-item">
                    <a href="{{route('jobtypes.view')}}"
                        class="nav-link {{ request()->routeIs('jobtypes.view') ? 'active' : '' }}">
                        <i class="ph-clipboard"></i>
                        <span>Job Types</span>
                    </a>
                </li>      
                @endif

                

            </ul>

        </div>
        <!-- /main navigation -->
    </div>
    <!-- /sidebar content -->
</div>
<!-- /main sidebar -->