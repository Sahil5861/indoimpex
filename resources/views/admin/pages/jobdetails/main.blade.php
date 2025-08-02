@extends('layout.base')

@section('content')
<div class="page-content">
    @include('admin.pages.jobdetails.sidebar', ['sidebarTitle' => 'Job Details'])
    <div class="content-wrapper">
        <div class="content-inner">            
            <div class="content">
                <div class="row">
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
            </div>
        </div>
    </div>
</div>

@endsection

