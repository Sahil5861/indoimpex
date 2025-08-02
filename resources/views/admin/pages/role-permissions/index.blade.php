@extends('layout.base')
<style>
    label{
        cursor:pointer;
        user-select: none;
    }
    input{
        cursor: pointer;
        
    }
</style>
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
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <form action="{{route('admin.rolepermission.save')}}" method="POST">
                            @csrf
                            <input type="hidden" name="role_id" id="role_id" value="{{$id}}">                           
                            <?php 
                                // $subroutes = \App\Models\Permission::where('parent_id', $permission->id)->get();    
                            ?>                                                                
                            {{-- @if (!empty($permissionAll))                                
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <div class="form-check m-0">
                                            <input 
                                                type="checkbox" 
                                                class="form-check-input" 
                                                id="selectAllPermissions"
                                            >
                                            <label class="form-check-label fw-bold" for="selectAllPermissions">
                                                SELECT ALL PERMISSIONS
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="row g-3 gx-2">
                                            @foreach ($permissionAll as $permission) 
                                                @php
                                                    $subroutes = \App\Models\Permission::where('parent_id', $permission->id)->get();    
                                                @endphp                                    
            
                                                @if ($subroutes->count() > 0)
                                                    <div class="col-lg-12">
                                                        <div class="card border shadow-sm">
                                                            <div class="card-header bg-light d-flex align-items-center justify-content-between">
                                                                <div class="form-check m-0">
                                                                    <input 
                                                                        type="checkbox" 
                                                                        class="form-check-input parent-checkbox" 
                                                                        id="parent_{{ $permission->id }}"
                                                                        data-parent="{{ $permission->id }}"
                                                                    >
                                                                    <label class="form-check-label fw-bold" for="parent_{{ $permission->id }}">
                                                                        {{ strtoupper($permission->feature . ' ' . $permission->name) }}
                                                                    </label>
                                                                </div>
                                                            </div>
            
                                                            <div class="card-body row g-2 child-group" data-group="{{ $permission->id }}">
                                                                @foreach ($subroutes as $item)
                                                                    <div class="col-lg-4 col-sm-6">
                                                                        <div class="form-check border p-3 rounded">
                                                                            <input 
                                                                                type="checkbox" 
                                                                                name="permission[]" 
                                                                                id="permission_{{ $item->id }}" 
                                                                                value="{{ $item->id }}"
                                                                                class="form-check-input child-checkbox"
                                                                                data-parent="{{ $permission->id }}"
                                                                                {{ in_array($item->id, $rolePermissons->toArray()) ? 'checked' : '' }}
                                                                            >
                                                                            <label class="form-check-label" for="permission_{{ $item->id }}" style="user-select: none; cursor: pointer;">
                                                                                {{ $item->feature . ' ' . $item->name }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                @else 
                                                <div class="col-lg-12">
                                                    <div class="card border shadow-sm">
                                                        <div class="card-header bg-light d-flex align-items-center justify-content-between">
                                                            <div class="form-check m-0">
                                                                <input 
                                                                    type="checkbox" 
                                                                    class="form-check-input child-checkbox" 
                                                                    id="parent_{{ $permission->id }}"
                                                                    data-parent="{{ $permission->id }}"
                                                                    name="permission[]" 
                                                                    value="{{ $permission->id }}"                                                        
                                                                    {{ in_array($permission->id, $rolePermissons->toArray()) ? 'checked' : '' }}
                                                                >
                                                                <label class="form-check-label fw-bold" for="parent_{{ $permission->id }}">
                                                                    {{ strtoupper($permission->feature . ' ' . $permission->name) }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif   --}}

                            <div class="card p-3">
                                {{-- Submit Button --}}
                                @if (hasPermission('Permission Assign', 'Assign'))                                    
                                <div class="text-end mt-3">
                                    <button type="submit" class="btn btn-lg btn-success-2">Save</button>
                                </div>
                                @endif
                                {{-- Select All Checkbox --}}

                                <?php                                 
                                    $allPermissions = \App\Models\Permission::all();
                                ?>
                                <div class="form-check mb-3">
                                    <input type="checkbox" id="select-all" class="form-check-input" 

                                    @if (count($rolePermissons) == count($allPermissions))
                                        checked
                                    @endif
                                    
                                    >
                                    <label for="select-all" class="form-check-label">Select All</label>
                                </div>

                                {{-- Permissions Tree --}}
                                @include('admin.pages.role-permissions.permissiontree', [
                                    'grouped' => $grouped,
                                    'parent_id' => 0,
                                    'rolePermissons' => $rolePermissons // <== pass it recursively
                                ])
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


{{-- <script>
    // When parent checkbox is clicked, toggle all its children
    $('.parent-checkbox').on('change', function () {
        const parentId = $(this).data('parent');
        const isChecked = $(this).is(':checked');
        $(`.child-checkbox[data-parent="${parentId}"]`).prop('checked', isChecked);
    });

    // Optional: When all children of a parent are checked, auto-check the parent
    $('.child-checkbox').on('change', function () {
        const parentId = $(this).data('parent');
        const total = $(`.child-checkbox[data-parent="${parentId}"]`).length;
        const checked = $(`.child-checkbox[data-parent="${parentId}"]:checked`).length;

        $(`#parent_${parentId}`).prop('checked', total === checked);
    });

    // Trigger initial sync
    $('.parent-checkbox').each(function () {
        const parentId = $(this).data('parent');
        const total = $(`.child-checkbox[data-parent="${parentId}"]`).length;
        const checked = $(`.child-checkbox[data-parent="${parentId}"]:checked`).length;
        $(this).prop('checked', total === checked);
    });

    // Function to check if all permission checkboxes are checked
    function updateSelectAllCheckbox() {
        const allCheckboxes = document.querySelectorAll('input[type="checkbox"][name="permission[]"]');
        const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
        document.getElementById('selectAllPermissions').checked = allChecked;
    }

    // Select All toggle
    document.getElementById('selectAllPermissions').addEventListener('change', function () {
        const isChecked = this.checked;
        document.querySelectorAll('input[type="checkbox"][name="permission[]"]').forEach(function (checkbox) {
            checkbox.checked = isChecked;
        });

        // Also update parent checkboxes if needed
        document.querySelectorAll('.parent-checkbox').forEach(function (parentCheckbox) {
            parentCheckbox.checked = isChecked;
        });
    });

    // Monitor changes on individual checkboxes
    document.querySelectorAll('input[type="checkbox"][name="permission[]"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', updateSelectAllCheckbox);
    });

    // Initial state check on page load
    document.addEventListener('DOMContentLoaded', function () {
        updateSelectAllCheckbox();
    });
</script> --}}

@endsection