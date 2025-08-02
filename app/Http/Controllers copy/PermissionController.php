<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\PermissionCategory;
use App\Models\PermissionGroup;

use App\Models\RolePermission;
use Illuminate\Http\Request;
use DataTables;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
        $query = Permission::query();

        $data = $query->orderBy('id')->get();

        return DataTables::of($data)
            ->addIndexColumn()                
            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y');
            })                 
            ->addColumn('action', function ($row) {
                return '<div class="dropdown">
                                <a href="#" class="text-body" data-bs-toggle="dropdown">
                                    <i class="ph-list"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="#" onclick="editRole(this)" data-id="'.$row->id.'" data-name="'.$row->category_name.'" data-value="'.$row->category_value.'" class="dropdown-item">
                                        <i class="ph-pencil me-2"></i>Edit
                                    </a>
                                    <a href="' . route('admin.NonWovenCategory.remove', $row->id) . '" data-id="' . $row->id . '" class="dropdown-item delete-button">
                                        <i class="ph-trash me-2"></i>Delete
                                    </a>
                                </div>
                            </div>';
            })
            ->rawColumns(['created_at','action'])
            ->make(true);
        }

        return view('admin.pages.permissions.index'); 
    }    
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name'
            ]
        ]);
    
        Permission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name ?? 'web' // Default value
        ]);
    
        return redirect('permissions')->with('status', 'Permission created');
    }
    
    public function edit(Permission $permission)
    {
        return view('admin.role-permission.permission.edit', [
            'permission' => $permission
        ]);
    }
    public function permissionsUpdate(Request $request)
    {
        $perCatPost = $request->input('per_cat', []);
        $toBeUpdate = [];
        $toBeDelete = [];

        $permCategory = ['view', 'add', 'edit', 'delete']; // same as $perm_category in PHP version
        
        foreach ($perCatPost as $catId) {
            $insertData = [];
            $defaults = array_fill_keys($permCategory, 0);

            foreach ($permCategory as $permKey) {
                if ($request->has($permKey . '_' . $catId)) {
                    $insertData[$permKey] = 1;
                } else {
                    $defaults[$permKey] = 0;
                }
            }

            if (!empty($insertData)) {
                $insertData['id'] = $catId;
                $toBeUpdate[] = array_merge($defaults, $insertData);
            } else {
                $insertData['id'] = $catId;
                $toBeDelete[] = array_merge($defaults, $insertData);
            }
        }

        if (!empty($toBeUpdate)) {
            foreach ($toBeUpdate as $item) {
                $permission = Permission::find($item['id']);
                if ($permission) {
                    $permission->enable_view = $item['view'];
                    $permission->enable_add = $item['add'];
                    $permission->enable_edit = $item['edit'];
                    $permission->enable_delete = $item['delete'];
                    $permission->save();
                }
            }
        }

        if (!empty($toBeDelete)) {
            foreach ($toBeDelete as $item) {
                $permission = Permission::find($item['id']);
                if ($permission) {
                    $permission->enable_view = $item['view'];
                    $permission->enable_add = $item['add'];
                    $permission->enable_edit = $item['edit'];
                    $permission->enable_delete = $item['delete'];
                    $permission->save();
                }
            }
        }
        return redirect()->back()->with('success', 'Permission updated successfully');
    }   
    
    public function destroy($permissionId)
    {
        $permission = Permission::find($permissionId);
        $permission->delete();
        return redirect('permissions')->with('status', 'Permission Deleted');
    }


    public function createModule(Request $request){
        $request->validate([
            'name' => 'required',            
        ]);
        $module = new PermissionGroup();
        $module->name = $request->name;
        if ($module->save()) {
            return redirect()->back()->with('success', 'Module Added successfully');
        }
    }

    public function createFeature(Request $request){
        $request->validate([
            'name' => 'required',
            'prem_group_id' => 'required'
        ]);

        // dd($request->all()); exit;
        $feature = new Permission();
        $feature->name = $request->name;
        $feature->prem_group_id = $request->prem_group_id;
        if ($feature->save()) {
            return redirect()->back()->with('success', 'Permission Added successfully');
        }
    }
}
