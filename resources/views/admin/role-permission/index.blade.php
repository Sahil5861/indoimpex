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
                            Dashboard - <span class="fw-normal">Roles List</span>
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
                        <h5 class="card-title">Roles List</h5>
                        <div class="card-tools text-end"
                            style="display: flex; align-items:center; justify-content: space-between;">
                            <div class="btns">
                                <a href="#" class="text-white btn btn-primary" data-toggle="modal" data-target="#features">Add Feature</a>
                                <a href="#" class="text-white btn btn-primary" data-toggle="modal" data-target="#module">Add Module</a>                                
                                <br><br>
                                {{-- <select name="status" id="status" class="form-control">
                                    <option value="">All</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select> --}}
                            </div>
                            <div class="dropdown">
                                {{-- <a href="#" class="text-body" data-bs-toggle="dropdown">
                                    <i class="ph-list"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                        data-target="#importModal">Import Roles</a>
                                    <a href="#" class="dropdown-item" id="export-roles">Export Roles</a>
                                </div> --}}                                
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('permissions.update') }}" method="POST">
                            @csrf
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Feature Name</th>
                                                <th>View</th>
                                                <th>Add</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($permissions as $per)
                                                <tr>
                                                    <input type="hidden" name="per_cat[]" value="{{ $per->id }}">
                                                    <th scope="row">{{ $per->name }}</th>
                                                    <td><input type="checkbox" name="view_{{ $per->id }}" value="1" {{ $per->enable_view ? 'checked' : '' }}></td>
                                                    <td><input type="checkbox" name="add_{{ $per->id }}" value="1" {{ $per->enable_add ? 'checked' : '' }}></td>
                                                    <td><input type="checkbox" name="edit_{{ $per->id }}" value="1" {{ $per->enable_edit ? 'checked' : '' }}></td>
                                                    <td><input type="checkbox" name="delete_{{ $per->id }}" value="1" {{ $per->enable_delete ? 'checked' : '' }}></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <button type="submit" class="btn btn-success float-right mt-4">Save</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="features" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="bopp" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Feature</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.feature.save')}}" method="post">
                    @csrf
                    <div class="form-body">
                        <div class="form-seperator-dashed"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Feature Name :</label>
                                    <input type="text" id="name" class="form-control" name="name" required placeholder="Enter Role Name">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Permissin Group :</label>
                                    <select name="prem_group_id" id="perm_roup_id" class="form-control">
                                        @foreach ($modules as $perm_group)
                                            <option value="{{$perm_group->id}}">{{$perm_group->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="form-seperator-dashed"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-rounded text-left" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success float-right text-right">Submit & Save</button>
                    </div>
                </form>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>    
</div>

<div id="module" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="bopp" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Module</h4>
                <button type="button" class="close close-edit-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.module.save')}}" method="post">
                    @csrf
                    <input type="hidden" id="roleid" name="id">
                    <div class="form-body">
                        <div class="form-seperator-dashed"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Module Name :</label>
                                    <input type="text" id="rolename" class="form-control" name="name" required placeholder="Enter Module Name">
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

<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import ROles</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="importForm" action="{{route('admin.role.import')}}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="csv_file">Select CSV File</label>
                        <input type="file" name="csv_file" class="form-control" required value="{{old('csv_file')}}">
                    </div>
                    <a class="btn btn-success csvSample" href="{{ route('sample-file-download-role') }}">Download
                        Sample</a>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button " class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" form="importForm" class="btn btn-primary">Import</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        var RoleTable = $('#role-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.role') }}",
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
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'action', name: 'action', orderable: false, searchable: false },                
                { data: 'role_name', name: 'role_name' },
                { data: 'created_at', name: 'created_at' },                
                { data: 'access', name: 'access', orderable: false, searchable: false },
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
                                    url: "{{ route('admin.role.deleteSelected') }}",
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
    function editRole(element){
        var roleId = $(element).data('id');
        var roleName = $(element).data('name');

        console.log(roleId, roleName);

        $('#editrole').modal('show');
        $('#editrole').find('#roleid').val(roleId);
        $('#editrole').find('#rolename').val(roleName);
    }

    $('.close-edit-modal').on('click', function (){        
        $('#editrole').modal('hide');
    })
</script>
@endsection