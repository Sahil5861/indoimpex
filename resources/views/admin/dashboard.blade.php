@extends('layout.base')

@section('content')
<div class="page-content">

    <!-- Main sidebar -->
    {{-- @include('layout.sidebar') --}}
    <!-- /main sidebar -->

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Inner content -->
        <div class="content-inner">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <!-- Page header -->
            {{-- <div class="page-header page-header-light shadow">
                <div class="page-header-content d-lg-flex">
                    <div class="d-flex">
                        <h4 class="page-title mb-0">
                            Home - <span class="fw-normal">Dashboard</span>
                        </h4>

                        <a href="#page_header"
                            class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto"
                            data-bs-toggle="collapse">
                            <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                        </a>
                    </div>
                </div>
            </div> --}}
            <!-- /page header -->
            <!-- Content area -->
            <div class="content container">
                @if (hasPermission('Job Details View', 'View') || hasAnyPermission('Master') || hasPermission('Roles View', 'View') || hasPermission('Users View', 'View'))                                
                <div class="card">
                    <div class="card-body">
                        <div class="row">                            
                            <div class="col-lg-12 col-sm-12">                                
                                <div class="">
                                    @if (hasPermission('Job Details All View', 'View') || hasPermission('Job Details Pending View', 'View') || hasPermission('Job Details Saved View', 'View'))	
                                    <div class="row justify-content-start g-4">
                                        <div class="col-12 mb-4">
                                            <h3 class="fw-semibold text-dark border-bottom pb-2">Job Details</h3>
                                        </div>

                                        <!-- All Jobs Card -->
                                        @if (hasPermission('Job Details All View', 'View'))                                            
                                        <div class="col-lg-4 col-md-6">
                                            <a href="{{ route('jobdetails.view.all') }}" class="text-decoration-none">
                                                <div class="card border-0 shadow-lg rounded-4 bg-white hover-shadow p-4 transition" style="min-height: 150px;">
                                                    <div class="card-body d-flex align-items-center">
                                                        <div class="me-4 text-primary">
                                                            <!-- Clipboard SVG Icon -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M9 2a1 1 0 0 0-1 1v1H6a2 2 0 0 0-2 2v14c0 1.103.897 2 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2V3a1 1 0 0 0-1-1H9zm0 2h6v1H9V4zm-3 3h12v14H6V7zm2 2v2h8V9H8zm0 4v2h8v-2H8z"/>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <h5 class="text-muted mb-1">All Jobs</h5>
                                                            <h3 class="fw-bold text-dark">
                                                                {{ \App\Models\JobDetails::where('job_status', '1')->where('approval_status', '1')->count() }}
                                                            </h3>
                                                            <small class="text-muted">Approved and Active Jobs</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        @endif

                                        
                                        @if (hasPermission('Job Details Pending View', 'View'))                                            
                                        <!-- Pending Jobs Card -->
                                        <div class="col-lg-4 col-md-6">
                                            <a href="{{ route('jobdetails.view.pending') }}" class="text-decoration-none">
                                                <div class="card border-0 shadow-lg rounded-4 bg-white hover-shadow p-4 transition" style="min-height: 150px;">
                                                    <div class="card-body d-flex align-items-center">
                                                        <div class="me-4 text-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M12 1a11 11 0 1 0 11 11A11.012 11.012 0 0 0 12 1zm0 20a9 9 0 1 1 9-9 9.01 9.01 0 0 1-9 9zm1-13h-2v6h6v-2h-4z"/>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <h5 class="text-muted mb-1">Pending Jobs</h5>
                                                            <h3 class="fw-bold text-dark">
                                                                {{ \App\Models\JobDetails::where('job_status', '1')->where('approval_status', '0')->count() }}
                                                            </h3>
                                                            <small class="text-muted">Waiting for approval</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        @endif

                                        @if (hasPermission('Job Details Saved View', 'View'))                                            
                                        <!-- Saved Jobs Card -->
                                        <div class="col-lg-4 col-md-6">
                                            <a href="{{ route('jobdetails.view.saved') }}" class="text-decoration-none">
                                                <div class="card border-0 shadow-lg rounded-4 bg-white hover-shadow p-4 transition" style="min-height: 150px;">
                                                    <div class="card-body d-flex align-items-center">
                                                        <div class="me-4 text-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M6 2a2 2 0 0 0-2 2v18l8-5.333L20 22V4a2 2 0 0 0-2-2H6z"/>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <h5 class="text-muted mb-1">Saved Jobs</h5>
                                                            <h3 class="fw-bold text-dark">
                                                                @if (Auth::user()->role_id == 1)                                                                    
                                                                {{ \App\Models\JobDetails::where('job_status', '0')->where('approval_status', '0')->count() }}
                                                                @else
                                                                {{ \App\Models\JobDetails::where('job_status', '0')->where('approval_status', '0')->where('saved_by', Auth::User()->id)->count() }}
                                                                @endif
                                                            </h3>
                                                            <small class="text-muted">Drafts or Unsubmitted Jobs</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                    @endif

                                    @if (hasAnyPermission('Master'))						
                                    <div class="row justify-content-start g-4">
                                        <div class="col-12 mb-4">
                                            <h3 class="fw-semibold text-dark border-bottom pb-2">Masters</h3>
                                        </div>

                                        <!-- Pending Jobs Card -->
                                        @if (hasPermission('Job Type View', 'View'))
                                        <div class="col-lg-4 col-md-6">
                                            <a href="{{ route('jobtypes.view') }}" class="text-decoration-none">
                                                <div class="card border-0 shadow-lg rounded-4 bg-white hover-shadow p-4 transition" style="min-height: 150px;">
                                                    <div class="card-body d-flex align-items-center">
                                                        <div class="me-4 text-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M12 2 1 7l11 5 9-4.09V15h2V7L12 2zM1 17v2l11 5 11-5v-2l-11 5-11-5z"/>
                                                            </svg>

                                                        </div>
                                                        <div>
                                                            <h5 class="text-muted mb-1">All Job Types</h5>
                                                            <h3 class="fw-bold text-dark">
                                                                {{ \App\Models\JobTypes::all()->count() }}
                                                            </h3>
                                                            <small class="text-muted">All Active Job Types</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        @endif

                                        <!-- All Jobs Card -->
                                        {{-- @if (hasPermission('Job Name View', 'View'))	
                                        <div class="col-lg-4 col-md-6">
                                            <a href="{{ route('jobnames.view') }}" class="text-decoration-none">
                                                <div class="card border-0 shadow-lg rounded-4 bg-white hover-shadow p-4 transition" style="min-height: 150px;">
                                                    <div class="card-body d-flex align-items-center">
                                                        <div class="me-4 text-primary">
                                                            <!-- Clipboard SVG Icon -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M10 4H4a2 2 0 0 0-2 2v2h8V4zm0 4H2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8h-8l-2-2zm6.293 4.293 1.414 1.414L13 18.414l-3.707-3.707 1.414-1.414L13 15.586l3.293-3.293z"/>
                                                            </svg>

                                                        </div>
                                                        <div>
                                                            <h5 class="text-muted mb-1">All Job Names</h5>
                                                            <h3 class="fw-bold text-dark">
                                                                {{ \App\Models\JobNames::where('status', '1')->count() }}
                                                            </h3>
                                                            <small class="text-muted">Active Job Names</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        @endif --}}                                        

                                        @if (hasPermission('Party View', 'View'))	
                                        <div class="col-lg-4 col-md-6">
                                            <a href="{{ route('admin.party') }}" class="text-decoration-none">
                                                <div class="card border-0 shadow-lg rounded-4 bg-white hover-shadow p-4 transition" style="min-height: 150px;">
                                                    <div class="card-body d-flex align-items-center">
                                                        <div class="me-4 text-primary">
                                                            <!-- Clipboard SVG Icon -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M2 4h20a1 1 0 0 1 1 1v2H1V5a1 1 0 0 1 1-1zm0 4h22v11a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V8zm6 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm0 6c-1.33 0-4 0.67-4 2v1h8v-1c0-1.33-2.67-2-4-2zm8-5h4v2h-4v-2zm0 3h4v2h-4v-2z"/>
                                                            </svg>

                                                        </div>
                                                        <div>
                                                            <h5 class="text-muted mb-1">All Parties</h5>
                                                            <h3 class="fw-bold text-dark">
                                                                {{ \App\Models\Party::all()->count() }}
                                                            </h3>
                                                            <small class="text-muted">Active Parties</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                    @endif

                                    @if (hasPermission('Roles View', 'View') || hasPermission('Users View', 'View'))                                        
                                    <div class="row justify-content-start g-4">
                                        <div class="col-12 mb-4">
                                            <h3 class="fw-semibold text-dark border-bottom pb-2">Roles and Users</h3>
                                        </div>

                                        <!-- All Jobs Card -->
                                        @if (hasPermission('Roles View', 'View'))
                                        <div class="col-lg-4 col-md-6">
                                            <a href="{{ route('admin.role') }}" class="text-decoration-none">
                                                <div class="card border-0 shadow-lg rounded-4 bg-white hover-shadow p-4 transition" style="min-height: 150px;">
                                                    <div class="card-body d-flex align-items-center">
                                                        <div class="me-4 text-primary">
                                                            <!-- Clipboard SVG Icon -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M12 1l9 4v6c0 5.25-3.94 10.74-9 12-5.06-1.26-9-6.75-9-12V5l9-4zm0 2.18L5 6.09v4.91c0 4.5 3.36 9.14 7 10.36 3.64-1.22 7-5.86 7-10.36V6.09l-7-2.91zM11 11V7h2v4h3l-4 4-4-4h3z"/>
                                                            </svg>

                                                        </div>
                                                        <div>
                                                            <h5 class="text-muted mb-1">All Roles</h5>
                                                            <h3 class="fw-bold text-dark">
                                                                {{ \App\Models\Role::all()->count() }}
                                                            </h3>
                                                            <small class="text-muted">All Active Roles</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        @endif

                                        <!-- Pending Jobs Card -->
                                        @if (hasPermission('Users View', 'View'))	
                                        <div class="col-lg-4 col-md-6">
                                            <a href="{{ route('admin.users') }}" class="text-decoration-none">
                                                <div class="card border-0 shadow-lg rounded-4 bg-white hover-shadow p-4 transition" style="min-height: 150px;">
                                                    <div class="card-body d-flex align-items-center">
                                                        <div class="me-4 text-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M16 11c1.657 0 3-1.791 3-4s-1.343-4-3-4-3 1.791-3 4 1.343 4 3 4zm-8 0c1.657 0 3-1.791 3-4s-1.343-4-3-4-3 1.791-3 4 1.343 4 3 4zm0 2c-2.67 0-8 1.337-8 4v3h8v-3c0-.882.388-1.684 1-2.236.612.552 1 1.354 1 2.236v3h8v-3c0-2.663-5.33-4-8-4zm8.5.5c-.337 0-.664.017-.982.05.664.878 1.057 1.935 1.057 3.116v2.334h5.425c.006-.111.018-.221.018-.334 0-2.17-3.582-5.166-5.518-5.166z"/>
                                                            </svg>

                                                        </div>
                                                        <div>
                                                            <h5 class="text-muted mb-1">Users</h5>
                                                            <h3 class="fw-bold text-dark">
                                                                {{ \App\Models\User::all()->count() }}
                                                            </h3>
                                                            <small class="text-muted">All Active Users</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>                                
                        </div>    
                    </div>    
                </div>
                @endif                
            </div>
            <!-- /content area -->

        </div>
        <!-- /inner content -->

    </div>
    <!-- /main content -->
</div>
<script>
    if (document.querySelector('#isAuthenticated').textContent === 'true') {
        window.location.href = "{{ route('dashboard') }}";
    }
</script>

{{-- In your template --}}
<span id="isAuthenticated" style="display: none;">{{ session('isAuthenticated') }}</span>


@endsection