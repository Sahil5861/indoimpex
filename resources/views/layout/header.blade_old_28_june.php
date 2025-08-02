<style>
	:root {
		--bg-main: #fff;
		--primary-bg: #004d7f;
	}
	
	.navbar{
		background-color: var(--bg-main);
	}
	.text-success-2{
		color: var(--primary-bg) !important;
	}
	.fw-bold{
		font-weight: bold;
	}

	.btn-success-2{
		background-color: var(--primary-bg) !important;
		border: var(--primary-bg) !important;
		color: #fff;
	}

	.btn-outline-success-2{
		color: var(--primary-bg);
		outline:  var(--primary-bg) !important;
		border: 1px solid var(--primary-bg) !important;
	}

	.btn-outline-success-2:hover{
		color: #fff;
		background-color: var(--primary-bg) !important;
		border: var(--primary-bg) !important;
	}
	/* .page-header{
		background-color: var(--bg-main);
	}
	.card{
		background-color: var(--bg-main);
	} */
</style>

<style>
	a:hover{
		text-decoration: none;
	}
	.dropdown-toggle::after{
		border-top: none;
	}
</style>


<!-- Main navbar -->
	<div class="navbar navbar-expand-lg navbar-static px-lg-0">
		<div class="container-fluid container-boxed jusitfy-content-start">
			<div class="navbar-brand flex-1 flex-lg-0">
				<a href="index.html" class="d-inline-flex align-items-center">
					<img src="{{asset('assets/images/logo.webp')}}" style="height: 50px;" alt=""> 					
				</a>
			</div>

			<!-- Navigation -->	

				<?php
					$isActiveMaster = false;
					$routes = [
						'boppstock.items.view',
						'boppstock.categories.view',
						'non-wovenfabricstock.items.view',
						'non-wovenfabricstock.categories.view'
					];
					if (in_array(Route::currentRouteName(), $routes)) {
						$isActiveMaster = true;
					}																			
				?>
				<hr style="margin: 0;">
				<div class="navbar ">
					<div class="container-fluid container-boxed position-relative">
						<div class="flex-fill overflow-auto overflow-lg-visible scrollbar-hidden">
							<ul class="nav gap-1 flex-nowrap flex-lg-wrap">					
								<li class="nav-item">
									<a href="{{ route('dashboard') }}" class="navbar-nav-link {{ Route::currentRouteName() === 'dashboard' ? 'rounded active' : '' }}">
										<i class="ph-house me-2"></i>
										Home
									</a>
								</li>					
							
								<?php 
									$boppItems = \App\Models\PPItem::all()->count();
									$boppCategories = \App\Models\PPCategory::all()->count();
									$nonWovenItems = \App\Models\NonWovenItem::all()->count();
									$nonWovenCategories = \App\Models\NonWovenCategory::all()->count();
									$ppWovenItems = \App\Models\PPwovenItem::all()->count();
									$ppWovenCategory = \App\Models\PPwovenCategory::all()->count();
									$party = \App\Models\Party::all()->count();
									$jobname = \App\Models\JobNames::all()->count();
									$jobtype = \App\Models\JobTypes::all()->count();
								
								?>

								@if (hasAnyPermission('Master'))						
									<li class="nav-item dropdown">
										<a href="#" class="navbar-nav-link dropdown-toggle {{$isActiveMaster ? 'rounded active' : ''}}" data-bs-toggle="dropdown">
											<i class="ph-layout me-2"></i> Masters
										</a>
										<ul class="dropdown-menu">

											{{-- Bopp Stock --}}
												@if (hasPermission('Bopp Stock Items View', 'View') || hasPermission('Bopp Stock Categories View', 'View'))									
												<li class="dropdown-submenu">
													<a class="dropdown-item dropdown-toggle" href="#">Bopp Stock</a>
													<ul class="dropdown-menu">
														@if (hasPermission('Bopp Stock Items View', 'View'))
															<li><a class="dropdown-item" href="{{ route('boppstock.items.view') }}">Bopp Item List (<span class="text-success-2 fw-bold">{{$ppWovenItems}}</span>)</a></li>
														@endif

														@if (hasPermission('Bopp Stock Categories View', 'View'))
															<li><a class="dropdown-item" href="{{ route('bopp-stock.categories.view') }}">Bopp Category List (<span class="text-success-2 fw-bold">{{$ppWovenCategory}}</span>)</a></li>
														@endif
													</ul>
												</li>							
												@endif
															
											{{-- Non Woven --}}		
												@if (hasPermission('Non Woven Fabric Items View', 'View') || hasPermission('Non Woven Fabric Categories View', 'View'))														
												<li class="dropdown-submenu">
													<a class="dropdown-item dropdown-toggle" href="#">Non-Woven Fabric Stock </a>
													<ul class="dropdown-menu">	
														@if (hasPermission('Non Woven Fabric Items View', 'View'))
														<li><a class="dropdown-item" href="{{ route('non-wovenfabricstock.items.view') }}">Non-Woven Item List (<span class="text-success-2 fw-bold">{{$nonWovenItems}}</span>)</a></li>																						
														@endif
														@if (hasPermission('Non Woven Fabric Categories View', 'View'))
														<li><a class="dropdown-item" href="{{ route('non-wovenfabricstock.categories.view') }}">Non-Woven Category List (<span class="text-success-2 fw-bold">{{$nonWovenCategories}}</span>)</a></li>											
														@endif
													</ul>
												</li>							
												@endif

											{{-- PP Woven --}}								
												@if (hasPermission('PP Woven Fabric stock Items View', 'View') || hasPermission('PP Woven Fabric stock Categories View', 'View'))									
												<li class="dropdown-submenu">
													<a class="dropdown-item dropdown-toggle" href="#">PP-Woven Fabric Stock</a>
													<ul class="dropdown-menu">	
														@if (hasPermission('PP Woven Fabric stock Items View', 'View'))										
														<li><a class="dropdown-item" href="{{ route('ppwovenfabricstock.items.view') }}">PP Item List (<span class="text-success-2 fw-bold">{{$ppWovenItems}}</span>)</a></li>											
														@endif
														@if (hasPermission('PP Woven Fabric stock Categories View', 'View'))
														<li><a class="dropdown-item" href="{{ route('ppwovenfabricstock.categories.view') }}">PP Category List (<span class="text-success-2 fw-bold">{{$ppWovenCategory}}</span>)</a></li>											
														@endif
													</ul>
												</li>								
												@endif

											@if (hasPermission('Party View', 'View'))									
											<li>
												<a class="dropdown-item" href="{{ route('admin.party') }}">Party (<span class="text-success-2 fw-bold">{{$party}}</span>)</a>
											</li>
											@endif

											@if (hasPermission('Job Type View', 'View'))	
											<li>
												<a class="dropdown-item" href="{{ route('jobtypes.view') }}">Job Type (<span class="text-success-2 fw-bold">{{$jobtype}}</span>)</a>
											</li>
											@endif

											@if (hasPermission('Job Name View', 'View'))	
											<li>
												<a class="dropdown-item" href="{{ route('jobnames.view') }}">Job Names (<span class="text-success-2 fw-bold">{{$jobname}}</span>)</a>
											</li>
											@endif
										</ul>
									</li>
								@endif

								@if (hasPermission('Order Book View', 'View'))						
								<li class="nav-item">
									<a href="{{ route('orderbooks.items.view') }}" class="navbar-nav-link {{ Route::currentRouteName() === 'orderbooks.items.view' ? 'rounded active' : '' }}">
										<i class="ph-book me-2"></i>
										Order Book
									</a>
								</li>
								@endif

								{{-- job details --}}

								@if (hasPermission('Job Details View', 'View'))						
								<li class="nav-item nav-item-dropdown-lg dropdown">
									<a href="#" class="navbar-nav-link dropdown-toggle rounded" data-bs-toggle="dropdown">
										<i class="ph-layout me-2"></i>
										Job details
									</a>

									<?php
										$allJobesCount = \App\Models\JobDetails::where('approval_status', '1')->where('job_status', '1')->count();
										$pendingJobesCount = \App\Models\JobDetails::where('approval_status', '0')->where('job_status', '1')->count();
										$savedJobesCount = \App\Models\JobDetails::where('job_status', '0')->where('approval_status', '0')->count();
									?>

									<div class="dropdown-menu dropdown-menu-end">	
										@if (hasPermission('Job Details View', 'View'))								
										<a href="{{route('jobdetails.view', 'all')}}" class="dropdown-item rounded">All Job Details &nbsp;<span class="text-success">{{$allJobesCount}}</span></a>														
										<a href="{{route('jobdetails.view', 'pending')}}" class="dropdown-item rounded">Pending For Approval &nbsp;<span class="text-danger">{{$pendingJobesCount}}</span></a>														
										<a href="{{route('jobdetails.view', 'saved')}}" class="dropdown-item rounded">Saved &nbsp;<span class="text-warning">{{$savedJobesCount}}</span></a>														
										@endif																			
									</div>
								</li>
								@endif

								{{-- @endif --}}




								
								
								<li class="nav-item nav-item-dropdown-lg dropdown">
									<a href="#" class="navbar-nav-link dropdown-toggle rounded" data-bs-toggle="dropdown">
										<i class="ph-layout me-2"></i>
										Roles and Users
									</a>

									<div class="dropdown-menu dropdown-menu-end">													
										<a href="{{route('admin.role')}}" class="dropdown-item rounded">Roles </a>							
										{{-- <a href="{{route('admin.mainUsers')}}" class="dropdown-item rounded">Users </a>							 --}}
									</div>
								</li>

								<li class="nav-item nav-item-dropdown-lg dropdown">
									<a href="#" class="navbar-nav-link dropdown-toggle rounded" data-bs-toggle="dropdown">
										<i class="ph-layout me-2"></i>
										Roles and Users
									</a>

									<div class="dropdown-menu dropdown-menu-end">													
										<a href="{{route('admin.role')}}" class="dropdown-item rounded">Roles </a>							
										{{-- <a href="{{route('admin.mainUsers')}}" class="dropdown-item rounded">Users </a>							 --}}
									</div>
								</li>
								<li class="nav-item nav-item-dropdown-lg dropdown">
									<a href="#" class="navbar-nav-link dropdown-toggle rounded" data-bs-toggle="dropdown">
										<i class="ph-layout me-2"></i>
										Roles and Users
									</a>

									<div class="dropdown-menu dropdown-menu-end">													
										<a href="{{route('admin.role')}}" class="dropdown-item rounded">Roles </a>							
										{{-- <a href="{{route('admin.mainUsers')}}" class="dropdown-item rounded">Users </a>							 --}}
									</div>
								</li>								
							</ul>
						</div>		
					</div>
				</div>
			<!-- /navigation -->

			

			<ul class="nav order-3 ms-lg-2">			
				<li class="nav-item nav-item-dropdown-lg dropdown ms-lg-2">
					<a href="#" class="navbar-nav-link align-items-center rounded-pill p-1" data-bs-toggle="dropdown">
						<div class="status-indicator-container">
							<img src="{{asset('assets/images/avtar.webp')}}" class="w-32px h-32px rounded-pill" alt="">
							<span class="status-indicator bg-success"></span>
						</div>
						<span class="d-none d-lg-inline-block mx-lg-2 text-dark">{{ ucfirst(Auth::user()->name) }}</span>
					</a>

					<div class="dropdown-menu dropdown-menu-end">
						<a href="#" class="dropdown-item">
							<i class="ph-user-circle me-2"></i>
							My profile
						</a>						
						<a href="{{route('logout')}}" class="dropdown-item">
							<i class="ph-sign-out me-2"></i>
							Logout
						</a>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->
