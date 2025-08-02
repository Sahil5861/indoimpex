<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PPCategory;

use Carbon\Carbon;
use DataTables;

class BoppCategoryController extends Controller
{
    public function index(Request $request)
    {                
        if ($request->ajax()) {
            $query = PPCategory::query();

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
                                        <a href="' . route('admin.bopp-stock-pp-categories.remove', $row->id) . '" data-id="' . $row->id . '" class="dropdown-item delete-button">
                                            <i class="ph-trash me-2"></i>Delete
                                        </a>
                                    </div>
                                </div>';
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
                return redirect()->route('admin.bopp-stock-pp-categories')->with('success', 'Category Updated Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        }
        else{
            $category = new PPCategory();

            $category->category_name = $request->name;
            $category->category_value = $request->value;

            if ($category->save()) {
                return redirect()->route('admin.bopp-stock-pp-categories')->with('success', 'Category added Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        }
    }

    public function remove(Request $request, $id)
    {
        $category = PPCategory::firstwhere('id', $request->id);

        if ($category->delete()) {
            return back()->with('success', 'PPCategory deleted Suuccessfully !!');
        } else {
            return back()->with('error', 'Something went wrong !!');
        }
    }
}
