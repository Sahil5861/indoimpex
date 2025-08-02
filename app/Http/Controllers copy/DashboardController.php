<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // // Fetch all roles
        // $roles = Role::all();

        // // Fetch active users
        // $data = User::where('status', 'active')->get();

        // // Fetch current user
        // $user = auth()->user();

        // // Define user permissions
        // $permissions = [
        //     'colours' => $user->hasPermissionTo('colours'),
        //     'sizes' => $user->hasPermissionTo('sizes'),
        //     'roles' => $user->hasPermissionTo('roles'),
        //     'plans' => $user->hasPermissionTo('plans'),
        //     'dealers' => $user->hasPermissionTo('dealers'),
        //     'contact_persons' => $user->hasPermissionTo('contact_persons'),
        //     'documents' => $user->hasPermissionTo('documents'),
        //     'documents_type' => $user->hasPermissionTo('documents_type'),
        //     'brands' => $user->hasPermissionTo('brands'),
        //     'categories' => $user->hasPermissionTo('categories'),
        //     'product_relations' => $user->hasPermissionTo('product_relations'),
        //     'products' => $user->hasPermissionTo('products'),
        //     'gallery' => $user->hasPermissionTo('gallery'),
        //     'users' => $user->hasPermissionTo('users'),
        // ];
        // // dd($permissions);
        // // Pass data to the view
        return view('admin.dashboard');
    }
}
