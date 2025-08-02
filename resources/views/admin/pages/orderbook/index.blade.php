@extends('layout.base')

@section('content')

<style>
    .selected_quantity {
        background-color: #2b7975;
        color: #fff !important;
    }

    .suggestions li.highlight {
        background-color: #f0f0f0;
    }  
</style>
<div class="page-content">
    {{-- @include('layouts.sidebar') --}}
    <div class="content-wrapper">
        <div class="content-inner">
            <div class="page-header page-header-light shadow">
                <div class="page-header-content d-lg-flex">
                    <div class="d-flex">
                        {{-- <h4 class="page-title mb-0">
                            Dashboard - <span class="fw-normal">OrderBook List</span>
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
                        <h5 class="card-title">OrderBook List</h5>
                        <div class="card-tools text-end"
                            style="display: flex; align-items:center; justify-content: space-between;">
                            <div class="btns">
                                @if(hasPermission('Order Book Save', 'Save'))
                                <a href="#" class="text-white btn btn-primary-2" data-toggle="modal" data-target="#order-books">Add Orders</a>
                                @endif
                                @if(hasPermission('Order Book Delete', 'Delete'))
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
                                        <th>Order.NO</th>                                        
                                        <th>Party Name</th>                                        
                                        <th>Job Name</th>                                        
                                        <th>Order Date</th>                                        
                                        <th>Quantity Pcs</th>
                                        <th>Quantity Kg</th>                                        
                                        <th>Delivery Date</th>                                                                                                                                                                                                     
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

<?php 
    $orderCount = \App\Models\OrderBook::latest()->first()?->id ?? 0;
    $formattedOrderNumber = str_pad($orderCount + 1, 5, '0', STR_PAD_LEFT);
?>

<div id="order-books" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="bopp" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Order Book</h4>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.orderbooks.save')}}" method="POST" id="orderForm">
                    @csrf

                    

                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h2 class="modal-title">Order Book</h2>
                            </div>                            
                            <div class="col-md-4">
                                <h4 class="float-right">
                                    Date :
                                    <span>
                                        <input type="text" class="form-control change_date" id="submit_date" name="submit_date" required value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" readonly>
                                    </span>
                                </h4>
                            </div>

                            {{-- Order Number --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Order Number :</label>
                                    @php
                                        use App\Models\OrderBook;
                                        $lastId = OrderBook::latest()->value('id') ?? 0;
                                        $orderUniqueNumber = str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
                                    @endphp
                                    <input type="text" class="form-control" name="order_unique_number" id="order_unique_number" value="{{ $orderUniqueNumber }}" required readonly>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="id" id="id">

                        <div class="form-seperator-dashed"></div>

                        <div class="row">                            

                            {{-- Job Code --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Code :</label>
                                    <input type="text" id="autocomplete" class="form-control" required placeholder="Enter Job Code" autocomplete="off" oninput="makeJobcodeList(this);" style="position: relative;">
                                    <div id="job-list" class="suggestions" style="position: absolute;width: 100%;top: 80%;background-color: #fff;z-index: 10; display:none; overflow:auto; max-height:290px;"></div>
                                </div>
                                <input type="hidden" name="order_job_code" id="job_code_id">
                            </div>

                            {{-- Party Name --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Party Name :</label>
                                    {{-- <input type="text" id="party_name" class="form-control" required placeholder="Enter Party Name" name="party_name" autocomplete="off" value="{{ old('party_name') }}" oninput="makePartyNameList(this)"  style="position: relative;"> --}}
                                    <input type="text" id="party_name" class="form-control" required placeholder="Party Name" name="party_name" autocomplete="off" value="{{ old('party_name') }}"  style="position: relative;" readonly>
                                    {{-- <div id="party-list"  style="position: absolute;width: 90%;top: 80%;background-color: #fff;z-index: 10; display:none; overflow:auto; max-height:400px;"></div>                                     --}}
                                </div>
                            </div>

                            {{-- Job Name --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Name :</label>
                                    <input type="text" id="job_name" class="form-control" required placeholder="Enter Job Name" name="job_name" value="{{ old('job_name') }}" readonly>
                                </div>
                            </div>

                            {{-- Quantity --}}
                            <div class="col-md-6">
                                <label>Quantity :</label>
                                <div class="input-group">
                                    <input type="text" name="quantity" autocomplete="off" required class="form-control" placeholder="Enter Weight" value="{{ old('quantity') }}">
                                </div>
                            </div>

                            {{-- Quantity Type --}}
                            <div class="col-md-6">
                                <label>Quantity Type</label>
                                <div class="input-group">
                                    <div class="input-group-prepend w-50">
                                        <button type="button" class="btn btn-rounded btn-outline-success-2 quantity_type w-100" value="pc">Pcs</button>
                                    </div>
                                    <div class="input-group-append w-50">
                                        <button type="button" class="btn btn-rounded btn-outline-success-2 quantity_type w-100" value="kg">Kg</button>
                                    </div>
                                </div>
                                <input type="hidden" class="quant" name="quantity_type" value="{{ old('quantity_type') }}">
                            </div>

                            {{-- Delivery Date --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Delivery By :</label>
                                    @php
                                        $defaultDeliveryDate = \Carbon\Carbon::now()->addDays(15)->format('Y-m-d');
                                    @endphp
                                    <input type="date" class="form-control deliver_date" name="deliver_by" value="{{ old('deliver_by', $defaultDeliveryDate) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-seperator-dashed"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-rounded btn-danger btn-outline mr-1 close-modal" data-dismiss="modal">
                            <i class="ti-trash"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-rounded btn-success-2 btn-outline" name="submit_order">
                            <i class="ti-save-alt"></i> Save
                        </button>
                    </div>
                </form>

            </div>

        </div>
        <!-- /.modal-content -->
    </div>    
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>    
    $(document).on("click", ".quantity_type", function(e) {
        var val = $(this).val();        
        $(".quantity_type").removeClass("btn-success-2");
        $(".quantity_type").addClass("btn-outline-success-2");
        $(this).addClass("btn-success-2");
        $(this).removeClass("btn-outline-success-2");
        $(".quant").val(val);
    });
</script>
<script>    
    $(document).ready(function () {
        var RoleTable = $('#role-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('orderbooks.items.view') }}",
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
                { data: 'order_number', name: 'order_number' },
                
                { data: 'party_name', name: 'party_name' },
                { data: 'job_name', name: 'job_name'},
                { data: 'submit_date', name: 'submit_date' },
                { data: 'quantity_pcs', name: 'quantity_pcs' },
                { data: 'quantity_kg', name: 'quantity_kg' },
                { data: 'deliver_by', name: 'deliver_by' },                                               
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],

            order: [[2, 'asc']],
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
                                    url: "{{ route('bopp-stock.categories.deletemulti') }}",
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

    let selectedPartyIndex = -1;
    $('#autocomplete').on('keydown', function (event){

        if (event.key === 'Backspace' || event.key === 'Delete') {
            return;
        }

        // alert(event.key);

        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
            let items = $('#job-list li');            

            if (items.length === 0) return;

            // Remove existing highlight
            items.removeClass('highlight');

            if (event.key === 'ArrowDown') {                
                selectedPartyIndex = (selectedPartyIndex + 1) % items.length;
                $('#autocomplete').val(items.eq(selectedPartyIndex).text());
            } else if (event.key === 'ArrowUp') {

                if (selectedPartyIndex == 0) {
                    $('#autocomplete').focus();
                    selectedPartyIndex = -1;
                    return;
                }
                selectedPartyIndex = (selectedPartyIndex - 1 + items.length) % items.length;
                $('#autocomplete').val(items.eq(selectedPartyIndex).text());
            }

            // Add highlight to the selected item
            items.eq(selectedPartyIndex).addClass('highlight');
            return;
        }

        // On Enter key
        if (event.key === 'Enter') {
            event.preventDefault();
            const items = $('#job-list li'); 

            jobSuggestions = [];


            jobSuggestions = Array.from(items).map(item => $(item).text().trim());
                    
            $('#job-list').hide();

            if (items.length > 0 && selectedPartyIndex >= 0) {
                $('#autocomplete').val(items.eq(selectedPartyIndex).text());
            } else if (jobSuggestions.length > 0) {
                const currentVal = $('#autocomplete').val().toLowerCase();
                const exactMatch = jobSuggestions.find(p => p.toLowerCase() === currentVal);

                if (!exactMatch) {
                    $('#autocomplete').val(jobSuggestions[0]);
                }
            }
            
            selectedPartyIndex = -1;    
            
            manageJobcode();
            return false;
        }
    })

    var table = $('#example5').DataTable({});
    $(".close_table").click(function() {
        $(this).closest("tr").hide();
    });
    $(".shownextrow").click(function() {
        $(this).closest("tr").next().show();
    });

    function makeJobcodeList(elem){
        var jobcode = $(elem).val();
        $.ajax({
            url: `{{ url('admin/jobcode/get-jobcode-list') }}/${jobcode}`,
            method: 'GET',
            success: function(data) {
                var html = '';
                $('#job-list').empty();
                console.log(data);
                
                if (data.jobcode) {                    
                    $.each(data.jobcode, function(key, value) {                    
                        html += `<li style="padding:10px; cursor:pointer; list-style:none;" onclick="manageJobcode()">${value}</li>`
                    })
                    $('#job-list').append(html).show();
                }
                else{
                    $('#job-list').empty().hide();
                }
            }
        })
    }

    // function makePartyNameList(elem){
    //     var partyname = $(elem).val();
    //     $.ajax({
    //         url: `{{ url('admin/party/get-partyname-list') }}/${partyname}`,
    //         method: 'GET',
    //         success: function(data) {
    //             var html = '';
    //             $('#party-list').empty();
    //             console.log(data);
                
    //             if (data.parties) {                    
    //                 $.each(data.parties, function(key, value) {                    
    //                     html += `<li style="padding:10px; cursor:pointer; list-style:none; border:1px solid #888;" onclick="party_name.value = this.innerText; $('#party-list').hide();">${value}</li>`
    //                 })
    //                 $('#party-list').append(html).show();
    //             }
    //             else{
    //                 $('#party-list').empty().hide();
    //             }
    //         }
    //     })
    // }

    function manageJobcode(){
        var jobcode = $('#autocomplete').val();        
        $.ajax({
            url: `{{ route('get-jobcode') }}`,
            method: 'GET',
            data :{
                jobcode : jobcode,
            },
            success: function(data) {                
                $('#autocomplete').val(jobcode);
                $('#job_name').val(data.job_name.job_name)
                $("#job_code_id").val(data.job_name.id);
                $("#party_name").val(data.party.party_name);
            }
        })

        $('#job-list').hide();        
    }

    

</script>

<script>
    
// getOrderbookdata
    const form = $('#orderForm')[0]; // replace with your actual form ID            
    function editOrderBook(id){    
        $.ajax({
            url : '{{route("getOrderbookdata")}}',
            type: "GET",
            data: {
                id: id
            },
            success: function(response) {
                console.log(response);
                form.reset();
                const data = response.data;

                $('.quantity_type').removeClass('btn-success-2').addClass('btn-outline-success-2');
                // upload edita data
                $('#id').val(data.orderbook.id);
                $('#submit_date').val(data.orderbook.submit_date);
                $('#autocomplete').val(data.job_code);
                $('#job_code_id').val(data.orderbook.order_job_code);
                $('#job_name').val(data.job_name);
                $('#party_name').val(data.party_name);
                $('input[name="quantity"]').val(data.orderbook.quantity);
                $('input[name="deliver_by"]').val(data.orderbook.deliver_by);
                // $('#order_unique_number').val(String(data.orderbook.order_unique_number).padStart(5, '0'));
                $('#order_unique_number').val(String(data.orderbook.order_unique_number.split('X')[1]));
                $('.deliver_date').val(data.orderbook.deliver_by.split(' ')[0]);

                // Quantity Type
                $('.quant').val(data.orderbook.quantity_type);
                $('.quantity_type').removeClass('btn-success-2').addClass('btn-outline-success-2'); // reset
                $('.quantity_type[value="' + data.orderbook.quantity_type + '"]').removeClass('btn-outline-success-2').addClass('btn-success-2');

                // upload edita data
                $('#order-books').modal('show');                
            }
        })
    }

    $('.close-modal').on('click', function(){
        form.reset();
        $('.quantity_type').removeClass('btn-success-2').addClass('btn-outline-success-2');
        $('#order-books').modal('hide');
    })
</script>
@endsection