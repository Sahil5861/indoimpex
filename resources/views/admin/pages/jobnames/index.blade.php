@extends('layout.base')

@section('content')
<div class="page-content">
    {{-- @include('layouts.sidebar') --}}
    <div class="content-wrapper">
        <div class="content-inner">
            <div class="page-header page-header-light shadow">
                <div class="page-header-content d-lg-flex">
                    <div class="d-flex">
                        <h4 class="page-title mb-0">
                            Dashboard - <span class="fw-normal">Job Names List</span>
                        </h4>
                        <a href="#page_header"
                            class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto"
                            data-bs-toggle="collapse">
                            <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Job Names List</h5>
                        <div class="card-tools text-end"
                            style="display: flex; align-items:center; justify-content: space-between;">
                            <div class="btns">
                                @if(hasPermission('Job Name Save', 'Save'))
                                <a href="#" class="text-white btn btn-primary-2" data-toggle="modal" data-target="#users">Add Job Names</a>
                                @endif
                                @if(hasPermission('Job Name Delete', 'Delete'))
                                {{-- <button class="btn btn-danger" id="delete-selected">Delete Selected</button> --}}
                                @endif
                                <br><br>
                                {{-- <select name="status" id="status" class="form-control">
                                    <option value="">All</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select> --}}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>There were some problems with your input:</strong>
                                <ul class="mb-0 mt-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="role-table" class="table table-bordered text-center table-striped">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>S.NO</th>
                                        <th>Status</th>
                                        <th>Job Name</th>                                        
                                        <th>Created At</th>
                                        {{-- @if(hasPermission('Job Name Update', 'Update') || hasPermission('Job Name Delete', 'Delete')) --}}
                                        <th>Action</th>
                                        {{-- @endif --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables will populate this -->                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="users" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="bopp" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Job Name</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">


                <form action="{{route('jobnames.save')}}" method="post">
                    @csrf                    
                    <div class="form-body">
                        <div class="form-seperator-dashed"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Name :</label>
                                    <input type="text" id="job_type" class="form-control" name="job_name" required placeholder="Job name">
                                    @error('job_type') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>  
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status :</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">--Select--</option>
                                        <option value="1">Active</option>
                                        <option value="0">In active</option>
                                    </select>
                                    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>                            
                        </div>
                        <div class="form-seperator-dashed"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-rounded text-left" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success-2 float-right text-right">Submit & Save</button>
                    </div>
                </form>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>    
</div>

<div id="editparty" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="bopp" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Job </h4>
                <button type="button" class="close close-edit-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">


                <form action="{{route('jobnames.save')}}" method="post">
                    @csrf
                    <input type="hidden" name="id" id="jobid">
                    <div class="form-body">
                        <div class="form-seperator-dashed"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Name :</label>
                                    <input type="text" id="job_type1" class="form-control" name="job_name" required placeholder="Job name">
                                    @error('job_type') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status :</label>
                                    <select name="status" id="status1" class="form-control">
                                        <option value="">--Select--</option>
                                        <option value="1">Active</option>
                                        <option value="0">In active</option>
                                    </select>
                                    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>                
                        </div>
                        <div class="form-seperator-dashed"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-rounded text-left close-edit-modal" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success float-right text-right">Submit & Save</button>
                    </div>
                </form>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>    
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        var UserTable = $('#role-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('jobnames.view') }}",
                data: function (d) {
                    d.status = $('#status').val();
                }
            },
            columns: [
                {
                    data: null,
                    name: 'select',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return '<input type="checkbox" class="select-row" value="' + row.id + '">';
                    }
                },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false  },                
                { data: 'status', name: 'status',orderable:false, searchable:false},
                { data: 'job_name', name: 'job_name' },                
                { data: 'created_at', name: 'created_at', width:'200px'},                          
                { data: 'action', name: 'action', orderable: false, searchable: false }                               
            ],

            order: [[5, 'desc']],
            drawCallback: function (settings) {
                $('#select-all').on('click', function () {
                    var isChecked = this.checked;
                    $('#role-table .select-row').each(function () {
                        $(this).prop('checked', isChecked);
                    });
                });

                $('#delete-selected').on('click', function () {
                    var selectedIds = $('.select-row:checked').map(function () {
                        return this.value;
                    }).get();

                    if (selectedIds.length > 0) {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('jobtypes.deletemulti') }}",
                                    method: 'POST',
                                    data: { selected_roles: selectedIds },
                                    success: function (response) {
                                        UserTable.ajax.reload(); // Refresh the page
                                        Swal.fire(
                                            'Deleted!',
                                            response.success,
                                            'success'
                                        );
                                    },
                                    error: function (xhr) {
                                        Swal.fire(
                                            'Error!',
                                            'Something went wrong.',
                                            'error'
                                        );
                                    }
                                });
                            }
                        })


                    }
                    else {
                        Swal.fire(
                            'Error!',
                            'Please select at least one role to delete.',
                            'error'
                        );
                    }
                })


                $('.status-toggle').on('click', function () {
                    var roleId = $(this).data('id');
                    var status = $(this).is(':checked') ? 1 : 0;
                    updateStatus(roleId, status);
                });
            }
        });

        $('#status').on('change', function () {
            UserTable.ajax.reload();
        });

        $(document).ready(function () {
            $('#export-roles').on('click', function () {
                var status = $('#status').val();
                var url = "{{ route('admin.role.export') }}";
                if (status) {
                    url += "?status=" + status;
                }
                window.location.href = url;
            });
        });


        function updateStatus(roleId, status) {
            $.ajax({
                url: `{{ url('jobnames-update-status') }}/${roleId}`,
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify({ status: status }),
                success: function (data) {
                    if (data.success) {
                        // console.log('Status Updated !!');
                        Swal.fire(
                            'Updated!',
                            'Status Updated',
                            'success'
                        );
                        // alert('Status Updated !!');

                        // location.reload(); // Refresh the page
                        UserTable.ajax.reload();
                    } else {
                        alert('Failed to update status.');
                    }

                },
                error: function (error) {
                    console.error('Error:', error);
                }
            });
        }
    });


</script>

<script>
    function editUser(element){
        var id = $(element).data('id');        
        var job_type = $(element).data('name');                      
        var status = $(element).data('status');                      

        $('#editparty').modal('show');

        $('#editparty').find('#jobid').val(id);
        $('#editparty').find('#job_type1').val(job_type);         
        $('#editparty').find('#status1').val(status);         
        
    }

    $('.close-edit-modal').on('click', function (){        
        $('#editparty').modal('hide');
    })
</script>
@endsection