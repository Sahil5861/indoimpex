<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Module;
use Carbon\Carbon;
use DataTables;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Module::query();

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
                                        <a href="#" onclick="editModule(this)" data-id="'.$row->id.'" data-name="'.$row->role_name.'" class="dropdown-item">
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


    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',            
        ]);
        
        if (!empty($request->id)) {
            $module = Module::firstwhere('id', $request->id);
            $role->role_name = $request->input('name');

            if ($role->save()) {
                return back()->with('success', 'Module Added Successfully');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        } else {
                       

            $data = [
                'name' => $request->input('name'),
                'route_url' => $request->input('route_url'),
                'sorting_order' => $sorting_order
            ];

            if ($request->has('parent_id')) {
                $data['parent_id'] = $request->paren_id;
            }

            Module::create($data);

            return redirect()->route('admin.role')->with('success', 'Module added Suuccessfully !!');


            if ($role->save()) {
                
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        }
    }

    public function remove(Request $request, $id)
    {
        $role = Module::firstwhere('id', $request->id);

        if ($role->delete()) {
            return back()->with('success', 'Module deleted Suuccessfully !!');
        } else {
            return back()->with('error', 'Something went wrong !!');
        }
    }

    public function deleteSelected(Request $request)
    {
        $selectedModules = $request->input('selected_roles');
        if (!empty($selectedModules)) {
            Module::whereIn('id', $selectedModules)->delete();
            return response()->json(['success' => true, 'message' => 'Selected roles deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'No roles selected for deletion.']);
    }

}
