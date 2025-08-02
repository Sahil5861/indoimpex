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
                            Dashboard - <span class="fw-normal">Activity Logs List</span>
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
                        <h5 class="card-title">Activity Logs List</h5>
                        {{-- <div class="card-tools text-end" style="display: flex; align-items:center; justify-content: space-between;">
                            <div class="btns">                                
                                <button class="btn btn-danger" id="delete-selected">Delete Selected</button>                                                            
                            </div>
                        </div> --}}

                        <div class="row">
                            <div class="col-md-2">
                                <button class="btn btn-danger" id="delete-selected">Delete Selected</button>                                                            
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="select_module" id="select_module" class="form-control selectpicker">
                                        <option value="">--Filter By Module--</option>
                                        @foreach ($modules as $item)
                                            <option value="{{$item}}">{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="select_user" id="select_user" class="form-control">
                                        <option value="">--Filter By User--</option>
                                        @foreach ($users as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
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
                                        <th>User</th>                                        
                                        <th>Module</th>                                                                             
                                        <th>Action</th>                                        
                                        <th>Comment</th>                                        
                                        <th>Date</th>                                        
                                        <th>Actions</th>   
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        var UserTable = $('#role-table').DataTable({
            processing: true,
            serverSide: true,
            lengthMenu: [[100, 150, 200], [100, 150, 200]], // 👈 custom pagination lengths
            pageLength: 100, // 👈 default number of rows to show

            ajax: {
                url: "{{route('admin.activitylogs.view')}}",
                data: function (d) {
                    let mymodule = $('#select_module').val();
                    let user_id = $('#select_user').val();
                    
                    if (mymodule !== '') {
                        d.mymodule = mymodule;
                    }

                    if (user_id !== '') {
                        d.user_id = user_id;
                    }
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
                { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '100px', orderable: false, searchable: false },                
                { data: 'user', name: 'user'},                
                { data: 'module', name: 'module', width: '300px' },                
                { data: 'action_name', name: 'action_name', width: '300px', orderable: false, searchable: false  },                
                { data: 'comment', name: 'comment', width: '300px' },                                
                { data: 'action_date', name: 'action_date', width: '300px' },                                
                { data: 'action', name: 'action', orderable: false, searchable: false},                
            ],

            order: [[6, 'desc']],
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
                                    url: "{{ route('admin.party.deletemulti') }}",
                                    method: 'DELETE',
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
                    var partyId = $(this).data('id');
                    var status = $(this).is(':checked') ? 1 : 0;
                    updateStatus(partyId, status);
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
                url: `{{ url('admin-party-update-status') }}/${roleId}`,
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

    $('#select_module').on('change', function() {        
        $('#role-table').DataTable().ajax.reload(null, false);
    });

    $('#select_user').on('change', function() {        
        $('#role-table').DataTable().ajax.reload(null, false);
    });


</script>

<script>
    function editUser(element){
        var id = $(element).data('id');        
        $.ajax({
            type : 'GET',
            url : "{{route('admin.party.getData')}}",
            data : {
                id : id
            },
            success: function(response){
                console.log(response);  
                $('#editparty').modal('show');

                $('#editparty').find('#partyid').val(response.id);
                $('#editparty').find('#party_name1').val(response.party_name); 
                $('#editparty').find('#contact_number1').val(response.party_number); 
                $('#editparty').find('#email1').val(response.party_email); 
                $('#editparty').find('#gst1').val(response.party_gst); 
                $('#editparty').find('#address1').val(response.party_address); 
                
                contact_number1
            }
        })
    }

    $('.close-edit-modal').on('click', function (){        
        $('#editparty').modal('hide');
    })

    $(document).on('click', function (event){

        if (event.target.closest('.delete-button')) {
            event.preventDefault();

            const btn = event.target.closest('.delete-button');
            const url = btn.getAttribute('data-url');
            const jobId = btn.getAttribute('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to Delete this Party.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    })

</script>
@endsection