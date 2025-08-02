@extends('layout.base')

@section('content')
<div class="page-content">
    {{-- @include('layouts.sidebar') --}}
    <div class="content-wrapper">
        <div class="content-inner">
            <div class="page-header page-header-light shadow">
                <div class="page-header-content d-lg-flex">
                    <div class="d-flex">
                        {{-- <h4 class="page-title mb-0">
                            Dashboard - <span class="fw-normal">PP Item List</span>
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
                        <h5 class="card-title">PP Item List</h5>
                        <div class="card-tools text-end"
                            style="display: flex; align-items:center; justify-content: space-between;">
                            <div class="btns">
                                @if(hasPermission('PP Woven Fabric stock Items Save', 'Save'))
                                <a href="#" class="text-white btn btn-primary-2" data-toggle="modal" data-target="#users">Add Item</a>
                                @endif
                                @if(hasPermission('PP Woven Fabric stock Items Delete', 'Delete'))
                                <button class="btn btn-danger" id="delete-selected">Delete Selected</button>                                
                                @endif
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
                                        <th>S.NO</th>                                        
                                        <th>Item Code</th>
                                        <th>PP Size</th>
                                        <th>PP Category Value</th>                                        
                                        <th>Micron </th>                                        
                                        <th>Created At</th>                                                                                
                                        <th class="text-center">Actions</th>                                                                                
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
                <h4 class="modal-title">Add Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.PPWovenItem.save')}}" method="post">
                    @csrf
                    <div class="form-body">
                        <div class="form-seperator-dashed"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Item Code :</label>
                                    <input type="text" id="item_code" class="form-control" name="item_code" required placeholder="Enter Item Code" onblur="this.value = this.value.toUpperCase();">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Item Size :</label>
                                    <input type="text" id="size" class="form-control" name="size" required placeholder="Enter Item Value">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Item Category :</label>
                                    <select name="category_value" id="category_value" class="form-control select2">
                                        @foreach ($categories as $item)
                                            <option value="{{$item->category_value}}">{{$item->category_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>GMS : </label>
                                    <input type="text" id="microns" class="form-control" name="gms" required placeholder="Enter Microns">
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

<div id="editrole" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="bopp" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Item</h4>
                <button type="button" class="close close-edit-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.PPWovenItem.save')}}" method="post">
                    @csrf
                    <input type="hidden" id="roleid" name="id">
                    <div class="form-body">
                        <div class="form-seperator-dashed"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Item Code :</label>
                                    <input type="text" id="item_code1" class="form-control" name="item_code" required placeholder="Enter Item Name" onblur="this.value = this.value.toUpperCase();">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Item Size :</label>
                                    <input type="text" id="pp_size1" class="form-control" name="size" required placeholder="Enter Item Value">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Item Category :</label>
                                    <select name="category_value" id="pp_category" class="form-control select2">
                                        @foreach ($categories as $item)
                                            <option value="{{$item->category_value}}">{{$item->category_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>GMS : </label>
                                    <input type="text" id="pp_gms" class="form-control" name="gms" required placeholder="Enter Microns">
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
        var RoleTable = $('#role-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('ppwovenfabricstock.items.view') }}",
                data: function (d) {
                    d.status = $('#status').val();
                }
            },
            lengthMenu: [[100, 150, 200], [100, 150, 200]], // 👈 custom pagination lengths
            pageLength: 100, // 👈 default number of rows to show
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
                { data: 'item_code', name: 'item_code' },
                { data: 'pp_size', name: 'pp_size' },
                { data: 'pp_category', name: 'pp_category', width:'200px' },                                
                { data: 'pp_gms', name: 'pp_gms' },                                
                { data: 'created_at', name: 'created_at' },                                
                { data: 'action', name: 'action', orderable: false, searchable: false }                              
            ],

            order: [[3, 'desc']],
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
                                    url: "{{ route('pp.woven.fabricstock.items.deletemulti')}}",
                                    method: 'POST',
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
        var id = $(element).data('id');
        var item_code = $(element).data('item_code');
        var pp_size = $(element).data('pp_size');
        var pp_category = $(element).data('pp_category');
        var pp_gms = $(element).data('pp_gms');        

        $('#editrole').modal('show');
        $('#editrole').find('#roleid').val(id);
        $('#editrole').find('#item_code1').val(item_code);
        $('#editrole').find('#pp_size1').val(pp_size);        
        $('#editrole').find('#pp_category').val(pp_category).trigger('change');
        $('#editrole').find('#pp_gms').val(pp_gms);
        
    }

    $('.close-edit-modal').on('click', function (){        
        $('#editrole').modal('hide');
    })
</script>
@endsection