<!-- Navigation -->	

	<?php
		$isActiveMaster = false;
		$routes = [
			'boppstock.items.view',
			'boppstock.categories.view',
			'non-wovenfabricstock.items.view',
			'non-wovenfabricstock.categories.view'
		];
		if (in_array(Route::currentRouteName(), $routes)) {
			$isActiveMaster = true;
		}																			
	?>
	<hr style="margin: 0;">
	<div class="navbar navbar-dark px-lg-0" style="background-color: #555;">
		<div class="container-fluid container-boxed position-relative">
			<div class="flex-fill overflow-auto overflow-lg-visible scrollbar-hidden">
				<ul class="nav gap-1 flex-nowrap flex-lg-wrap">					
					<li class="nav-item">
						<a href="{{ route('dashboard') }}" class="navbar-nav-link {{ Route::currentRouteName() === 'dashboard' ? 'rounded active' : '' }}">
							<i class="ph-house me-2"></i>
							Home
						</a>
					</li>					
				
					{{-- <li class="nav-item">
						<a href="#" class="navbar-nav-link dropdown-toggle rounded" data-bs-toggle="dropdown">
							<i class="ph-layout me-2"></i>
							Page
						</a>

						<div class="dropdown-menu dropdown-mega-menu p-3">
							<div class="row">
								<div class="col-lg-4">
									<div class="fw-bold border-bottom pb-2 mb-2">Navbars</div>
									<div class="mb-3 mb-lg-0">
										<a href="layout_navbar_fixed.html" class="dropdown-item rounded">Fixed navbar</a>
										<a href="layout_navbar_hideable.html" class="dropdown-item rounded">Hideable navbar</a>
										<a href="layout_navbar_sticky.html" class="dropdown-item rounded">Sticky navbar</a>
										<a href="layout_fixed_footer.html" class="dropdown-item rounded">Fixed footer</a>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="fw-bold border-bottom pb-2 mb-2">Sidebars</div>
									<div class="mb-3 mb-lg-0">
										<a href="layout_2_sidebars_1_side.html" class="dropdown-item rounded">2 sidebars on 1 side</a>
										<a href="layout_2_sidebars_2_sides.html" class="dropdown-item rounded">2 sidebars on 2 sides</a>
										<a href="layout_3_sidebars.html" class="dropdown-item rounded">3 sidebars</a>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="fw-bold border-bottom pb-2 mb-2">Sections</div>
									<div class="mb-3 mb-lg-0">
										<a href="layout_no_header.html" class="dropdown-item rounded">No header</a>
										<a href="layout_no_footer.html" class="dropdown-item rounded">No footer</a>
										<a href="layout_boxed_page.html" class="dropdown-item rounded">Boxed page</a>
										<a href="layout_boxed_content.html" class="dropdown-item rounded">Boxed content</a>
									</div>
								</div>
							</div>
						</div>
					</li>

					<li class="nav-item">
						<a href="#" class="navbar-nav-link dropdown-toggle rounded" data-bs-toggle="dropdown">
							<i class="ph-columns me-2"></i>
							Sidebars
						</a>

						<div class="dropdown-menu dropdown-mega-menu p-3">
							<div class="row">
								<div class="col-lg-3">
									<div class="fw-bold border-bottom pb-2 mb-2">Main</div>
									<div class="mb-3 mb-lg-0">
										<a href="sidebar_default_resizable.html" class="dropdown-item rounded">Resizable</a>
										<a href="sidebar_default_resized.html" class="dropdown-item rounded">Resized</a>
										<a href="sidebar_default_hideable.html" class="dropdown-item rounded">Hideable</a>
										<a href="sidebar_default_hidden.html" class="dropdown-item rounded">Hidden</a>
										<a href="sidebar_default_stretched.html" class="dropdown-item rounded">Stretched</a>
										<a href="sidebar_default_color_dark.html" class="dropdown-item rounded">Dark color</a>
									</div>
								</div>
								<div class="col-lg-3">
									<div class="fw-bold border-bottom pb-2 mb-2">Secondary</div>
									<div class="mb-3 mb-lg-0">
										<a href="sidebar_secondary_hideable.html" class="dropdown-item rounded">Hideable</a>
										<a href="sidebar_secondary_hidden.html" class="dropdown-item rounded">Hidden</a>
										<a href="sidebar_secondary_stretched.html" class="dropdown-item rounded">Stretched</a>
										<a href="sidebar_secondary_color_dark.html" class="dropdown-item rounded">Dark color</a>
									</div>
								</div>
								<div class="col-lg-3">
									<div class="fw-bold border-bottom pb-2 mb-2">Right</div>
									<div class="mb-3 mb-lg-0">
										<a href="sidebar_right_hideable.html" class="dropdown-item rounded">Hideable</a>
										<a href="sidebar_right_hidden.html" class="dropdown-item rounded">Hidden</a>
										<a href="sidebar_right_stretched.html" class="dropdown-item rounded">Stretched</a>
										<a href="sidebar_right_color_dark.html" class="dropdown-item rounded">Dark color</a>
									</div>
								</div>
								<div class="col-lg-3">
									<div class="fw-bold border-bottom pb-2 mb-2">Other</div>
									<div class="mb-3 mb-lg-0">
										<a href="sidebar_components.html" class="dropdown-item rounded">Sidebar components</a>
									</div>
								</div>
							</div>
						</div>
					</li>

					<li class="nav-item">
						<a href="#" class="navbar-nav-link dropdown-toggle rounded" data-bs-toggle="dropdown">
							<i class="ph-rows me-2"></i>
							Navbars
						</a>

						<div class="dropdown-menu dropdown-mega-menu p-3">
							<div class="row">
								<div class="col-lg-3">
									<div class="fw-bold border-bottom pb-2 mb-2">Single</div>
									<div class="mb-3 mb-lg-0">
										<a href="navbar_single_top_static.html" class="dropdown-item rounded">Top static</a>
										<a href="navbar_single_top_fixed.html" class="dropdown-item rounded">Top fixed</a>
										<a href="navbar_single_bottom_static.html" class="dropdown-item rounded">Bottom static</a>
										<a href="navbar_single_bottom_fixed.html" class="dropdown-item rounded">Bottom fixed</a>
									</div>
								</div>
								<div class="col-lg-3">
									<div class="fw-bold border-bottom pb-2 mb-2">Multiple</div>
									<div class="mb-3 mb-lg-0">
										<a href="navbar_multiple_top_static.html" class="dropdown-item rounded">Top static</a>
										<a href="navbar_multiple_top_fixed.html" class="dropdown-item rounded">Top fixed</a>
										<a href="navbar_multiple_bottom_static.html" class="dropdown-item rounded">Bottom static</a>
										<a href="navbar_multiple_bottom_fixed.html" class="dropdown-item rounded">Bottom fixed</a>
										<a href="navbar_multiple_top_bottom_fixed.html" class="dropdown-item rounded">Top and bottom fixed</a>
										<a href="navbar_multiple_secondary_sticky.html" class="dropdown-item rounded">Secondary sticky</a>
									</div>
								</div>
								<div class="col-lg-3">
									<div class="fw-bold border-bottom pb-2 mb-2">Content</div>
									<div class="mb-3 mb-lg-0">
										<a href="navbar_component_single.html" class="dropdown-item rounded">Single</a>
										<a href="navbar_component_multiple.html" class="dropdown-item rounded">Multiple</a>
									</div>
								</div>
								<div class="col-lg-3">
									<div class="fw-bold border-bottom pb-2 mb-2">Other</div>
									<div class="mb-3 mb-lg-0">
										<a href="navbar_colors.html" class="dropdown-item rounded">Color options</a>
										<a href="navbar_sizes.html" class="dropdown-item rounded">Sizing options</a>
										<a href="navbar_components.html" class="dropdown-item rounded">Navbar components</a>
									</div>
								</div>
							</div>
						</div>
					</li>

					<li class="nav-item">
						<a href="#" class="navbar-nav-link dropdown-toggle rounded" data-bs-toggle="dropdown">
							<i class="ph-list me-2"></i>
							Navigation
						</a>

						<div class="dropdown-menu dropdown-mega-menu p-3">
							<div class="row">
								<div class="col-lg-6">
									<div class="fw-bold border-bottom pb-2 mb-2">Vertical</div>
									<div class="mb-3 mb-lg-0">
										<a href="navigation_vertical_styles.html" class="dropdown-item rounded">Navigation styles</a>
										<a href="navigation_vertical_collapsible.html" class="dropdown-item rounded">Collapsible menu</a>
										<a href="navigation_vertical_accordion.html" class="dropdown-item rounded">Accordion menu</a>
										<a href="navigation_vertical_bordered.html" class="dropdown-item rounded">Bordered navigation</a>
										<a href="navigation_vertical_right_icons.html" class="dropdown-item rounded">Right icons</a>
										<a href="navigation_vertical_badges.html" class="dropdown-item rounded">Badges</a>
										<a href="navigation_vertical_disabled.html" class="dropdown-item rounded">Disabled items</a>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="fw-bold border-bottom pb-2 mb-2">Horizontal</div>
									<div class="mb-3 mb-lg-0">
										<a href="navigation_horizontal_styles.html" class="dropdown-item rounded">Navigation styles</a>
										<a href="navigation_horizontal_elements.html" class="dropdown-item rounded">Navigation elements</a>
										<a href="navigation_horizontal_tabs.html" class="dropdown-item rounded">Tabbed navigation</a>
										<a href="navigation_horizontal_disabled.html" class="dropdown-item rounded">Disabled items</a>
										<a href="navigation_horizontal_mega.html" class="dropdown-item rounded">Mega menu</a>
									</div>
								</div>
							</div>
						</div>
					</li> --}}

					<?php 
						$boppItems = \App\Models\PPItem::all()->count();
						$boppCategories = \App\Models\PPCategory::all()->count();
						$nonWovenItems = \App\Models\NonWovenItem::all()->count();
						$nonWovenCategories = \App\Models\NonWovenCategory::all()->count();
						$ppWovenItems = \App\Models\PPwovenItem::all()->count();
						$ppWovenCategory = \App\Models\PPwovenCategory::all()->count();
						$party = \App\Models\Party::all()->count();
						$jobname = \App\Models\JobNames::all()->count();
						$jobtype = \App\Models\JobTypes::all()->count();
					
					?>

					@if (hasAnyPermission('Master'))						
						<li class="nav-item dropdown">
							<a href="#" class="navbar-nav-link dropdown-toggle {{$isActiveMaster ? 'rounded active' : ''}}" data-bs-toggle="dropdown">
								<i class="ph-layout me-2"></i> Masters
							</a>
							<ul class="dropdown-menu">

								{{-- Bopp Stock --}}
									@if (hasPermission('Bopp Stock Items View', 'View') || hasPermission('Bopp Stock Categories View', 'View'))									
									<li class="dropdown-submenu">
										<a class="dropdown-item dropdown-toggle" href="#">Bopp Stock</a>
										<ul class="dropdown-menu">
											@if (hasPermission('Bopp Stock Items View', 'View'))
												<li><a class="dropdown-item" href="{{ route('boppstock.items.view') }}">Bopp Item List (<span class="text-success-2 fw-bold">{{$ppWovenItems}}</span>)</a></li>
											@endif

											@if (hasPermission('Bopp Stock Categories View', 'View'))
												<li><a class="dropdown-item" href="{{ route('bopp-stock.categories.view') }}">Bopp Category List (<span class="text-success-2 fw-bold">{{$ppWovenCategory}}</span>)</a></li>
											@endif
										</ul>
									</li>							
									@endif
												
								{{-- Non Woven --}}		
									@if (hasPermission('Non Woven Fabric Items View', 'View') || hasPermission('Non Woven Fabric Categories View', 'View'))														
									<li class="dropdown-submenu">
										<a class="dropdown-item dropdown-toggle" href="#">Non-Woven Fabric Stock </a>
										<ul class="dropdown-menu">	
											@if (hasPermission('Non Woven Fabric Items View', 'View'))
											<li><a class="dropdown-item" href="{{ route('non-wovenfabricstock.items.view') }}">Non-Woven Item List (<span class="text-success-2 fw-bold">{{$nonWovenItems}}</span>)</a></li>																						
											@endif
											@if (hasPermission('Non Woven Fabric Categories View', 'View'))
											<li><a class="dropdown-item" href="{{ route('non-wovenfabricstock.categories.view') }}">Non-Woven Category List (<span class="text-success-2 fw-bold">{{$nonWovenCategories}}</span>)</a></li>											
											@endif
										</ul>
									</li>							
									@endif

								{{-- PP Woven --}}								
									@if (hasPermission('PP Woven Fabric stock Items View', 'View') || hasPermission('PP Woven Fabric stock Categories View', 'View'))									
									<li class="dropdown-submenu">
										<a class="dropdown-item dropdown-toggle" href="#">PP-Woven Fabric Stock</a>
										<ul class="dropdown-menu">	
											@if (hasPermission('PP Woven Fabric stock Items View', 'View'))										
											<li><a class="dropdown-item" href="{{ route('ppwovenfabricstock.items.view') }}">PP Item List (<span class="text-success-2 fw-bold">{{$ppWovenItems}}</span>)</a></li>											
											@endif
											@if (hasPermission('PP Woven Fabric stock Categories View', 'View'))
											<li><a class="dropdown-item" href="{{ route('ppwovenfabricstock.categories.view') }}">PP Category List (<span class="text-success-2 fw-bold">{{$ppWovenCategory}}</span>)</a></li>											
											@endif
										</ul>
									</li>								
									@endif

								@if (hasPermission('Party View', 'View'))									
								<li>
									<a class="dropdown-item" href="{{ route('admin.party') }}">Party (<span class="text-success-2 fw-bold">{{$party}}</span>)</a>
								</li>
								@endif

								@if (hasPermission('Job Type View', 'View'))	
								<li>
									<a class="dropdown-item" href="{{ route('jobtypes.view') }}">Job Type (<span class="text-success-2 fw-bold">{{$jobtype}}</span>)</a>
								</li>
								@endif

								@if (hasPermission('Job Name View', 'View'))	
								<li>
									<a class="dropdown-item" href="{{ route('jobnames.view') }}">Job Names (<span class="text-success-2 fw-bold">{{$jobname}}</span>)</a>
								</li>
								@endif
							</ul>
						</li>
					@endif

					@if (hasPermission('Order Book View', 'View'))						
					<li class="nav-item">
						<a href="{{ route('orderbooks.items.view') }}" class="navbar-nav-link {{ Route::currentRouteName() === 'orderbooks.items.view' ? 'rounded active' : '' }}">
							<i class="ph-book me-2"></i>
							Order Book
						</a>
					</li>
					@endif

					{{-- job details --}}

					@if (hasPermission('Job Details View', 'View'))						
					<li class="nav-item nav-item-dropdown-lg dropdown">
						<a href="#" class="navbar-nav-link dropdown-toggle rounded" data-bs-toggle="dropdown">
							<i class="ph-layout me-2"></i>
							Job details
						</a>

						<?php
							$allJobesCount = \App\Models\JobDetails::where('approval_status', '1')->where('job_status', '1')->count();
							$pendingJobesCount = \App\Models\JobDetails::where('approval_status', '0')->where('job_status', '1')->count();
							$savedJobesCount = \App\Models\JobDetails::where('job_status', '0')->where('approval_status', '0')->count();
						?>

						<div class="dropdown-menu dropdown-menu-end">	
							@if (hasPermission('Job Details View', 'View'))								
							<a href="{{route('jobdetails.view', 'all')}}" class="dropdown-item rounded">All Job Details &nbsp;<span class="text-success">{{$allJobesCount}}</span></a>														
							<a href="{{route('jobdetails.view', 'pending')}}" class="dropdown-item rounded">Pending For Approval &nbsp;<span class="text-danger">{{$pendingJobesCount}}</span></a>														
							<a href="{{route('jobdetails.view', 'saved')}}" class="dropdown-item rounded">Saved &nbsp;<span class="text-warning">{{$savedJobesCount}}</span></a>														
							@endif																			
						</div>
					</li>
					@endif

					{{-- @endif --}}




					
					
					<li class="nav-item nav-item-dropdown-lg dropdown">
						<a href="#" class="navbar-nav-link dropdown-toggle rounded" data-bs-toggle="dropdown">
							<i class="ph-layout me-2"></i>
							Roles and Users
						</a>

						<div class="dropdown-menu dropdown-menu-end">													
							<a href="{{route('admin.role')}}" class="dropdown-item rounded">Roles </a>							
							{{-- <a href="{{route('admin.mainUsers')}}" class="dropdown-item rounded">Users </a>							 --}}
						</div>
					</li>

				</ul>
			</div>

			{{-- <div class="fab-menu fab-menu-absolute fab-menu-top fab-menu-top-end d-none d-lg-block" data-fab-toggle="click" data-fab-state="closed">
					<button type="button" class="fab-menu-btn btn btn-primary rounded-pill">
						<div class="m-1">
							<i class="fab-icon-open ph-plus"></i>
							<i class="fab-icon-close ph-x"></i>
						</div>
					</button>

					<ul class="fab-menu-inner">
						<li>
							<div data-fab-label="Compose email">
								<a href="#" class="btn btn-light shadow rounded-pill btn-icon">
									<i class="ph-pencil m-1"></i>
								</a>
							</div>
						</li>
						<li>
							<div data-fab-label="Conversations">
								<a href="#" class="btn btn-light shadow rounded-pill btn-icon">
									<i class="ph-chats m-1"></i>
								</a>
								<span class="badge bg-dark position-absolute top-0 end-0 translate-middle-top rounded-pill mt-1 me-1">5</span>
							</div>
						</li>
						<li>
							<div data-fab-label="Chat with Jack">
								<a href="#" class="btn btn-link btn-icon status-indicator-container rounded-pill p-0 ms-1">
									<img src="../../../assets/images/demo/users/face1.jpg" class="img-fluid rounded-pill" alt="">
									<span class="status-indicator bg-danger"></span>
									<span class="badge bg-dark position-absolute top-0 end-0 translate-middle-top rounded-pill mt-1 me-1">2</span>
								</a>
							</div>
						</li>
					</ul>
			</div> --}}
		</div>
	</div>
<!-- /navigation -->

