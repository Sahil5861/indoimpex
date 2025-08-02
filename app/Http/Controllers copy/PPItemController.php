<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PPCategory;
use App\Models\PPItem;

use Carbon\Carbon;
use DataTables;

class BoppItemController extends Controller
{
    public function index(Request $request)
    {                
        if ($request->ajax()) {
            $query = PPItem::query();

            $data = $query->orderBy('id')->get();

            return DataTables::of($data)
                ->addIndexColumn()                
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : '';
                })                
                ->addColumn('action', function ($row) {
                    return '<div class="dropdown">
                                    <a href="#" class="text-body" data-bs-toggle="dropdown">
                                        <i class="ph-list"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="#" onclick="editRole(this)" data-id="'.$row->id.'" data-item_code="'.$row->item_code.'" data-pp_size="'.$row->pp_size.'" data-pp_category="'.$row->pp_category.'" data-pp_gms="'.$row->pp_gms.'" class="dropdown-item">
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

        $categories = PPCategory::all();
        return view('admin.pages.bopp_stock.item_code', compact('categories'));
    }  


    public function save(Request $request){

        
        $request->validate([
            'item_code' => 'required',
            'size' => 'required',
            'category_value' => 'required',
            'microns' => 'required'
        ]);

        
        
        if (!empty($request->id)) {
            $item = PPItem::where('id', $request->id)->first();

            $item->item_code = $request->item_code;
            $item->pp_size = $request->size;
            $item->pp_category = $request->category_value;
            $item->pp_gms = $request->microns;

            if ($item->save()) {
                return redirect()->route('admin.bopp-stock-pp-item')->with('success', 'Item Updated Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        }
        else{
            $item = new PPItem();

            $item->item_code = $request->item_code;
            $item->pp_size = $request->size;
            $item->pp_category = $request->category_value;
            $item->pp_gms = $request->microns;
            
            if ($item->save()) {
                return redirect()->route('admin.bopp-stock-pp-item')->with('success', 'Item added Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        }
    }
}
