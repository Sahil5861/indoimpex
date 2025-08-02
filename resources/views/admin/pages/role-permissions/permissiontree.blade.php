@php
    $level = $level ?? 0;
@endphp

{{-- <ul style="margin-left: {{ $level * 20 }}px;">
    @foreach ($grouped[$parent_id] ?? [] as $permission)
        <li>
            <label>
                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}">
                {{ $permission->name }}
            </label>            
            @if (isset($grouped[$permission->id]))
                @include('admin.pages.role-permissions.permissiontree', [
                    'grouped' => $grouped,
                    'parent_id' => $permission->id,
                    'level' => $level + 1
                ])
            @endif
        </li>
    @endforeach
</ul> --}}


{{-- <div class="card">
    @foreach ($grouped[$parent_id] ?? [] as $permission)
    <div class="card">
        <div class="card-body">
            <label>
                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}">
                {{ $permission->name }}
            </label>            
            @if (isset($grouped[$permission->id]))
                @include('admin.pages.role-permissions.permissiontree', [
                    'grouped' => $grouped,
                    'parent_id' => $permission->id,
                    'level' => $level + 1
                ])
            @endif
        </div>
    </div>
    @endforeach
</div> --}}

{{-- <div class="card mb-3 p-2">
    @foreach ($grouped[$parent_id] ?? [] as $permission)
        @if (!isset($grouped[$permission->id]))            
            @php
                $leafPermissions[] = $permission;
            @endphp
        @else
            <div class="card mb-2">
                <div class="card-body p-3">
                    <label class="fw-bold">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}">
                        {{ $permission->name }}
                    </label>
                    
                    <div class="p-1">
                        @include('admin.pages.role-permissions.permissiontree', [
                            'grouped' => $grouped,
                            'parent_id' => $permission->id,
                            'level' => $level + 1
                        ])
                    </div>
                </div>
            </div>
        @endif
    @endforeach
    
    @isset($leafPermissions)
        <div class="card-body p-3">
            <div class="row p-2">
                @foreach ($leafPermissions as $index => $leaf)
                    <div class="col-md-3 mb-2">
                        <label>
                            <input type="checkbox" name="permissions[]" value="{{ $leaf->id }}">
                            {{ $leaf->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        @php unset($leafPermissions); @endphp
    @endisset
</div> --}}


<div class="accordion" id="permissionAccordion-{{ $parent_id }}">
    @foreach ($grouped[$parent_id] ?? [] as $index => $permission)
        @php
            $isLeaf = !isset($grouped[$permission->id]);
        @endphp

        @if (!$isLeaf)
            <div class="accordion-item">
                <h2 class="accordion-header d-flex align-items-center" id="heading-{{ $permission->id }}">
                    <input type="checkbox" name="permissions[]" data-parent="{{ $permission->id }}" class="form-check-input parent-checkbox" value="{{ $permission->id }}" class="me-2 ms-2" {{ in_array($permission->id, $rolePermissons ?? []) ? 'checked' : '' }} style="margin-left:-10px;" >
                    <label class="accordion-button flex-grow-1 collapsed form-check-label"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{ $permission->id }}"
                            aria-expanded="false"
                            aria-controls="collapse-{{ $permission->id }}">
                        {{ $permission->name }}
                </label>
                </h2>
                <div id="collapse-{{ $permission->id }}"
                     class="accordion-collapse collapse"
                     aria-labelledby="heading-{{ $permission->id }}"
                     data-bs-parent="#permissionAccordion-{{ $parent_id }}">
                    <div class="accordion-body">
                        @include('admin.pages.role-permissions.permissiontree', [
                            'grouped' => $grouped,
                            'parent_id' => $permission->id,
                            'level' => $level + 1,
                            'rolePermissons' => $rolePermissons // <== pass it recursively
                        ])
                    </div>
                </div>
            </div>
        @else
            @php $leafPermissions[] = $permission; @endphp
        @endif
    @endforeach

    @isset($leafPermissions)
    <div class=" mt-3">
        <div class="row">
            @foreach ($leafPermissions as $leaf)
                <div class="col-md-3 mb-3">
                    <div class="card-body border rounded shadow-sm">
                        <label style="display: flex; justify-content:flex-start; align-items:center; gap:10px;">
                            <input type="checkbox" class="form-check-input child-checkbox" name="permissions[]" data-parent="{{ $parent_id }}" value="{{ $leaf->id }}"
                                   {{ in_array($leaf->id, $rolePermissons ?? []) ? 'checked' : '' }} style="margin-left:-30px;" >
                            <span>{{ $leaf->name }}</span>
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @php unset($leafPermissions); @endphp
    @endisset

</div>

{{-- script --}}

