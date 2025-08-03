@php
    $routeName = request()->route()->getName();

    // Active states
    $isActiveBoppConsolidated = $routeName === 'admin.material-stock.bopp';
    $isActiveBoppRoll = $routeName === 'admin.material-stock.bopp-roll';

    $isActiveBopp = $isActiveBoppConsolidated || $isActiveBoppRoll;
    $isActiveMaterialisticStock = $isActiveBopp;
    $isActiveStock = $isActiveMaterialisticStock;
@endphp

<div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg">
    <div class="sidebar-content">

        <!-- Sidebar header -->
        <div class="sidebar-section">
            <div class="sidebar-section-body d-flex justify-content-center">
                <h5 class="sidebar-resize-hide flex-grow-1 my-auto">{{ $sidebarTitle ?? 'Stocks' }}</h5>
            </div>
        </div>
        <!-- /sidebar header -->

        <!-- Sidebar navigation -->
        <div class="sidebar-section">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Section label -->
                <li class="nav-item-header pt-0">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Material Stock</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>

                <!-- Stocks dropdown -->
                <li class="nav-item nav-item-submenu {{ $isActiveStock ? 'nav-item-open' : '' }}">
                    <a href="#" class="nav-link {{ $isActiveStock ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 4h16v2H4zm0 6h16v2H4zm0 6h16v2H4z" />
                        </svg>
                        <span>Stocks</span>
                    </a>

                    <ul class="nav nav-group-sub" style="{{ $isActiveStock ? 'display: block;' : '' }}">

                        <!-- Materialistic Stock submenu -->
                        <li class="nav-item nav-item-submenu {{ $isActiveMaterialisticStock ? 'nav-item-open' : '' }}">
                            <a href="#" class="nav-link {{ $isActiveMaterialisticStock ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2 3h20v2H2zm0 6h20v2H2zm0 6h20v2H2z" />
                                </svg>
                                <span>Materialistic Stock</span>
                            </a>

                            <ul class="nav nav-group-sub" style="{{ $isActiveMaterialisticStock ? 'display: block;' : '' }}">

                                <!-- Bopp submenu -->
                                <li class="nav-item nav-item-submenu {{ $isActiveBopp ? 'nav-item-open' : '' }}">
                                    <a href="#" class="nav-link {{ $isActiveBopp ? 'active' : '' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm0 2v14h14V5H5zm2 3h10v2H7V8zm0 4h6v2H7v-2z" />
                                        </svg>
                                        <span>Bopp</span>
                                    </a>

                                    <ul class="nav nav-group-sub" style="{{ $isActiveBopp ? 'display: block;' : '' }}">
                                        <li class="nav-item">
                                            <a href="{{ route('admin.material-stock.bopp') }}" 
                                               class="nav-link {{ $isActiveBoppConsolidated ? 'active' : '' }}">
                                                Consolidated
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.material-stock.bopp-roll') }}" 
                                               class="nav-link {{ $isActiveBoppRoll ? 'active' : '' }}">
                                                Roll Form
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                            </ul>
                        </li>

                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>
