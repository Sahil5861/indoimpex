<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PPCategory;
use App\Models\PPItem;

use Carbon\Carbon;
use DataTables;

class BoppCategoryController extends Controller
{
    public function index(Request $request)
    {                
        if ($request->ajax()) {
            $query = PPCategory::query();

            $data = $query->orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()                
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : '';
                })                
                ->addColumn('action', function ($row) {
                    $action = '';
                    if (hasPermission('Bopp Stock Categories Update', 'Update') || hasPermission('Bopp Stock Categories Delete', 'Delete')) {
                        $action .= '<div class="dropdown">
                                    <a href="#" class="text-body" data-bs-toggle="dropdown">
                                        <i class="ph-list"></i>
                                    </a>
                                        <div class="dropdown-menu dropdown-menu-end">';
                    }
                    if (hasPermission('Bopp Stock Categories Update', 'Update')){
                        $action .= '<a href="#" onclick="editRole(this)" data-id="'.$row->id.'" data-name="'.$row->category_name.'" data-value="'.$row->category_value.'" class="dropdown-item">
                                            <i class="ph-pencil me-2"></i>Edit
                                    </a>';
                    }
                    if (hasPermission('Bopp Stock Categories Delete', 'Delete')) {
                        $action .= '<a href="' . route('admin.bopp-stock-pp-categories.remove', $row->id) . '" data-id="' . $row->id . '" class="dropdown-item delete-button">
                                            <i class="ph-trash me-2"></i>Delete
                                    </a>';
                    }
                    $action .= '</div></div>';      
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.pages.bopp_stock.index');
    }  


    public function save(Request $request){
        $request->validate([
            'name' => 'required',
            'value' => 'required'
        ]);

        if (!empty($request->id)) {
            $category = PPCategory::where('id', $request->id)->first();

            $category->category_name = $request->name;
            $category->category_value = $request->value;

            if ($category->save()) {
                return redirect()->back()->with('success', 'Category Updated Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        }
        else{
            $category = new PPCategory();

            $category->category_name = $request->name;
            $category->category_value = $request->value;

            if ($category->save()) {
                return redirect()->route('bopp-stock.categories.view')->with('success', 'Category added Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        }
    }

    public function remove(Request $request, $id)
    {
        $category = PPCategory::firstwhere('id', $request->id);

        $items = PPItem::where('bopp_category', $category->category_value)->get();
        if (count($items) > 0) {
            return redirect()->back()->with('error', "This Category is used in other Table. Can't delete");
        }            
        if ($category->delete()) {            
            return back()->with('success', 'PPCategory deleted Suuccessfully !!');
        } else {
            return back()->with('error', 'Something went wrong !!');
        }
    }

    public function multidelete(Request $request){
        $selectedIds = $request->input('selected_roles');
        // print_r($selectedIds); exit;
        if (!empty($selectedIds)) {
            PPCategory::whereIn('id', $selectedIds)->delete();
            return response()->json(['success' => true, 'message' => 'Selected Categories deleted successfully.']);
        }
    }
}
