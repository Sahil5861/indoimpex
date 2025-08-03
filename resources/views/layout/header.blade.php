<style>
	:root {
		--bg-main: #fff;
		--primary-bg: #006db5;
		--success-bg : #004d7f ;
	}
	
	.navbar{
		background-color: var(--bg-main);
	}
	.text-success-2{
		color: var(--success-bg) !important;
	}
	.fw-bold{
		font-weight: bold;
	}

	.bg-primary-2{
		background-color: var(--success-bg) !important;
		color: #fff
	}

	.btn-success-2{
		background-color: var(--success-bg) !important;
		border: var(--success-bg) !important;
		color: #fff !important;
	}
	.btn-primary-2{
		background-color: var(--primary-bg) !important;
		border: var(--primary-bg) !important;
		color: #fff !important;
	}

	.btn-outline-success-2{
		color: #717885;
		outline:  #c7c9cd;
		border: 1px solid #c7c9cd;
	}

	.btn-outline-success-2:hover{
		color: #fff;
		background-color: var(--success-bg) !important;
		border: var(--success-bg) !important;
	}
	div:where(.swal2-icon).swal2-success [class^=swal2-success-line]{
		background-color: transparent;
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
				<a href="{{route('home')}}" class="d-inline-flex align-items-center">
					<img src="{{asset('assets/images/logo.webp')}}" style="height: 50px;" alt=""> 					
				</a>
			</div>

			<!-- Navigation -->	

				<?php
					$isActiveMaster = false;
					$isActiveStock = false;
					$routes = [
						'boppstock.items.view',
						'bopp-stock.categories.view',
						'non-wovenfabricstock.items.view',
						'non-wovenfabricstock.categories.view',
						'ppwovenfabricstock.categories.view',
						'ppwovenfabricstock.items.view',
						'jobtypes.view',						
						'admin.party'
					];

					$routes_stock = [
						'admin.material-stock.bopp',						
					];


					if (in_array(Route::currentRouteName(), $routes)) {
						$isActiveMaster = true;
					}	
					
					if (in_array(Route::currentRouteName(), $routes_stock)) {
						$isActiveStock = true;
					}	
					
				?>
				<hr style="margin: 0;">
				<div class="navbar ">
					<div class="container-fluid container-boxed position-relative">
						<div class="flex-fill overflow-auto overflow-lg-visible scrollbar-hidden">
							<?php 
									$boppItems = \App\Models\PPItem::all()->count();
									$boppCategories = \App\Models\PPCategory::all()->count();
									$nonWovenItems = \App\Models\NonWovenItem::all()->count();
									$nonWovenCategories = \App\Models\NonWovenCategory::all()->count();
									$ppWovenItems = \App\Models\PPWovenItem::all()->count();
									$ppWovenCategory = \App\Models\PPWovenCategory::all()->count();
									$party = \App\Models\Party::all()->count();
									$jobname = \App\Models\JobNames::all()->count();
									$jobtype = \App\Models\JobTypes::all()->count();								
								?>
							<ul class="nav gap-1 flex-nowrap flex-lg-wrap">					
								<li class="nav-item">
									<a href="{{ route('dashboard') }}" class="navbar-nav-link {{ Route::currentRouteName() === 'dashboard' ? 'rounded active' : '' }}">
										<i class="ph-house me-2"></i>
										Home
									</a>
								</li>
																												
								@if (hasPermission('Bopp Stock Items View', 'View') ||  hasPermission('Bopp Stock Categories View', 'View') || hasPermission('Non Woven Fabric Items View', 'View') || hasPermission('Non Woven Fabric Categories View', 'View') || hasPermission('PP Woven Fabric stock Items View', 'View') || hasPermission('PP Woven Fabric stock Categories View', 'View') || hasPermission('Party View', 'View') || hasPermission('Job Type View', 'View') || hasPermission('Job Name View', 'View'))								
									<li class="nav-item">
										<a href="{{route('admin.masters')}}" class="navbar-nav-link {{$isActiveMaster ? 'rounded active' : ''}}">
											<i class="ph-gear me-2"></i> Masters
										</a>
										{{-- <ul class="dropdown-menu">																					
												@if (hasPermission('Bopp Stock Items View', 'View') ||  hasPermission('Bopp Stock Categories View', 'View'))								
												<li class="dropdown-submenu ">
													<a class="dropdown-item dropdown-toggle" href="#">Bopp Stock</a>
													<ul class="dropdown-menu">
														@if (hasPermission('Bopp Stock Items View', 'View'))
															<li><a class="dropdown-item" href="{{ route('boppstock.items.view') }}">Bopp Item List (<span class="text-success-2 fw-bold">{{$boppItems}}</span>)</a></li>
														@endif

														@if (hasPermission('Bopp Stock Categories View', 'View'))
															<li><a class="dropdown-item" href="{{ route('bopp-stock.categories.view') }}">Bopp Category List (<span class="text-success-2 fw-bold">{{$boppCategories}}</span>)</a></li>
														@endif
													</ul>
												</li>							
												@endif
																											
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
										</ul> --}}
									</li>
								@endif

								<?php 
								
									$orderCount = \App\Models\OrderBook::all()->count();
								?>

								@if (hasPermission('Order Book View', 'View'))						
								<li class="nav-item">
									<a href="{{ route('orderbooks.items.view') }}" class="navbar-nav-link {{ Route::currentRouteName() === 'orderbooks.items.view' ? 'rounded active' : '' }}">
										<i class="ph-receipt me-2"></i>
										Order Book
										@if ($orderCount > 0)											
										(<span class="text-success-2 fw-bold">{{$orderCount}}</span>)
										@endif
									</span>
									</a>
								</li>
								@endif
								
								<li class="nav-item dropdown">
									<a href="#" class="navbar-nav-link dropdown-toggle {{ $isActiveStock ? 'rounded active' : '' }}" data-bs-toggle="dropdown">
										<i class="ph-gear me-2"></i> Stocks
									</a>
									<ul class="dropdown-menu">
										<li class="dropdown-submenu">
											<a class="dropdown-item dropdown-toggle" href="#">Materialistic Stock</a>
											<ul class="dropdown-menu">
												<li class="dropdown-submenu">
													<a class="dropdown-item dropdown-toggle" href="#">Bopp</a>
													<ul class="dropdown-menu">
														<li><a class="dropdown-item" href="{{ route('admin.material-stock.bopp') }}">Consolidated</a></li>
														<li><a class="dropdown-item" href="{{ route('admin.material-stock.bopp-roll') }}">Roll Form</a></li>
													</ul>
												</li>
											</ul>
										</li>
									</ul>
								</li>
						

								@if (hasPermission('Job Details All View', 'View') || hasPermission('Job Details Pending View', 'View') || hasPermission('Job Details Saved View', 'View'))								
								<li class="nav-item">
									<a href="{{ route('jobdetails.view') }}" class="navbar-nav-link {{ (Route::currentRouteName() === 'jobdetails.view.all' || Route::currentRouteName() === 'jobdetails.view.pending' || Route::currentRouteName() === 'jobdetails.view.saved' || Route::currentRouteName() === 'jobdetails.view') ? 'rounded active' : '' }}">
										<i class="ph-receipt me-2"></i>
										Job Detais										
									</span>
									</a>
								</li>
								@endif
																								



								{{-- @if (hasPermission('Job Name View', 'View'))	
								<li>
									<a href="{{ route('jobnames.view') }}" class="navbar-nav-link {{ Route::currentRouteName() === 'jobnames.view' ? 'rounded active' : '' }}">Job Names (<span class="text-success-2 fw-bold">{{$jobname}}</span>)</a>									
								</li>
								@endif --}}

								{{-- job details --}}

								{{-- @if (hasPermission('Job Details All View', 'View') || hasPermission('Job Details Pending View', 'View') || hasPermission('Job Details Saved View', 'View'))								
								<li class="nav-item nav-item-dropdown-lg dropdown">
									<a href="#" class="navbar-nav-link dropdown-toggle {{ Route::currentRouteName() === 'jobdetails.view' ? 'rounded active' : '' }}" data-bs-toggle="dropdown">
										<i class="ph-clipboard-text me-2"></i>
										Job details
									</a>

									<div class="dropdown-menu dropdown-menu-end">											
										@if (hasPermission('Job Details All View', 'View'))
										<a href="{{route('jobdetails.view.all')}}" class="dropdown-item rounded">All Job Details<span class="text-success-2">&nbsp;&nbsp;({{$allJobesCount}})</span></a>														
										@endif

										@if (hasPermission('Job Details Pending View', 'View'))
										<a href="{{route('jobdetails.view.pending')}}" class="dropdown-item rounded">Pending For Approval<span class="text-success-2">&nbsp;&nbsp;({{$pendingJobesCount}})</span></a>														
										@endif

										@if (hasPermission('Job Details Saved View', 'View'))
										<a href="{{route('jobdetails.view.saved')}}" class="dropdown-item rounded">Saved<span class="text-success-2">&nbsp;&nbsp;({{$savedJobesCount}})</span></a>																								
										@endif																			
									</div>
								</li>
								@endif --}}

								{{-- @endif --}}

								<?php 
								
									$role_user_routes = [
										'admin.users',
										'admin.role'
									];	
									$users = \App\Models\User::all()->count();								
								?>
								@if (hasPermission('Roles View', 'View') || hasPermission('Users View', 'View'))
								<li class="nav-item nav-item-dropdown-lg dropdown">
									<a href="#" class="navbar-nav-link dropdown-toggle {{ (Route::currentRouteName() === 'admin.users' || Route::currentRouteName() === 'admin.role') ? 'rounded active' : '' }}" data-bs-toggle="dropdown">
										<i class="ph ph-users-three me-2"></i>
										Roles and Users
									</a>

									<div class="dropdown-menu dropdown-menu-end">	
										@if (hasPermission('Roles View', 'View'))											
										<a href="{{route('admin.role')}}" class="dropdown-item {{Route::currentRouteName() === 'admin.role' ? 'active' : ''}}">Roles </a>							
										@endif												
										@if (hasPermission('Users View', 'View'))											
										<a href="{{route('admin.users')}}" class="dropdown-item {{Route::currentRouteName() === 'admin.users' ? 'active' : ''}}">Users (<span class="text-success-2 fw-bold">{{$users}}</span>)</a>							
										@endif
									</div>
								</li>	
								@endif	
								
								<li class="nav-item">
									<a href="{{ route('admin.activitylogs.view') }}" class="navbar-nav-link {{ Route::currentRouteName() === 'admin.activitylogs.view' ? 'rounded active' : '' }}">
										<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="1" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1 me-2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
										Activity Logs
									</a>
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
	<hr style="margin: 0;">	
<!-- /navigation -->

