@extends('layout.base')

@section('content')
<div class="page-content">
    @include('admin.pages.sidebar', ['sidebarTitle' => 'Masters'])
    <div class="content-wrapper">
        <div class="content-inner">            
            <div class="content">
                @if (hasAnyPermission('Master'))	
                {{-- <div class="row justify-content-start g-4">
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-lg rounded-4 bg-white hover-shadow p-4 transition" style="min-heigt:150px;">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-4 text-primary">
                                    <svg viewBox="0 0 24 24" width="64" height="64" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                </div>                                    
                                <div>
                                    <h5 class="text-muted mb-1">Bopp Items</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-lg rounded-4 bg-white hover-shadow p-4 transition" style="min-heigt:150px;">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-4 text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 256 256">
                                        <circle cx="60" cy="60" r="16"/>
                                        <circle cx="128" cy="60" r="16"/>
                                        <circle cx="196" cy="60" r="16"/>
                                        <circle cx="60" cy="128" r="16"/>
                                        <circle cx="128" cy="128" r="16"/>
                                        <circle cx="196" cy="128" r="16"/>
                                        <circle cx="60" cy="196" r="16"/>
                                        <circle cx="128" cy="196" r="16"/>
                                        <circle cx="196" cy="196" r="16"/>
                                    </svg>

                                </div>                                
                                <div>
                                    <h5 class="text-muted mb-1">Bopp Categories</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  --}}
                
                {{-- <div class="row justify-content-start g-4">
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-lg rounded-4 bg-white hover-shadow p-4 transition" style="min-heigt:150px;">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-4 text-primary">
                                    <svg viewBox="0 0 24 24" width="64" height="64" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                </div>                                    
                                <div>
                                    <h5 class="text-muted mb-1">Non Woven Items</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-lg rounded-4 bg-white hover-shadow p-4 transition" style="min-heigt:150px;">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-4 text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 256 256">
                                        <circle cx="60" cy="60" r="16"/>
                                        <circle cx="128" cy="60" r="16"/>
                                        <circle cx="196" cy="60" r="16"/>
                                        <circle cx="60" cy="128" r="16"/>
                                        <circle cx="128" cy="128" r="16"/>
                                        <circle cx="196" cy="128" r="16"/>
                                        <circle cx="60" cy="196" r="16"/>
                                        <circle cx="128" cy="196" r="16"/>
                                        <circle cx="196" cy="196" r="16"/>
                                    </svg>

                                </div>                                
                                <div>
                                    <h5 class="text-muted mb-1">Non Woven Categories</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  --}}
                <div class="row justify-content-start g-4">                                        
                    <!-- Pending Jobs Card -->
                    @if (hasPermission('Job Type View', 'View'))
                    <div class="col-lg-6 col-md-6">
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

                    @if (hasPermission('Party View', 'View'))	
                    <div class="col-lg-6 col-md-6">
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
            </div>
        </div>
    </div>
</div>

@endsection

