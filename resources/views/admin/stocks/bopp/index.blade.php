@extends('layout.base')
<style>
    .suggestions li.highlight {
        background-color: #f0f0f0;
    }  
    
    .input-group-text{
        height: 86%;
    }


    .suggestions{
        position: absolute;
        top: 100%;
        z-index: 100;
        left: 0;
        width: 100%;        
        height: max-content;
        max-height: 350px;
        overflow: auto;
        background-color: #fff;
        border: 1px solid #888;
        list-style: none;
    }
    .suggestions li:hover{
        outline: 1px solid #888;
    }
</style>
@section('content')
<div class="page-content">
    @include('admin.stocks.bopp.sidebar', ['sidebarTitle' => 'BOPP | Roll Form'])
    {{-- @include('layouts.sidebar') --}}
    <div class="content-wrapper">
        <div class="content-inner">
            {{-- <div class="page-header page-header-light shadow">
                <div class="page-header-content d-lg-flex">
                    <div class="d-flex">
                        <h4 class="page-title mb-0">
                            Dashboard - <span class="fw-normal">Bopp</span>
                        </h4>
                        <a href="#page_header"
                            class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto"
                            data-bs-toggle="collapse">
                            <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                        </a>
                    </div>
                </div>
            </div> --}}

            <div class="content">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">BOPP | Consolidated</h5>
                        @include('admin.stocks.bopp.common_links')
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                                <button type="button" style="float: right;" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        {{-- <div class="table-responsive">
                            <table id="role-table" class="table table-bordered text-center table-striped">
                                <thead>
                                    <tr>                                          
                                        <th>S.NO</th>                                        
                                        <th>Size</th>
                                        <th>Party Name</th>
                                        <th>Category</th>
                                        <th>Micron</th>                                                                                
                                        <th>Balance</th>                                                                                
                                    </tr>
                                </thead>
                                <tbody>                                
                                </tbody>
                            </table>
                        </div> --}}

                        <!-- Nav tabs -->
                        {{-- <ul class="nav nav-tabs" id="boppTab" role="tablist" style="display: flex; justify-content:center;align-items:center;">
                        <li class="nav-item" role="presentation" style="flex: 1">
                            <a class="nav-link active" id="consolidated-tab" data-bs-toggle="tab" href="#consolidated" role="tab" aria-controls="consolidated" aria-selected="true">Consolidated View</a>
                        </li>
                        <li class="nav-item" role="presentation" style="flex: 1">
                            <a class="nav-link" id="rollform-tab" data-bs-toggle="tab" href="#rollform" role="tab" aria-controls="rollform" aria-selected="false">Roll Form View</a>
                        </li>
                        </ul> --}}
                        <div class="table-responsive">
                            <table id="role-table" class="table table-bordered text-center table-striped">
                                <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>Size</th>
                                    <th>Party Name</th>
                                    <th>Category</th>
                                    <th>Micron</th>
                                    <th>Balance</th>
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



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        var RoleTable = $('#role-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.material-stock.bopp-list') }}",
            lengthMenu: [[100, 150, 200], [100, 150, 200]], // 👈 custom pagination lengths
            pageLength: 100, // 👈 default number of rows to show
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'bopp_size', name: 'bopp_size' },
                { data: 'bopp_party_name', name: 'bopp_party_name' },
                { data: 'bopp_category', name: 'bopp_category' },
                { data: 'bopp_micron', name: 'bopp_micron' },
                { data: 'balance', name: 'balance' }
            ],
            order: [[3, 'asc']]
        });


        var RollTable = $('#roll-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.material-stock.bopproll-list') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'roll_number', name: 'roll_number' },
                { data: 'bopp_size', name: 'bopp_size' },
                { data: 'bopp_category', name: 'bopp_category' },
                { data: 'bopp_micron', name: 'bopp_micron' },
                { data: 'balance', name: 'balance' },
                { data: 'bopp_party_name', name: 'bopp_party_name' },
                { data: 'action', name: 'action' }
            ],
            order: [[3, 'asc']]
        });


        $('#status').on('change', function () {
            RoleTable.ajax.reload();
        });

        
        $(".bopp_item_code").on('input', function () {        
            updateBoppSuggestions(this);
            updateBoppcodes();
        });

        $(".bopp_item_code_issue").on('input', function () {        
            updateBoppIssueSuggestions(this);
            updateBoppIssuecodes();
        });

        let selectedBoppIndex = -1;
        let selectedBoppIssueIndex = -1;

        let boppSuggestions = [];
        let boppIssueSuggestions = [];

        $(".bopp_item_code").on('keydown', function (e) {
            const $input = $(this);
            const $suggestionBox = $input.nextAll('.bopp-item-suggestions').first();
            const items = $suggestionBox.find('li');

            // Arrow navigation
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();

                if (items.length === 0) return;

                // Remove existing highlights
                items.removeClass('highlight');

                if (e.key === 'ArrowDown') {
                    selectedBoppIndex = (selectedBoppIndex + 1) % items.length;
                } else if (e.key === 'ArrowUp') {
                    selectedBoppIndex = (selectedBoppIndex - 1 + items.length) % items.length;
                }

                const selectedText = items.eq(selectedBoppIndex).text().trim();
                $input.val(selectedText);

                items.eq(selectedBoppIndex).addClass('highlight');
                return;
            }

            // Enter key to select item
            if (e.key === 'Enter') {
                e.preventDefault();                                
                if (items.length > 0 && selectedBoppIndex >= 0) {
                    const selectedText = items.eq(selectedBoppIndex).text().trim();
                    $input.val(selectedText);
                } else if (boppSuggestions.length > 0) {
                    const currentVal = $input.val().toLowerCase();
                    const exactMatch = boppSuggestions.find(b => b.toLowerCase() === currentVal);
                    if (!exactMatch) {
                        $input.val(boppSuggestions[0]);
                    }
                }

                $('.bopp-item-suggestions').hide().html('');
                selectedBoppIndex = -1;
                updateBoppcodes();
                return false;
            }

            // Reset index for any other key
            selectedBoppIndex = -1;
        });

        $(".bopp_item_code_issue").on('keydown', function (e) {
            const $input = $(this);
            const $suggestionBox = $input.nextAll('.bopp-item-suggestions').first();
            const items = $suggestionBox.find('li');

            // Arrow navigation
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();

                if (items.length === 0) return;

                // Remove existing highlights
                items.removeClass('highlight');

                if (e.key === 'ArrowDown') {
                    selectedBoppIssueIndex = (selectedBoppIssueIndex + 1) % items.length;
                } else if (e.key === 'ArrowUp') {
                    selectedBoppIssueIndex = (selectedBoppIssueIndex - 1 + items.length) % items.length;
                }

                const selectedText = items.eq(selectedBoppIssueIndex).text().trim();
                $input.val(selectedText);

                items.eq(selectedBoppIssueIndex).addClass('highlight');
                return;
            }

            // Enter key to select item
            if (e.key === 'Enter') {
                e.preventDefault();                                
                if (items.length > 0 && selectedBoppIssueIndex >= 0) {
                    const selectedText = items.eq(selectedBoppIssueIndex).text().trim();
                    $input.val(selectedText);
                } else if (boppIssueSuggestions.length > 0) {
                    const currentVal = $input.val().toLowerCase();
                    const exactMatch = boppIssueSuggestions.find(b => b.toLowerCase() === currentVal);
                    if (!exactMatch) {
                        $input.val(boppIssueSuggestions[0]);
                    }
                }

                $('.bopp-item-suggestions').hide().html('');
                selectedBoppIssueIndex = -1;
                updateBoppIssuecodes();
                setTimeout(() => {                    
                    checkIssueBopp();
                }, 500);
                return false;
            }

            // Reset index for any other key
            selectedBoppIssueIndex = -1;
        });

        function updateBoppSuggestions(elem) {
            var item_code = $(elem).val();                                            

            $.ajax({
                type: "GET",
                url: "{{ route('bopp_item_code_suggestions') }}",
                data: { item_code: item_code },
                success: function(data) {
                    let html = '';
                    if (data.status == true && data.boppItems.length > 0) {                                                        
                        data.boppItems.forEach((item) => {
                            html += `<li onclick="selectBoppItem(this)" style="cursor:pointer; padding:10px;">${item.item_code}</li>`;
                        });
                        $('.bopp-item-suggestions').html(html).show();
                    } else {
                        $('.bopp-item-suggestions').html('').hide();
                    }
                }
            });
        }    
        
        function updateBoppIssueSuggestions(elem) {
            var item_code = $(elem).val();                                            

            $.ajax({
                type: "GET",
                url: "{{ route('bopp_item_code_suggestions') }}",
                data: { item_code: item_code },
                success: function(data) {
                    let html = '';
                    if (data.status == true && data.boppItems.length > 0) {                                                        
                        data.boppItems.forEach((item) => {
                            html += `<li onclick="selectBoppItemIssue(this)" style="cursor:pointer; padding:10px;">${item.item_code}</li>`;
                        });
                        $('.bopp-item-suggestions').html(html).show();
                    } else {
                        $('.bopp-item-suggestions').html('').hide();
                    }
                }
            });
        } 
        
        function checkIssueBopp(){
            let bopp_item_code_issue =$(".bopp_item_code_issue").val()
            $.ajax({
                type: "GET",
                url: "{{ route('check_issue_bopp') }}",
                data: { bopp_item_code_issue: bopp_item_code_issue },
                success: function(response) {
                    console.log(response); 
                    if (response.status == true) {
                        $('.add_issue_bopp').prop('disabled', false);
                        $('.roll-table-body').empty();
                        let html = ``;
                        let data = response.data;
                        let index = 0;
                        data.forEach((item)=>{
                            index +=1;
                            console.log(item);
                            html += `<tr>
                                        <td>${index}</td>
                                        <td>${item.roll_number}</td>
                                        <td>${item.weight}</td>
                                        <td><input type="checkbox" class="checkbox_roll" name="bopp_roll_id" id="checkbox_${index}" value="${item.id}"></td>
                                    </tr>`;                                                                                
                        })
                        $('.roll-table-body').html(html);
                        $('.roll-table').show();
                        $('.item-coed-issue-error').html('');
                    }                   
                    else{
                        $('.add_issue_bopp').prop('disabled', true);
                        $('.roll-table').hide();
                        $('.item-coed-issue-error').html(`${response.message}`);
                    }
                }
            });
        }

        $(document).on('change', '.checkbox_roll', function() {
            $('.checkbox_roll').not(this).prop('checked', false);
        });
                
    });

    

    function selectBoppItemIssue(li) {        
        $('.bopp-item-suggestions').html('').hide();
        $('#bopp_item_code_issue').val(li.innerText);        
        // $('#bopp_item_code').trigger('input'); // trigger to re-run AJAX                
        updateBoppIssuecodes();
    }

    function selectBoppItem(li) {        
        $('.bopp-item-suggestions').html('').hide();
        $('#bopp_item_code').val(li.innerText);        
        // $('#bopp_item_code').trigger('input'); // trigger to re-run AJAX                
        updateBoppcodes();
    }

    function updateBoppcodes(){
        let bopp_item_code = $('#bopp_item_code').val()
        $.ajax({
            type: "GET",
            url: "{{ route('check-bopp-item') }}",
            data: { bopp_item_code: bopp_item_code },
            success: function(data) {                            
                if (data.status == true && data.item != null) {                        
                    $(".bopp_size").val(data.item.bopp_size);
                    $(".bopp_micron").val(data.item.bopp_micron);                    
                }
                else{
                    $(".bopp_size").val('');
                    $(".bopp_micron").val('');
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    function updateBoppIssuecodes(){
        let bopp_item_code = $('#bopp_item_code_issue').val()
        $.ajax({
            type: "GET",
            url: "{{ route('check-bopp-item') }}",
            data: { bopp_item_code: bopp_item_code },
            success: function(data) {                            
                if (data.status == true && data.item != null) {                        
                    $(".bopp_issue_size").val(data.item.bopp_size);
                    $(".bopp_issue_micron").val(data.item.bopp_micron);                    
                }
                else{
                    $(".bopp_issue_size").val('');
                    $(".bopp_issue_micron").val('');
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }


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

    let partySuggestions = [];
    let selectedPartyIndex = -1;



    // $('#party_name').on('input keydown', function (event) {        
    //     if (event.key === 'Backspace' || event.key === 'Delete') {
    //         return;
    //     }
    //      // Navigate suggestions with arrow keys
    //     if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
    //         let items = $('.party_name_suggestions li');
    //         if (items.length === 0) return;

    //         // Remove existing highlight
    //         items.removeClass('highlight');

    //         if (event.key === 'ArrowDown') {
    //             selectedPartyIndex = (selectedPartyIndex + 1) % items.length;
    //             $('#party_name').val(items.eq(selectedPartyIndex).text());
    //         } else if (event.key === 'ArrowUp') {
    //             selectedPartyIndex = (selectedPartyIndex - 1 + items.length) % items.length;
    //             $('#party_name').val(items.eq(selectedPartyIndex).text());
    //         }

    //         // Add highlight to the selected item
    //         items.eq(selectedPartyIndex).addClass('highlight');
    //         return;
    //     }

    //     // On Enter key
    //     if (event.key === 'Enter') {
    //         event.preventDefault();
    //         const items = $('.party_name_suggestions li');

    //         $('.party_name_suggestions').hide();

            
    //         if (items.length > 0 && selectedPartyIndex >= 0) {
    //             $('#party_name').val(items.eq(selectedPartyIndex).text());
    //         } else if (partySuggestions.length > 0) {
    //             const currentVal = $('#party_name').val().toLowerCase();
    //             const exactMatch = partySuggestions.find(p => p.party_name.toLowerCase() === currentVal);

    //             if (!exactMatch) {
    //                 $('#party_name').val(partySuggestions[0].party_name);
    //             }
    //         }
            
    //         selectedPartyIndex = -1;                                             
    //         openform2();
    //         return false;
    //     }

    //     // Reset index
    //     selectedPartyIndex = -1;

    
    //     let query = $(this).val();        

    //     if (query.length == 0) {
    //         // $('#party_name').val('');
    //         $('.party_name_suggestions').hide();
    //         // partySuggestions = [];
    //         return;
    //     }
    //     if (query.length >= 1) {
    //         $.ajax({
    //             type: 'GET',
    //             url: '{{ route("getPartyDetails") }}',
    //             data: { party_name: query },
    //             success: function (data) {
    //                 let party_name_error = $('.party_name_error');

    //                 if (data.status === true && data.party.length > 0) {
    //                     partySuggestions = data.party;
    //                     let html = '';

    //                     data.party.forEach(party => {
    //                         html += `<li style="list-style:none; padding:10px; cursor:pointer;" onclick="updatePartyVal(this)">${party.party_name}</li>`;
    //                     });

    //                     $('.party_name_suggestions').html(html).show();
    //                     party_name_error.hide();
    //                 } else {
    //                     $('#party_name').val(query.slice(0, -1));
    //                 }
    //             }
    //         });
    //     } else {
    //         $('#party_name').val('');
    //         $('.party_name_suggestions').hide();
    //     }       
    // });
</script>

<script>
    function addIssueBopp(id){
        let boppIssueId = id;        
        $.ajax({
            type: 'GET',
            url: '{{ route("getBoppRoll") }}',
            data: { bopp_issue_id: boppIssueId },
            success: function (response) {
                console.log(response);

                let data = response.data;

                $('#bopp_roll_id').val(id);

                $('#bopp_item_code_issue_add').val(data.bopp_roll.item_code);

                $('#roll_size').val(data.bopp_issue.bopp_size);
                $('#roll_micron').val(data.bopp_issue.bopp_micron);

                
                $('#roll_category').empty();
                $('#roll_category').append(`<option value="${data.bopp_issue.bopp_category}">${data.categories[data.bopp_issue.bopp_category]}</option>`);

                
                $('#bopp_issue_add').modal('show');
            }
            
        })
    }

    $('.close-modal').on('click', ()=>{
        $('#bopp_issue_add form')[0].reset();
        $('#bopp_issue_add').modal('hide');
    })
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.body.addEventListener('click', function (e) {
            // ADD button click
            if (e.target.closest('.add_bopp')) {
                e.preventDefault();

                let originalRow = e.target.closest('.param-row');
                let clone = originalRow.cloneNode(true);

                // Clear input fields
                clone.querySelectorAll('input').forEach(input => input.value = '');

                // Replace add button with remove button
                const btnContainer = clone.querySelector('.col-md-2 .form-group');
                btnContainer.innerHTML = `
                    <label>Remove:</label><br>
                    <button type="button" class="btn remove_bopp btn-sm btn-danger btn-circle btn-shadow w-100">−</button>
                `;

                // Append the clone
                document.getElementById('paramsWrapper').appendChild(clone);
            }

            // REMOVE button click
            if (e.target.closest('.remove_bopp')) {
                e.preventDefault();
                const row = e.target.closest('.param-row');
                row.remove();
            }
        });
    });
</script>



<script>
    function editRole(element){
        var roleId = $(element).data('id');
        var roleName = $(element).data('name');
        var roleValue = $(element).data('value');

        console.log(roleId, roleName);

        $('#editrole').modal('show');
        $('#editrole').find('#roleid').val(roleId);
        $('#editrole').find('#rolename').val(roleName);        
        $('#editrole').find('#rolevalue').val(roleValue)
    }

    $('.close-edit-modal').on('click', function (){        
        $('#editrole').modal('hide');
    })
</script>
@endsection