{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Listen to all parent checkboxes
        document.querySelectorAll('.accordion-header input[type="checkbox"]').forEach(parentCheckbox => {
            parentCheckbox.addEventListener('change', function () {
                const parentId = this.closest('.accordion-header').getAttribute('id');
                const collapseId = this.closest('.accordion-item').querySelector('.accordion-collapse').getAttribute('id');

                // Get all checkboxes inside this collapse body
                const childCheckboxes = document.querySelectorAll(`#${collapseId} input[type="checkbox"]`);

                childCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
            });
        });
    });


    document.querySelectorAll('.accordion-collapse input[type="checkbox"][name="permissions[]"]').forEach(childCheckbox => {
        childCheckbox.addEventListener('change', function () {
            const collapse = this.closest('.accordion-collapse');
            const parentCheckbox = collapse.closest('.accordion-item')
                .querySelector('.accordion-header input[type="checkbox"]');

            const allChildCheckboxes = collapse.querySelectorAll('input[type="checkbox"][name="permissions[]"]');
            const allChecked = Array.from(allChildCheckboxes).every(cb => cb.checked);

            parentCheckbox.checked = allChecked;
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select-all');

        selectAll.addEventListener('change', function () {
            const allCheckboxes = document.querySelectorAll('input[type="checkbox"][name="permissions[]"]');

            allCheckboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });
        });
    });

</script> --}}

{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {

        // Parent checkbox toggles all child checkboxes
        document.querySelectorAll('.accordion-header input[type="checkbox"]').forEach(parentCheckbox => {
            parentCheckbox.addEventListener('change', function () {
                const collapseId = this.closest('.accordion-item').querySelector('.accordion-collapse').getAttribute('id');
                const childCheckboxes = document.querySelectorAll(`#${collapseId} input[type="checkbox"][name="permissions[]"]`);
                childCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
            });
        });

        // Child checkbox change updates parent checkbox
        document.querySelectorAll('.accordion-collapse input[type="checkbox"][name="permissions[]"]').forEach(childCheckbox => {
            childCheckbox.addEventListener('change', function () {
                const collapse = this.closest('.accordion-collapse');
                const parentCheckbox = collapse.closest('.accordion-item')
                    .querySelector('.accordion-header input[type="checkbox"]');

                const allChildCheckboxes = collapse.querySelectorAll('input[type="checkbox"][name="permissions[]"]');
                const allChecked = Array.from(allChildCheckboxes).every(cb => cb.checked);

                parentCheckbox.checked = allChecked;
            });
        });

        // Global select all
        const selectAll = document.getElementById('select-all');
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                const allCheckboxes = document.querySelectorAll('input[type="checkbox"][name="permissions[]"]');
                allCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
            });
        }
    });
</script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const selectAll = document.getElementById('select-all');

        // Parent toggles all its children
        document.querySelectorAll('.accordion-header input[type="checkbox"]').forEach(parentCheckbox => {
            parentCheckbox.addEventListener('change', function () {
                const collapseId = this.closest('.accordion-item').querySelector('.accordion-collapse').getAttribute('id');
                const childCheckboxes = document.querySelectorAll(`#${collapseId} input[type="checkbox"][name="permissions[]"]`);
                childCheckboxes.forEach(cb => cb.checked = this.checked);

                updateSelectAll(); // Sync select-all checkbox
            });
        });

        // Child updates parent checkbox
        document.querySelectorAll('.accordion-collapse input[type="checkbox"][name="permissions[]"]').forEach(childCheckbox => {
            childCheckbox.addEventListener('change', function () {
                const collapse = this.closest('.accordion-collapse');
                const parentCheckbox = collapse.closest('.accordion-item')
                    .querySelector('.accordion-header input[type="checkbox"]');

                const allChildCheckboxes = collapse.querySelectorAll('input[type="checkbox"][name="permissions[]"]');
                const allChecked = Array.from(allChildCheckboxes).every(cb => cb.checked);
                parentCheckbox.checked = allChecked;

                updateSelectAll(); // Sync select-all checkbox
            });
        });
        

        // Select All toggles everything
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                const allCheckboxes = document.querySelectorAll('input[type="checkbox"][name="permissions[]"]');
                allCheckboxes.forEach(cb => cb.checked = this.checked);
            });
        }

        function updateParentCheckbox(childCheckbox) {
            const parentId = childCheckbox.dataset.parent;
            if (!parentId) return;

            // Find the parent checkbox using the data-parent id
            const parentCheckbox = document.querySelector(`.accordion-header input[data-parent="${parentId}"]`);
            if (!parentCheckbox) return;

            // Get all children of this parent
            const childCheckboxes = document.querySelectorAll(`.accordion-collapse input[data-parent="${parentId}"]`);
            const allChecked = Array.from(childCheckboxes).every(cb => cb.checked);

            parentCheckbox.checked = allChecked;

            // Recursively go up if the parent itself has a parent
            updateParentCheckbox(parentCheckbox);
        }

        // Function to update select-all checkbox
        function updateSelectAll() {
            const allCheckboxes = document.querySelectorAll('input[type="checkbox"][name="permissions[]"]');
            const checkedCheckboxes = document.querySelectorAll('input[type="checkbox"][name="permissions[]"]:checked');
            selectAll.checked = allCheckboxes.length === checkedCheckboxes.length;
        }
    });
</script>



