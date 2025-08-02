@extends('layout.base')

@section('content')

<div class="page-content">
    @include('layouts.sidebar')
    <div class="content-wrapper">
        <div class="content-inner">
            <div class="page-header page-header-light shadow">
                <div class="page-header-content d-lg-flex">
                    <div class="d-flex">
                        <h4 class="page-title mb-0">
                            Dashboard - <span class="fw-normal">Add Role Access</span>
                        </h4>
                    </div>
                </div>
            </div>
            @php
                $rolePermissions = isset($rolePermissions) ? $rolePermissions : [];
            @endphp

            <div class="content">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title fw-bold">Add Role Access</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.role.updateAccess', $role->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <!-- Manage User Section -->
                                <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                                    <div class="col-sm-12 p-3 p_res border d_flex">
                                        <label for="users">Manage User</label>
                                        <input type="checkbox" name="permissions[]" value="users" {{ in_array('users', $rolePermissions) ? 'checked' : '' }}>
                                    </div>
                                </div>

                                <!-- Master Section -->
                                <div class="col-lg-12 col-md-6 col-sm-6 mb-4 p-3 p_res border">
                                    <h3>Master</h3>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
                                            <div class="col-sm-12 p-3 p_res border d_flex">
                                                <label for="roles">Roles</label>
                                                <input type="checkbox" name="permissions[]" value="roles" {{ in_array('roles', $rolePermissions) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
                                            <div class="col-sm-12 p-3 p_res border d_flex">
                                                <label for=" colours">Colours</label>
                                                <input type="checkbox" name="permissions[]" value="colours" {{ in_array('colours', $rolePermissions) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
                                            <div class="col-sm-12 p-3 p_res border d_flex">
                                                <label for=" sizes">Sizes</label>
                                                <input type="checkbox" name="permissions[]" value="sizes" {{ in_array('sizes', $rolePermissions) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
                                            <div class="col-sm-12 p-3 p_res border d_flex">
                                                <label for=" plans">Plans</label>
                                                <input type="checkbox" name="permissions[]" value="plans" {{ in_array('plans', $rolePermissions) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dealers Section -->
                                <div class="col-lg-12 col-md-12 col-sm-12 mb-4 border p-3 p_res">
                                    <h3>Dealers</h3>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 col-sm-12 mb-4">
                                            <div class="col-sm-12 p-3 p_res border d_flex">
                                                <label for=" dealers">Dealer</label>
                                                <input type="checkbox" name="permissions[]" value="dealers" {{ in_array('dealers', $rolePermissions) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12 mb-4">
                                            <div class="col-sm-12 p-3 p_res border d_flex">
                                                <label for=" contact_persons">Contact Person</label>
                                                <input type="checkbox" name="permissions[]" value="contact_persons" {{ in_array('contact_persons', $rolePermissions) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Document Section -->
                                <div class="col-lg-12 col-md-12 col-sm-12 mb-4 border p-3 p_res">
                                    <h3>Documents</h3>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 col-sm-12 mb-4">
                                            <div class="col-sm-12 p-3 p_res border d_flex">
                                                <label for=" documents">Documents</label>
                                                <input type="checkbox" name="permissions[]" value="documents" {{ in_array('documents', $rolePermissions) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12 mb-4">
                                            <div class="col-sm-12 p-3 p_res border d_flex">
                                                <label for="documents_type">Documents Type</label>
                                                <input type="checkbox" name="permissions[]" value="documents_type" {{ in_array('documents_type', $rolePermissions) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Manage Products Section -->
                                <div class="col-lg-12 col-md-6 col-sm-6 mb-4 border p-3 p_res">
                                    <h3>Manage Products</h3>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
                                            <div class="col-sm-12 p-3 p_res border d_flex">
                                                <label for=" brands">Brands</label>
                                                <input type="checkbox" name="permissions[]" value="brands" {{ in_array('brands', $rolePermissions) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
                                            <div class="col-sm-12 p-3 p_res border d_flex">
                                                <label for=" categories">Categories</label>
                                                <input type="checkbox" name="permissions[]" value="categories" {{ in_array('categories', $rolePermissions) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
                                            <div class="col-sm-12 p-3 p_res border d_flex">
                                                <label for=" product_relations">Product Relations</label>
                                                <input type="checkbox" name="permissions[]" value="product_relations" {{ in_array('product_relations', $rolePermissions) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
                                            <div class="col-sm-12 p-3 p_res border d_flex">
                                                <label for=" products">Products</label>
                                                <input type="checkbox" name="permissions[]" value="products" {{ in_array('products', $rolePermissions) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Gallery Section -->
                                <div class="col-lg-12 col-md-6 col-sm-6 mb-4 border p-3 p_res">
                                    <h3>Gallery</h3>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
                                            <div class="col-sm-12 p-3 p_res border d_flex">
                                                <label for=" gallery">Image Gallery</label>
                                                <input type="checkbox" name="permissions[]" value="gallery" {{ in_array('gallery', $rolePermissions) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Update Access</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection