<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PPWovenItem;
use App\Models\PPWovenCategory;


use Carbon\Carbon;
use DataTables;

class PPWovenItemController extends Controller
{
    public function index(Request $request)
    {                
        if ($request->ajax()) {
            $query = PPWovenItem::query();

            $data = $query->orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()                
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                })                
                ->addColumn('action', function ($row) {
                    // return '<div class="dropdown">
                    //                 <a href="#" class="text-body" data-bs-toggle="dropdown">
                    //                     <i class="ph-list"></i>
                    //                 </a>
                    //                 <div class="dropdown-menu dropdown-menu-end">
                    //                     <a href="#" onclick="editRole(this)" data-id="'.$row->id.'" data-item_code="'.$row->item_code.'" data-pp_size="'.$row->pp_size.'"  data-pp_category="'.$row->pp_category.'" data-pp_gms="'.$row->pp_gms.'" class="dropdown-item">
                    //                         <i class="ph-pencil me-2"></i>Edit
                    //                     </a>
                    //                     <a href="' . route('admin.PPWovenItem.remove', $row->id) . '" data-id="' . $row->id . '" class="dropdown-item delete-button">
                    //                         <i class="ph-trash me-2"></i>Delete
                    //                     </a>
                    //                 </div>
                    //             </div>';

                    $action = '';
                    if (hasPermission('PP Woven Fabric stock Items Update', 'Update') || hasPermission('PP Woven Fabric stock Items Delete', 'Delete')) {
                        $action .= '<div class="dropdown">
                                    <a href="#" class="text-body" data-bs-toggle="dropdown">
                                        <i class="ph-list"></i>
                                    </a>
                                        <div class="dropdown-menu dropdown-menu-end">';
                    }
                    if (hasPermission('PP Woven Fabric stock Items Update', 'Update')) {
                        $action .= '<a href="#" onclick="editRole(this)" data-id="'.$row->id.'" data-item_code="'.$row->item_code.'" data-pp_size="'.$row->pp_size.'"  data-pp_category="'.$row->pp_category.'" data-pp_gms="'.$row->pp_gms.'" class="dropdown-item">
                                        <i class="ph-pencil me-2"></i>Edit
                                    </a>';
                    }
                    if (hasPermission('PP Woven Fabric stock Items Delete', 'Delete')) {
                        $action .= '<a href="' . route('admin.PPWovenItem.remove', $row->id) . '" data-id="' . $row->id . '" class="dropdown-item delete-button">
                                        <i class="ph-trash me-2"></i>Delete
                                    </a>';
                    }
                    $action .= '</div></div>';      
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $categories = PPWovenCategory::all();
        return view('admin.pages.pp-woven-fabric-stock.item_code', compact('categories'));
    }  


    public function save(Request $request){
        $request->validate([
            'item_code' => 'required',
            'size' => 'required',
            'category_value' => 'required',
            'gms' => 'required',
            
        ]);
        
        if (!empty($request->id)) {
            $item = PPWovenItem::where('id', $request->id)->first();

            $item->item_code = $request->item_code;
            $item->pp_size = $request->size;
            $item->pp_category = $request->category_value;
            $item->pp_gms = $request->gms;

            if ($item->save()) {
                return back()->with('success', 'Category Updated Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        }
        else{
            $item = new PPWovenItem();

            $item->item_code = $request->item_code;
            $item->pp_size = $request->size;
            $item->pp_category = $request->category_value;
            $item->pp_gms = $request->gms;
            if ($item->save()) {
                return back()->with('success', 'Category added Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        }
    }

    public function remove(Request $request, $id)
    {
        $category = PPWovenItem::firstwhere('id', $request->id);

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
            PPWovenItem::whereIn('id', $selectedIds)->delete();
            return response()->json(['success' => true, 'message' => 'Selected Categories deleted successfully.']);
        }
    }
}
