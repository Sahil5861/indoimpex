<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Indo Implex</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/css/lightgallery-bundle.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5/dist/fancybox/fancybox.css" /> --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" /> --}}

    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.css"/>

    <!-- Global stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/inter/inter.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/icons/phosphor/styles.min.css') }}">
    <link rel="stylesheet" href="{{ asset('full/assets/css/ltr/all.min.css') }}">

    


    <!-- /global stylesheets -->
<style>
. :hover{
    text-decoration: none;
    border: none;
  }
  div:where(.swal2-icon) .swal2-icon-content{
    font-size: 1em !important;
  }
  .img-preview {
      max-width: 150px;
      max-height: 150px;
      margin: 10px;
  }

    select[name="role-table_length"] {
        width: 200px !important;
    }

    table thead th{
        text-align: center !important;
    }

    .sidebar{
        width: 16%;
    }
  /* From Uiverse.io by abrahamcalsin */ 

</style>
</head>

<body>
    @if (Auth::check())        
        @include('layout.header')
    @endif

    <!-- Demo config -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="demo_config" style="">
        <div class="position-absolute top-50 end-100 visible">
            <button type="button" class="btn btn-primary btn-icon translate-middle-y rounded-end-0"
                data-bs-toggle="offcanvas" data-bs-target="#demo_config">
                <i class="ph-gear"></i>
            </button>
        </div>

        <div class="offcanvas-header border-bottom py-0">
            <h5 class="offcanvas-title py-3">Theme Color</h5>
            <button type="button" class="btn btn-light btn-sm btn-icon border-transparent rounded-pill"
                data-bs-dismiss="offcanvas">
                <i class="ph-x"></i>
            </button>
        </div>

        <div class="offcanvas-body">
            <div class="fw-semibold mb-2">Color mode</div>
            <div class="list-group mb-3">
                <label class="list-group-item list-group-item-action form-check border-width-1 rounded mb-2">
                    <div class="d-flex flex-fill my-1">
                        <div class="form-check-label d-flex me-2">
                            <i class="ph-sun ph-lg me-3"></i>
                            <div>
                                <span class="fw-bold">Light theme</span>
                                <div class="fs-sm text-muted">Set light theme or reset to default</div>
                            </div>
                        </div>
                        <input type="radio" class="form-check-input cursor-pointer ms-auto" name="main-theme" value="light">
                    </div>
                </label>

                <label class="list-group-item list-group-item-action form-check border-width-1 rounded mb-2">
                    <div class="d-flex flex-fill my-1">
                        <div class="form-check-label d-flex me-2">
                            <i class="ph-moon ph-lg me-3"></i>
                            <div>
                                <span class="fw-bold">Dark theme</span>
                                <div class="fs-sm text-muted">Switch to dark theme</div>
                            </div>
                        </div>
                        <input type="radio"  class="form-check-input cursor-pointer ms-auto" name="main-theme" value="dark" checked>
                    </div>
                </label>
            </div>
        </div>
    </div>

    @yield('content')
    

    @include('layout.footer')

    <!-- Core JS files -->
    <script src="{{ asset('assets/js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/demo/demo_configurator.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    
    

    <!-- JSZip and pdfmake (for Excel & PDF export) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>


    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script src="{{ asset('assets/js/vendor/visualization/d3/d3.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/visualization/d3/d3_tooltip.js') }}"></script>
    <script src="{{ asset('assets/demo/pages/dashboard.js') }}"></script>                    
    
    <!-- /theme JS files -->

    <!-- Additional JS files -->
    <script src="{{ asset('assets/js/vendor/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/uploaders/fileinput/plugins/sortable.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/uploaders/fileinput/fileinput.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/notifications/sweet_alert.min.js') }}"></script>
    <script src="{{ asset('full/assets/js/app.js') }}"></script>
    {{-- <script src="{{ asset('assets/demo/pages/table_elements.js') }}"></script> --}}

    {{-- datatables js starts --}}

    <script src="{{ asset('assets/demo/pages/datatables_basic.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>



    <!-- DataTables JS -->
    {{-- <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script> --}}

    <!-- ✅ Buttons Extension -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

    {{-- datatables js end --}}




    <!-- /additional JS files -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <!-- Include Moment.js -->
    {{-- <script src="{{ asset('assets/demo/pages/extra_sweetalert.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <script>        
        $(window).on('load', function() {
            // Hide the loader when the page is fully loaded
            $('#loader-wrapper').fadeOut('slow');
        });

        $(window).on('beforeunload', function() {
            // Show loader when the window is unloading (e.g., when navigating to another page)
            $('#loader-wrapper').fadeIn('fast');
        });        
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        $(document).on('change', '.checkbox', function() {
        // Check if any checkbox is checked
        if ($('.checkbox:checked').length > 0) {
            $('#multidelete-btn').show();  // show delete button
        } else {
            $('#multidelete-btn').hide();  // hide delete button
        }
    });
    </script>


    <script>
        $(document).on('click', function (event){
            if (event.target.closest('.delete-button')) {
                event.preventDefault();

                const btn = event.target.closest('.delete-button');
                const url = btn.getAttribute('data-url');
                const jobId = btn.getAttribute('data-id');


                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to Delete this.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#006db5',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            }
        })

        function checkIsExist(elem){
            let job_id = $('#id').val();        
            let value = $(elem).val();
            $.ajax({
                type : 'GET',
                url : "{{route('checkjobName')}}",
                data : {
                    value : value,
                    job_id : job_id
                },
                success : function (response){    

                    if (response.status == true) {                                        
                        $('.job_error_message').html(response.message).show();
                        $('.submit-save-btn').prop('disabled', true);
                    }
                    else{
                        $('.job_error_message').hide();
                        $('.submit-save-btn').prop('disabled', false);
                    }
                }
            })
        }
    </script>
</body>

</html>