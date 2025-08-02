@extends('layout.base')

@section('content')
<div class="page-content">    
    <div class="content-wrapper">
        <div class="content-inner">
            <div class="page-header page-header-light shadow">
                <div class="page-header-content d-lg-flex">
                    <div class="d-flex">
                        <h4 class="page-title mb-0">
                            Dashboard - <span class="fw-normal">Users</span>
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
                        <h5 class="card-title">Users</h5>
                        <div class="card-tools text-end"
                            style="display: flex; align-items:center; justify-content: space-between;">
                            <div class="btns">
                                @if (hasPermission('Users Save', 'Save'))                                    
                                <a href="{{ route('admin.user.create') }}" class="text-white btn btn-primary-2" data-toggle="modal" data-target="#users">Add Users</a>
                                @endif
                                @if (hasPermission('Users Delete', 'Delete'))                                    
                                <button class="btn btn-danger" id="delete-selected">Delete Selected</button>                                
                                @endif
                            </div>                            
                        </div>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li style="list-style: none;">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered text-center table-striped" id="users-table">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>S.NO</th>                                        
                                        <th>Username</th>
                                        <th>Role</th>                                                                             
                                        <th>Email</th>                                                                             
                                        <th>Phone no.</th>                                                                             
                                        <th>Status</th>                                                                             
                                        <th>Created On</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
                <h4 class="modal-title">Add Users</h4>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.users.save')}}" method="post">
                    @csrf

                    <input type="hidden" name="id" id="id">
                    <div class="form-body">
                        <div class="form-seperator-dashed"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>User Name :</label>
                                    <input type="text" id="name" class="form-control" name="name" required placeholder="Enter User Name" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email :</label>
                                    <input type="email" id="email" class="form-control" name="email" required placeholder="Enter Email" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="col-md-6" id="password-feild">
                                <div class="form-group">
                                    <label>Password :</label>
                                    <div class="input-group">
                                        <input type="password" id="password" class="form-control position-relative" name="password" required placeholder="Enter Password" maxlength="10" autocomplete="new-password">                                        
                                        <button type="button" style="position: absolute; background-color:transparent; width:20%; top:0; left:100%; height:100%; z-index:10; transform:translateX(-100%); cursor: pointer; border:none; border-left:1px solid #ddd;" onmouseover="password.type = 'text';" onmouseout="password.type = 'password'">
                                            <svg viewBox="0 0 24 24" width="20" height="20" stroke="#26a69a" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>    
                                        </button>                                        
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone :</label>
                                    <input type="tel" id="phone" class="form-control" name="phone"  placeholder="Enter Phone Number" oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="10">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select Role :</label>
                                    <select name="role_id" id="role_id" class="form-control">
                                        <option value="">--select--</option>
                                        @foreach ($roles as $role)
                                        <option value="{{$role->id}}">{{$role->role_name}}</option>
                                        @endforeach
                                    </select>
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

{{-- <div id="viewUser" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="bopp" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Users</h4>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.users.save')}}" method="post">
                    @csrf

                    <input type="hidden" name="id" id="id">
                    <div class="form-body">
                        <div class="form-seperator-dashed"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>User Name :</label>
                                    <input type="text" id="name" class="form-control" name="name" required placeholder="Enter User Name" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email :</label>
                                    <input type="email" id="email" class="form-control" name="email" required placeholder="Enter Email" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="col-md-6" id="password-feild">
                                <div class="form-group">
                                    <label>Password :</label>
                                    <input type="password" id="password" class="form-control" name="password" required placeholder="Enter Password" maxlength="10" autocomplete="new-password">
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone :</label>
                                    <input type="tel" id="phone" class="form-control" name="phone"  placeholder="Enter Phone Number" oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="10">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select Role :</label>
                                    <select name="role_id" id="role_id" class="form-control">
                                        <option value="">--select--</option>
                                        @foreach ($roles as $role)
                                        <option value="{{$role->id}}">{{$role->role_name}}</option>
                                        @endforeach
                                    </select>
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
</div> --}}


