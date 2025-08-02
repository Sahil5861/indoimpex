<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\PermissionCategory;
use App\Models\PermissionGroup;

use App\Models\RolePermission;
use App\Models\Module;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Str;

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
            ->addColumn('name', function ($row){
                return $row->name;
            })               
            ->addColumn('action', function ($row) {
                return '<div class="dropdown">
                                <a href="#" class="text-body" data-bs-toggle="dropdown">
                                    <i class="ph-list"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="#" onclick="editPermision(this)" data-id="'.$row->id.'" class="dropdown-item">
                                        <i class="ph-pencil me-2"></i>Edit
                                    </a>
                                    <a href="' . route('admin.permission.destroy', $row->id) . '" data-id="' . $row->id . '" class="dropdown-item delete-button">
                                        <i class="ph-trash me-2"></i>Delete
                                    </a>
                                </div>
                            </div>';
            })   
            ->addColumn('route', function($row){
                return $row->route ? $row->route : '----';
            })         
            ->addColumn('feature', function($row){
                return $row->feature ? $row->feature : '----';
            })         
            ->rawColumns(['created_at','action', 'name', 'sub_module', 'route', 'feature'])
            ->make(true);
        }

        $modules = Permission::where('route', '')->get() ?? 0;
        return view('admin.pages.permissions.index', compact('modules')); 
    } 
    
    public function getPermissionData(Request $request){
        $permission = Permission::where('id', $request->id)->first();
        // dd($permission); exit;
        if ($permission) {
            return response()->json([
                'status' => true,
                'permission' => $permission
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'No Permission found '
            ]);
        }
    }
    public function store(Request $request)
    {
        $request->validate([            
            'name' => 'required',
            // 'action' => 'required'        
        ]);
    
        
        $action = $request->action ?? '';
        $parent_id = $request->parent_id ?? 0;
        
        
        
        // $slug = strtolower(str_replace(' ', '', $main_module_name . '-' . $sub_module_name . '-' . $feature));
        // $route = strtolower(str_replace(' ', '', $main_module_name . '.' . $sub_module_name . '.' . $feature));
        $route = $request->route ?? '';
        $name = $request->name;
        $slug = strtolower(str_replace(' ', '-', $name)) . '-' . strtolower($action);
        
        $data = [
            'name' => $name,
            'slug' => $slug,
            'route' => $route,                        
            'feature' => ucfirst(strtolower($action)),
            'parent_id' => 0,
        ];

        if ($request->has('parent_id') && $request->parent_id != '') {
            $data['parent_id'] = $request->parent_id;
        }  

        if ($request->has('id') && ($request->id != null || $request->id != '') ) {
            $permission = Permission::where('id', $request->id)->first();

            if ($permission) {
                $permission->update($data);
                return redirect()->back()->with('success', 'Permission updated');
            }
        }
        else{
            // $permission = Permission::create($data);
            // RolePermission::create([
            //     'role_id' => '1',
            //     'permission_id' => $permission->id
            // ]);

            Permission::create($data);

            
            return redirect()->back()->with('success', 'Permission created');
        }
        
        
    
    
        
    }
    
    public function edit(Permission $permission)
    {
        return view('admin.role-permission.permission.edit', [
            'permission' => $permission
        ]);
    }
    
    public function destroy($permissionId)
    {
        $permission = Permission::find($permissionId);
        $permission->delete();
        return redirect('admin.permissions')->with('status', 'Permission Deleted');
    }

    public function multidelete(Request $request){
        $selectedIds = $request->input('selected_roles');
        // print_r($selectedIds); exit;
        if (!empty($selectedIds)) {
            Permission::whereIn('id', $selectedIds)->delete();
            return response()->json(['success' => true, 'message' => 'Selected Categories deleted successfully.']);
        }
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
