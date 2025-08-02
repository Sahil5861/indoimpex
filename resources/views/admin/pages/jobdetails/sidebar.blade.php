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
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="ph-house"></i>
                        <span>Dashboard</span>
                    </a>
                </li>                                
                <li class="nav-item nav-item-submenu">
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
                </li>                

            </ul>

        </div>
        <!-- /main navigation -->
    </div>
    <!-- /sidebar content -->
</div>
<!-- /main sidebar -->