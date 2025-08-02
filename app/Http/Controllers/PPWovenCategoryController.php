<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PPWovenCategory;
use App\Models\PPWovenItem;



use Carbon\Carbon;
use DataTables;

class PPWovenCategoryController extends Controller
{
    public function index(Request $request)
    {                
        if ($request->ajax()) {
            $query = PPWovenCategory::query();

            $data = $query->orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()                
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                })                
                ->addColumn('action', function ($row) {
                $action = '';

                if (hasPermission('PP Woven Fabric stock Categories Update', 'Update') || hasPermission('PP Woven Fabric stock Categories Delete', 'Delete')) {
                    $action .= '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">';

                    // Edit Button
                    if (hasPermission('PP Woven Fabric stock Categories Update', 'Update')) {
                        $action .= '<a href="#" onclick="editRole(this)" 
                                        data-id="' . $row->id . '" 
                                        data-name="' . $row->category_name . '" 
                                        data-value="' . $row->category_value . '" 
                                        class="btn-sm" title="Edit this Category">
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="#006db5" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>';
                    }

                    // Delete Button
                    if (hasPermission('PP Woven Fabric stock Categories Delete', 'Delete')) {
                        $action .= '<a href="#" data-url="' . route('admin.PPWovenCategory.remove', $row->id) . '" 
                                        data-id="' . $row->id . '" 
                                        class="btn-sm delete-button" 
                                        title="Delete this Category">
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="#ef4444" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4
                                                a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                            </path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </a>';
                    }

                    $action .= '</div>'; // Close flex container
                } else {
                    $action = '--';
                }

                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);

        }
        return view('admin.pages.pp-woven-fabric-stock.category');
    }  


    public function save(Request $request){
        $request->validate([
            'name' => 'required',
            'value' => 'required'
        ]);
        

        if (!empty($request->id)) {
            $category = PPWovenCategory::where('id', $request->id)->first();            

            $category->category_name = $request->name;
            $category->category_value = $request->value;

            if ($category->save()) {
                return back()->with('success', 'Category Updated Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        }
        else{
            $category = new PPWovenCategory();

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
        $category = PPWovenCategory::firstwhere('id', $request->id);
        $items = PPWovenItem::where('pp_category', $category->category_value)->get();

        if (count($items) > 0) {
            return redirect()->back()->with('error', "This Category is used in other Table. Can't delete");
        }

        if ($category->delete()) {
            return back()->with('success', 'PP Woven Category deleted Suuccessfully !!');
        } else {
            return back()->with('error', 'Something went wrong !!');
        }
    }

    public function multidelete(Request $request){
        $selectedIds = $request->input('selected_roles');        
        if (!empty($selectedIds)) {
            PPWovenCategory::whereIn('id', $selectedIds)->delete();
            return response()->json(['success' => true, 'message' => 'Selected Categories deleted successfully.']);
        }
    }
}
