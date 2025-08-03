@extends('layout.base')

@section('content')
<div class="page-content">
    @include('admin.pages.sidebar', ['sidebarTitle' => 'Masters'])
    <div class="content-wrapper">
        <div class="content-inner">
            <div class="page-header page-header-light shadow">
                <div class="page-header-content d-lg-flex">
                    <div class="d-flex">
                        {{-- <h4 class="page-title mb-0">
                            Dashboard - <span class="fw-normal">Parties List</span>
                        </h4>
                        <a href="#page_header"
                            class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto"
                            data-bs-toggle="collapse">
                            <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                        </a> --}}
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Parties List</h5>
                        <div class="card-tools text-end"
                            style="display: flex; align-items:center; justify-content: space-between;">
                            <div class="btns">
                                @if(hasPermission('Party Save', 'Save'))
                                <a href="#" class="text-white btn btn-primary-2" data-toggle="modal" data-target="#users">Add Parties</a>
                                @endif
                                @if(hasPermission('Party Delete', 'Delete'))
                                <button class="btn btn-danger" id="delete-selected">Delete Selected</button>
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
                                        <th>Party Name</th>                                        
                                        <th>Created At</th>                                        
                                        <th>Actions</th>   
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables will populate this -->
                                    @foreach ($data as $key => $item)
                                        <tr>
                                            <td><input type="checkbox" class="select-item" value="{{$item->id}}"></td>
                                            <td>{{$key + 1}}</td>
                                            <td>
                                                @if(hasPermission('Party Update', 'Update') || hasPermission('Party Delete', 'Delete'))
                                                <div class="dropdown">
                                                    <a href="#" class="text-body" data-bs-toggle="dropdown">
                                                        <i class="ph-list"></i>
                                                    </a>                                                    
                                                    <div class="dropdown-menu dropdown-menu-end">    
                                                               
                                                        @if (hasPermission('Party Update', 'Update'))                                                            
                                                        <a href="#" onclick="editUser(this)" data-id="{{$item->id}}" class="dropdown-item">
                                                            <i class="ph-pencil me-2"></i>Edit
                                                        </a>  
                                                        @endif
                                                        @if (hasPermission('Party Delete', 'Delete'))                                                            
                                                        <a href="{{route('admin.party.delete', $item->id)}}" data-id="{{$item->id}}" class="dropdown-item delete-button">
                                                            <i class="ph-trash me-2"></i>Delete
                                                        </a>
                                                        @endif                                  
                                                    </div>
                                                </div>
                                                @endif
                                            </td>
                                            <td>{{$item->party_name}}</td>
                                            <td>{{$item->created_at->format('d M Y')}}</td>
                                        </tr>
                                    @endforeach
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
                <h4 class="modal-title">Add Party</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">


                <form action="{{route('admin.party.save')}}" method="post">
                    @csrf                    
                    <div class="form-body">
                        <div class="form-seperator-dashed"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Party Name : <small class="text-danger">*</small></label>
                                    <input type="text" id="party_name" class="form-control" name="party_name" required placeholder="Enter First Name">
                                    @error('party_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact Number :</label>
                                    <input type="tel" id="contact_number" class="form-control" name="contact_number" placeholder="Enter Contact Name">
                                    @error('contact_number') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email :</label>
                                    <input type="email" id="email" class="form-control" name="email" placeholder="Enter Email">
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>  
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>GST Number :</label>
                                    <input type="text" id="gst" class="form-control" name="gst" placeholder="Enter GST">
                                    @error('gst') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description :</label>
                                    <textarea name="address" id="address" cols="10" rows="3" class="form-control" placeholder="Description"></textarea>
                                    @error('desc') <small class="text-danger">{{ $message }}</small> @enderror
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
                <h4 class="modal-title">Add Party</h4>
                <button type="button" class="close close-edit-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">


                <form action="{{route('admin.party.save')}}" method="post">
                    @csrf
                    <input type="hidden" name="id" id="partyid">
                    <div class="form-body">
                        <div class="form-seperator-dashed"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Party Name :</label>
                                    <input type="text" id="party_name1" class="form-control" name="party_name" required placeholder="Enter First Name">
                                    @error('party_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact Number :</label>
                                    <input type="tel" id="contact_number1" class="form-control" name="contact_number" required placeholder="Enter Contact Name">
                                    @error('contact_number') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email :</label>
                                    <input type="email" id="email1" class="form-control" name="email" required placeholder="Enter Email">
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>  
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>GST Number :</label>
                                    <input type="text" id="gst1" class="form-control" name="gst" required placeholder="Enter GST">
                                    @error('gst') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description :</label>
                                    <textarea name="address" id="address1" cols="10" rows="3" class="form-control" placeholder="Description"></textarea>
                                    @error('desc') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-seperator-dashed"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-rounded text-left close-edit-modal" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success-2 float-right text-right">Submit & Save</button>
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
                    <a class="btn btn-success-2 csvSample" href="{{ route('sample-file-download-role') }}">Download
                        Sample</a>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button " class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" form="importForm" class="btn btn-primary-2">Import</button>
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
            ajax: {
            
                data: function (d) {
                    d.status = $('#status').val();
                }
            },
            lengthMenu: [[100, 150, 200], [100, 150, 200]], // 👈 custom pagination lengths
            pageLength: 100, // 👈 default number of rows to show
            columns: [
                {
                    data: 'id',
                    name: 'select',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return '<input type="checkbox" class="select-row" value="' + row.id + '">';
                    }
                },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '100px', orderable: false, searchable: false },                
                { data: 'status', name: 'status', orderable: false, searchable: false },                
                { data: 'party_name', name: 'party_name', width: '300px' },                
                { data: 'created_at', name: 'created_at', width: '400px' },                              
                { data: 'action', name: 'action', orderable: false, searchable: false},                
            ],

            order: [[4, 'desc']],
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