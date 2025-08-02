@extends('layout.base')

@section('content')
<div class="page-content">

    <!-- Main sidebar -->
    {{-- @include('layouts.sidebar') --}}
    <!-- /main sidebar -->

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Inner content -->
        <div class="content-inner">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <!-- Page header -->
            <div class="page-header page-header-light shadow">
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
            </div>
            <!-- /page header -->
            <!-- Content area -->
            <div class="content">
                <h2>Home Page</h2>
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