<div id="updatePassword" class="modal fade" tabindex="-1" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update User Password</h4>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form action="{{route('updatePassword')}}" method="post">
                    @csrf

                    <input type="hidden" name="user_id" id="user_id">
                    <div class="form-body">
                        <div class="form-seperator-dashed"></div>
                        <div class="row">                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Create New Password :</label>
                                    <input type="password" id="password1" class="form-control" name="password1" required placeholder="Enter User Name" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Confirm Password :</label>
                                    <input type="password" id="confirm_password" class="form-control" name="confirm_password" required placeholder="Enter Email" autocomplete="new-password">
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

<div class="modal fade" id="importModal" tabindex="-1" user="dialog" aria-labelledby="importModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" user="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Users</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="importForm" action="{{ route('admin.user.import') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="csv_file">Select CSV File</label>
                        <input type="file" name="csv_file" class="form-control" required>
                    </div>
                    <a class="btn btn-success csvSample" href="{{ route('sample-file-download-user') }}">Download
                    Sample</a>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" form="importForm" class="btn btn-primary-2">Import</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        var UserTable = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.user') }}",
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
                { data: 'name', name: 'name' },
                { data: 'role', name: 'role' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone', orderable: false, searchable: false  },                
                { data: 'status', name: 'status', orderable: false, searchable: false  },                
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],

            order: [[1, 'asc']],
            drawCallback: function (settings) {
                $('#select-all').on('click', function () {
                    var isChecked = this.checked;
                    $('#users-table .select-row').each(function () {
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
                                    url: "{{ route('admin.user.deleteSelected') }}",
                                    method: 'DELETE',
                                    data: { selected_users: selectedIds },
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
                            'Please select at least one user to delete.',
                            'error'
                        );
                    }
                })


                $('.status-toggle').on('click', function () {
                    var userId = $(this).data('id');
                    var status = $(this).is(':checked') ? 1 : 0;
                    updateStatus(userId, status);
                });
            }



        });

        $('#status').on('change', function () {
            UserTable.ajax.reload();
        });

        $(document).ready(function () {
            $('#export-users').on('click', function () {
                var status = $('#status').val();
                var url = "{{ route('admin.user.export') }}";
                if (status) {
                    url += "?status=" + status;
                }
                window.location.href = url;
            });
        });

        


        function updateStatus(userId, status) {
            $.ajax({
                url: `{{ url('admin/user/update-status') }}/${userId}`,
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

    function editUser(id){
        let userId = id;
        $.ajax({
            type: "GET",
            url: "{{ route('admin.user.edit') }}",
            data: {id: userId},
            success: function (response) {
                // $('#edit-user-modal').modal('show');
                console.log(response);
                if (response.status) {                    
                    let data = response.user;

                    $('#users form')[0].reset();
                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                    $('#role_id').val(data.role_id);
                    $('#password-feild').hide();
                    $('#password').prop('required', false); 
                    
                    $('#users').modal('show');
                }
                
            }
        })
    }

    function updatePassword(elem){
        let userId = $(elem).data('id');            
        $('#user_id').val(userId);
        $('#updatePassword').modal('show');
    }
    

    $(document).on('change', '.toggle-status', function() {
        let id = $(this).data('id');
        let status = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: "{{route('change-user-status')}}", // your update route
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                status: status
            },
            success: function(response) {
                // Optional: show alert or reload
                Swal.fire('Success', 'Status updated successfully', 'success');
                console.log('Status updated');
            },
            error: function(xhr) {
                alert('Error updating status');
            }
        });
    });

    $('.close-modal').on('click', function (){
        $('#users').modal('hide');
        $('#updatePassword').modal('hide');
        setTimeout(() => {            
            $('#password-feild').show(); 
            $('#password').prop('required', true);
            $('#users form')[0].reset();
        }, 500);
    })
</script>
@endsection