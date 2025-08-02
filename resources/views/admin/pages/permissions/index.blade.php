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
                            Dashboard - <span class="fw-normal">Permissions List</span>
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
                        <h5 class="card-title">Permissions List</h5>
                        <div class="card-tools text-end"
                            style="display: flex; align-items:center; justify-content: space-between;">
                            <div class="btns">

                                
                                <a href="#" class="text-white btn btn-primary-2" data-toggle="modal" data-target="#users">Add Permissions</a>
                                {{-- <a href="#" class="text-white btn btn-primary" data-toggle="modal" data-target="#modules">Add Modules</a>   --}}
                                <button class="btn btn-danger" id="delete-selected">Delete Selected</button>
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

                        <div class="table-responsive">
                            <table id="role-table" class="table table-bordered text-center table-striped">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>ID</th>
                                        <th class="text-center">Actions</th>                                        
                                        <th>Permission Name</th>                                        
                                        <th>Route</th>                                                                                                                    
                                        <th>Feature</th>                                        
                                        <th>Created At</th>                                                                                
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
                <h4 class="modal-title">Add Permissions</h4>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.permission.save')}}" method="post">
                    @csrf

                    <input type="hidden" name="id" id="id">
                    <div class="form-body">
                        <div class="form-seperator-dashed"></div>
                        <div class="row">                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Permission Name</label>
                                    <input type="text" name="name" id="name" class="form-control" required placeholder="Permission Name">
                                </div>
                            </div>

                            @if (count($modules) > 0)                                
                                <div class="col md-4">
                                    <div class="form-group">
                                        <label for="parent_id">Select Parent</label>
                                        <select name="parent_id" id="parent_id" class="form-control">
                                            <option value="">--Select--</option>
                                            @foreach ($modules as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Action</label>
                                    {{-- <select name="action" id="action" class="form-control select2">                                         --}}
                                    <select name="action" id="action" class="form-control">                                        
                                        <option value="">Select Action</option>
                                        <option value="View">View</option>
                                        <option value="Save">Save</option>                                        
                                        <option value="Update">Update</option>                                        
                                        <option value="Delete">Delete</option>
                                        <option value="Assign">Assign</option>
                                        <option value="Approve">Approve</option>
                                        <option value="Show">Show</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Route</label>
                                    <input type="text" name="route" id="route" class="form-control" placeholder="Permission Route">
                                </div>
                            </div>


                        </div>
                        <div class="form-seperator-dashed"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-rounded text-left close-modal" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success-2 float-right text-right">Submit & Save</button>
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
        var RoleTable = $('#role-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.permissions') }}",
                data: function (d) {
                    d.status = $('#status').val();
                }
            },
            columns: [
                {
                    data: null,name: 'select',orderable: false,searchable: false,
                    render: function (data, type, row) {
                        return '<input type="checkbox" class="select-row" value="' + row.id + '">';
                    }
                },
                { data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },                
                { data: 'name', name: 'name' },                
                { data: 'route', name: 'route' },                                              
                { data: 'feature', name: 'feature' },                
                { data: 'created_at', name: 'created_at' }                
            ],

            order: [[1, 'asc']],
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
                                    url: "{{ route('admin.permission.deleteSelected') }}",
                                    method: 'DELETE',
                                    data: { selected_roles: selectedIds },
                                    success: function (response) {
                                        RoleTable.ajax.reload(); // Refresh the page
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
            RoleTable.ajax.reload();
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
                url: `{{ url('admin/role/update-status') }}/${roleId}`,
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
                        RoleTable.ajax.reload();
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
    function editPermision(element){
        var id = $(element).data('id');        
        $.ajax({
            url: `{{ route('getPermissionData') }}`,
            method: 'GET',
            data: {id:id},
            success: function (data) {
                console.log(data);
                let permission = data.permission;
                $('#users form')[0].reset();
                $('#id').val(permission.id);
                $('#name').val(permission.name);
                $('#parent_id').val(permission.parent_id);
                $('#action').val(permission.feature);
                $('#route').val(permission.route);

                
                $('#users').modal('show');

            },
        })
    
    }

    $('.close-modal').on('click', function (){        
        $('#users').modal('hide');
        $('#users form')[0].reset();
    })


    function updateSubmodule(elem){
        let id = $(elem).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('update-submodule') }}',
            data: {id: id},
            success: function (response){
                $('#sub_module').empty();
                $.each(response.data, function (index, value) {
                    console.log(value.name);
                    
                    $('#sub_module').append(
                        `<option value="${value.id}">${value.name}</option>`
                    )
                })
            }
        })
    }
</script>
@endsection