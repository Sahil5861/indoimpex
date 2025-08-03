
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
        {{-- <div class="sidebar-section">
            <ul class="nav nav-sidebar" data-nav-type="accordion">                
                                
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link {{ request()->routeIs('boppstock.items.view') || request()->routeIs('bopp-stock.categories.view') ? 'active' : '' }}">
                        <i class="ph-package"></i>
                        <span>Metarialistic</span>
                    </a>
                    <ul class="nav-group-sub {{request()->routeIs('admin.material-stock.bopp', 'admin.material-stock.bopp-roll') ? 'show' : 'collapse'}}">                                                
                        <li class="nav-item nav-item-submenu">
                            <a href="#" class="nav-link {{ request()->routeIs('boppstock.items.view') ? 'active' : '' }}">
                                <i class="ph-squares-four"></i>
                                <span>Bopp</span>
                            </a>
                            <ul class="nav-group-sub {{request()->routeIs('admin.material-stock.bopp', 'admin.material-stock.bopp-roll') ? 'show' : 'collapse'}}">                                                
                                <li class="nav-item">
                                    <a href="{{route('admin.material-stock.bopp')}}" class="nav-link {{ request()->routeIs('admin.material-stock.bopp') ? 'active' : '' }}">
                                        <i class="ph-layout"></i>
                                        <span>Consolidated</span>
                                    </a>
                                </li>   
                                
                                <li class="nav-item">
                                    <a href="{{route('admin.material-stock.bopp-roll')}}" class="nav-link {{ request()->routeIs('admin.material-stock.bopp-roll') ? 'active' : '' }}">
                                        <i class="ph-layout"></i>
                                        <span>Roll Form</span>
                                    </a>
                                </li>   
                            </ul>
                        </li>                                                                              
                    </ul>
                </li>                                     
            </ul>

        </div> --}}
        <div class="sidebar-section">
            <ul class="nav nav-sidebar" id="sidebarMenu" data-nav-type="accordion">
                <li class="nav-item nav-item-submenu">
                    <a href="#materialMenu" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.material-stock.bopp') || request()->routeIs('admin.material-stock.bopp-roll') ? 'true' : 'false' }}" class="nav-link">
                        <i class="ph-package"></i>
                        <span>Metarialistic</span>
                    </a>

                    <ul id="materialMenu" class="nav-group-sub collapse {{ request()->routeIs('admin.material-stock.bopp') || request()->routeIs('admin.material-stock.bopp-roll') ? 'show' : '' }}" data-bs-parent="#sidebarMenu">
                        <li class="nav-item nav-item-submenu">
                            <a href="#boppMenu" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.material-stock.bopp') || request()->routeIs('admin.material-stock.bopp-roll') ? 'true' : 'false' }}" class="nav-link">
                                <i class="ph-squares-four"></i>
                                <span>Bopp</span>
                            </a>

                            <ul id="boppMenu" class="nav-group-sub collapse {{ request()->routeIs('admin.material-stock.bopp') || request()->routeIs('admin.material-stock.bopp-roll') ? 'show' : '' }}" data-bs-parent="#materialMenu">
                                <li class="nav-item">
                                    <a href="{{route('admin.material-stock.bopp')}}" class="nav-link {{ request()->routeIs('admin.material-stock.bopp') ? 'active' : '' }}">
                                        <i class="ph-layout"></i>
                                        <span>Consolidated</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{route('admin.material-stock.bopp-roll')}}" class="nav-link {{ request()->routeIs('admin.material-stock.bopp-roll') ? 'active' : '' }}">
                                        <i class="ph-layout"></i>
                                        <span>Roll Form</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

        <!-- /main navigation -->
    </div>
    <!-- /sidebar content -->
</div>
<!-- /main sidebar -->