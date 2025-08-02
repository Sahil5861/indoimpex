<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Role;
use App\Models\PermissionGroup;
use App\Models\PermissionCategory;
use App\Models\RolePermission;
use Carbon\Carbon;
use DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Role::query();

            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            $data = $query->orderBy('id')->get();

            return DataTables::of($data)
                ->addIndexColumn()                
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                })
                ->addColumn('access', function ($row) {
                    return '<a href="' . route('admin.rolepermission', ['id' => $row->id]) . '" class="text-primary"><i class="ph-eye me-2"></i></a>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="dropdown">
                                    <a href="#" class="text-body" data-bs-toggle="dropdown">
                                        <i class="ph-list"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="#" onclick="editRole(this)" data-id="'.$row->id.'" data-name="'.$row->role_name.'" class="dropdown-item">
                                            <i class="ph-pencil me-2"></i>Edit
                                        </a>
                                        <a href="' . route('admin.role.delete', $row->id) . '" data-id="' . $row->id . '" class="dropdown-item delete-button">
                                            <i class="ph-trash me-2"></i>Delete
                                        </a>
                                    </div>
                                </div>';
                })
                ->rawColumns(['action', 'access'])
                ->make(true);
        }

        return view('admin.pages.roles.index');
    }

    public function manageAccess($id)
    {
        $role = Role::findOrFail($id);
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $permissions = Permission::all()->pluck('name')->toArray();

        return view('admin.pages.roles.access', compact('role', 'rolePermissions', 'permissions'));
    }

    public function access($id)
    {
        $rolePermissions = Role::find($id)->permissions;
        session(['permissions' => $rolePermissions]);

        return view('admin.role', compact('rolePermissions'));
    }

    public function updateAccess(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);

        $permissionNames = $request->input('permissions', []);
        $permissionIds = Permission::whereIn('name', $permissionNames)->pluck('id')->toArray();

        $role->permissions()->sync($permissionIds);

        return redirect()->route('admin.role')->with('success', 'Permissions updated successfully.');
    }
    public function create()
    {
        $roles = Role::whereNull('deleted_at')->get();
        return view('admin.pages.roles.create', compact('roles'));
    }

    public function edit($id)
    {
        $roles = Role::whereNull('deleted_at')->get();
        $role = Role::find($id);

        return view('admin.pages.roles.edit', compact('role', 'roles'));
    }


    public function store(Request $request)
    {

        
        $validate = $request->validate([
            'name' => 'required|string|max:255',            
        ]);
        
        if (!empty($request->id)) {
            $role = Role::firstwhere('id', $request->id);
            $role->role_name = $request->input('name');

            if ($role->save()) {
                return redirect()->route('admin.role')->with('success', 'Role ' . $request->id . ' Updated Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        } else {
            $role = new Role();
            $role->role_name = $request->input('name');

            if ($role->save()) {
                return redirect()->route('admin.role')->with('success', 'Role added Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        }
    }

    public function remove(Request $request, $id)
    {
        $role = Role::firstwhere('id', $request->id);

        if ($role->delete()) {
            return back()->with('success', 'Role deleted Suuccessfully !!');
        } else {
            return back()->with('error', 'Something went wrong !!');
        }
    }


    public function updateStatus($id, Request $request)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $role = Role::findOrFail($id);
        if ($role) {
            $role->status = $request->status;
            $role->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }

    }



    public function deleteSelected(Request $request)
    {
        $selectedRoles = $request->input('selected_roles');
        if (!empty($selectedRoles)) {
            Role::whereIn('id', $selectedRoles)->delete();
            return response()->json(['success' => true, 'message' => 'Selected roles deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'No roles selected for deletion.']);
    }

    public function import(Request $request)
    {
        $validate = $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);
        if ($validate == false) {
            return redirect()->back();
        }
        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        if (($handle = fopen($path, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ','); // Skip the header row

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                Role::create([
                    'id' => $data[0],
                    'name' => $data[1],
                ]);
            }

            fclose($handle);
        }

        return redirect()->route('admin.role')->with('success', 'roles imported successfully.');

    }

    public function export(Request $request)
    {
        try {
            $status = $request->query('status', null); // Get status from query parameters

            $response = new StreamedResponse(function () use ($status) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Add headers for CSV
                $sheet->fromArray(['ID', 'Name', 'Created At', 'Status'], null, 'A1');

                // Fetch roles based on status
                $query = Role::query();
                if ($status !== null) {
                    $query->where('status', $status);
                }
                $roles = $query->get();
                $rolesData = [];
                foreach ($roles as $role) {
                    $rolesData[] = [
                        $role->id,
                        $role->name,
                        $role->created_at->format('d M Y'),
                        $role->status == 1 ? 'Active' : 'Inactive',
                    ];
                }
                $sheet->fromArray($rolesData, null, 'A2');

                // Write CSV to output
                $writer = new Csv($spreadsheet);
                $writer->setUseBOM(true);
                $writer->save('php://output');
            });

            // Set headers for response
            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename="roles.csv"');

            return $response;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sampleFileDownloadRole()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="role_csv_sample.csv"',
        ];

        $columns = ['Id', 'Name', 'Created At', 'Status'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    // role permissions:

    public function imdexperm(Request $request, $id){

        $role = Role::find($id);
        $perm_groups = PermissionGroup::orderBy('name')->get();

        return view('admin.pages.roles.rolePermission', compact('role', 'perm_groups'));
    }

    protected $perm_category = array('can_view', 'can_add', 'can_edit', 'can_delete');

    public function rolepermissionsupdate(Request $request){
        $perCatPost = $request->input('per_cat', []);
        $roleId = $request->input('role_id');
        $time = Carbon::now();

        // dd($request->all()); exit;

        $toBeInsert = [];
        $toBeUpdate = [];
        $toBeDelete = [];

        $permCategoryKeys = ['can_view', 'can_add', 'can_edit', 'can_delete']; // keys you're checking

        foreach ($perCatPost as $catId) {
            $insertData = [];
            $defaultPermissions = array_fill_keys($permCategoryKeys, 0);

            foreach ($permCategoryKeys as $key) {
                if ($request->has("{$key}-perm_{$catId}")) {
                    $insertData[$key] = 1;
                }
            }

            $prevId = $request->input("roles_permissions_id_{$catId}", 0);

            if ($prevId != 0) {
                if (!empty($insertData)) {
                    $insertData['id'] = $prevId;
                    $toBeUpdate[] = array_merge($defaultPermissions, $insertData);
                } else {
                    $toBeDelete[] = $prevId;
                }
            } elseif (!empty($insertData)) {
                $insertData['role_id'] = $roleId;
                $insertData['prem_cat_id'] = $catId;
                $insertData['created_at'] = $time;
                $insertData['updated_at'] = $time;
                $toBeInsert[] = array_merge($defaultPermissions, $insertData);
            }
        }

        // Insert
        if (!empty($toBeInsert)) {
            RolePermission::insert($toBeInsert);
        }

        // Update
        foreach ($toBeUpdate as $updateData) {
            $rolePerm = RolePermission::find($updateData['id']);
            if ($rolePerm) {
                unset($updateData['id']);
                $rolePerm->update($updateData);
            }
        }

        // Delete
        if (!empty($toBeDelete)) {
            RolePermission::whereIn('id', $toBeDelete)->delete();
        }

        return redirect()->back()->with('success', 'Permissions updated successfully.');
    }



}
