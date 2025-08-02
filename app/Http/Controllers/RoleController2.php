<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
class RoleController2 extends Controller
{
    public function index()
    {
        $roles = Role::get();
        return view('admin.role-permission.role.index', ['roles' => $roles]);
    }
    public function create()
    {
        return view('admin.role-permission.role.create');

    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:roles,name'
            ]
        ]);

        Role::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name ?? 'web' // or any default value
        ]);

        return redirect('roles')->with('status', 'Role created');
    }



    public function edit(Role $role)
    {
        return view('admin.role-permission.role.edit', [
            'role' => $role
        ]);
    }
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:roles,name,' . $role->id
            ]
        ]);

        $role->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name ?? 'web'
        ]);

        return redirect('roles')->with('status', 'Role Updated');
    }
    public function destroy($roleId)
    {
        $role = Role::find($roleId);
        $role->delete();
        return redirect('roles')->with('status', 'Role Deleted');
    }

    public function addPermissionToRole($roleId)
    {
        $permissions = Permission::get();
        $role = Role::findOrFail($roleId);
        $rolePermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id', $role->id)->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')->all();
        return view('admin.role-permission.role.add-permissions', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }
    public function givePermissionToRole(Request $request, $roleId)
    {
        $request->validate([
            'permission' => 'required|array'
        ]);
    
        $role = Role::findOrFail($roleId);
        $permissions = $request->input('permission');
    
        // Ensure that permissions exist
        $validPermissions = Permission::whereIn('id', $permissions)->pluck('id')->toArray();
        if (count($validPermissions) != count($permissions)) {
            return redirect()->back()->withErrors('Some permissions do not exist.');
        }
    
        // Sync permissions with the role
        $role->syncPermissions($validPermissions);
    
        return redirect()->back()->with('status', 'Permission added to Role');
    }
    

}
