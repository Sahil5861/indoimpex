<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NonWovenCategory;
use App\Models\NonWovenItem;

use Carbon\Carbon;
use DataTables;

class NonWovenCategoryController extends Controller
{
    public function index(Request $request)
    {                
        if ($request->ajax()) {
            $query = NonWovenCategory::query();

            $data = $query->orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()                
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : '';
                })                
                ->addColumn('action', function ($row) {
                    // return '<div class="dropdown">
                    //                 <a href="#" class="text-body" data-bs-toggle="dropdown">
                    //                     <i class="ph-list"></i>
                    //                 </a>
                    //                 <div class="dropdown-menu dropdown-menu-end">
                    //                     <a href="#" onclick="editRole(this)" data-id="'.$row->id.'" data-name="'.$row->category_name.'" data-value="'.$row->category_value.'" class="dropdown-item">
                    //                         <i class="ph-pencil me-2"></i>Edit
                    //                     </a>
                    //                     <a href="' . route('admin.NonWovenCategory.remove', $row->id) . '" data-id="' . $row->id . '" class="dropdown-item delete-button">
                    //                         <i class="ph-trash me-2"></i>Delete
                    //                     </a>
                    //                 </div>
                    //             </div>';

                    $action = '';
                    if (hasPermission('Non Woven Fabric Items Update', 'Update') || hasPermission('Non Woven Fabric Items Delete', 'Delete')) {
                        $action .= '<div class="dropdown">
                                    <a href="#" class="text-body" data-bs-toggle="dropdown">
                                        <i class="ph-list"></i>
                                    </a>
                                        <div class="dropdown-menu dropdown-menu-end">';
                    }
                    if (hasPermission('Non Woven Fabric Items Update', 'Update')) {
                        $action .= '<a href="#" onclick="editRole(this)" data-id="'.$row->id.'" data-name="'.$row->category_name.'" data-value="'.$row->category_value.'" class="dropdown-item">
                                        <i class="ph-pencil me-2"></i>Edit
                                    </a>';
                    }
                    if (hasPermission('Non Woven Fabric Items Delete', 'Delete')) {
                        $action .= '<a href="' . route('admin.NonWovenCategory.remove', $row->id) . '" data-id="' . $row->id . '" class="dropdown-item delete-button">
                                        <i class="ph-trash me-2"></i>Delete
                                    </a>';
                    }
                    $action .= '</div></div>';      
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.pages.non-woven-fabric-stock.category');
    }  


    public function save(Request $request){
        $request->validate([
            'name' => 'required',
            'value' => 'required'
        ]);

        if (!empty($request->id)) {
            $category = NonWovenCategory::where('id', $request->id)->first();

            $category->category_name = $request->name;
            $category->category_value = $request->value;

            if ($category->save()) {
                return back()->with('success', 'Category Updated Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        }
        else{
            $category = new NonWovenCategory();

            $category->category_name = $request->name;
            $category->category_value = $request->value;

            if ($category->save()) {
                return back()->with('success', 'Category added Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        }
    }

    public function remove(Request $request, $id)
    {
        $category = NonWovenCategory::firstwhere('id', $request->id);

        $item = NonWovenItem::where('non_color', $category->category_value)->get();
        if (count($item) > 0) {
            return redirect()->back()->with('error', "This Category is used in other Table. Can't delete");
        }
        dd($category, $item, 'hi'); exit;

        if ($category->delete()) {
            return back()->with('success', 'Non Woven Category deleted Suuccessfully !!');
        } else {
            return back()->with('error', 'Something went wrong !!');
        }
    }

    public function multidelete(Request $request){
        $selectedIds = $request->input('selected_roles');
        // print_r($selectedIds); exit;
        if (!empty($selectedIds)) {
            NonWovenCategory::whereIn('id', $selectedIds)->delete();
            return response()->json(['success' => true, 'message' => 'Selected Categories deleted successfully.']);
        }
    }
}
