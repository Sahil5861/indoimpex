<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PPCategory;
use App\Models\PPItem;
use App\Models\JobBopp;

use Carbon\Carbon;
use DataTables;

class BoppItemController extends Controller
{
    public function index(Request $request)
    {                
        if ($request->ajax()) {
            $query = PPItem::query();

            $data = $query->orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()                          
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : '';
                })                
                ->addColumn('bopp_category', function ($row){
                    $category = PPCategory::find($row->bopp_category);
                    return $category ? $category->category_value : $row->bopp_category;
                })      
                ->addColumn('action', function ($row) {

                    $action = '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">';                    
                    if (hasPermission('Bopp Stock Items Update', 'Update')) {
                        // $action .= '<a href="#" onclick="editRole(this)" 
                        //                 data-id="'.$row->id.'" 
                        //                 data-item_code="'.$row->item_code.'" 
                        //                 data-pp_size="'.$row->bopp_size.'" 
                        //                 data-pp_category="'.$row->bopp_category.'" 
                        //                 data-pp_gms="'.$row->bopp_micron.'" class="dropdown-item">
                        //                 <i class="ph-pencil me-2"></i>Edit
                        //             </a>';
                        $action .= '<a href="#" onclick="editRole(this)" 
                                    data-id="'.$row->id.'"
                                    data-item_code="'.$row->item_code.'" 
                                    data-pp_size="'.$row->bopp_size.'" 
                                    data-pp_category="'.$row->bopp_category.'" 
                                    data-pp_gms="'.$row->bopp_micron.'"
                                     class="btn-sm" title="Update this Item">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="#006db5" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </a>';
                    }

                    if (hasPermission('Bopp Stock Items Delete', 'Delete')) {                        
                        $action .= '<a href="#" data-url="' . route('admin.bopp-stock-pp-item.remove', $row->id) . '" data-id="' . $row->id . '" class="btn-sm delete-button" title="Delete this Job">
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="#ef4444" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </a> ';
                    }


                    if (hasPermission('Bopp Stock Items Update', 'Update')|| hasPermission('Bopp Stock Items Delete', 'Delete')) {
                        return $action;
                    }
                    else{
                        return '--';
                    }
                })
                ->rawColumns(['action', 'bopp_category'])
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
            $item->bopp_size = $request->size;
            $item->bopp_category = $request->category_value;
            $item->bopp_micron = $request->microns;

            if ($item->save()) {
                return redirect()->back()->with('success', 'Item Updated Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        }
        else{
            $item = new PPItem();

            $item->item_code = $request->item_code;
            $item->bopp_size = $request->size;
            $item->bopp_category = $request->category_value;
            $item->bopp_micron = $request->microns;
            
            if ($item->save()) {
                return redirect()->route('boppstock.items.view')->with('success', 'Item added Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        }
    }

    public function remove(Request $request, $id)
    {
        $item = PPItem::find($id);

        $job_bopp = JobBopp::where('bopp_item_code', $item->item_code)->get();

        if (count($job_bopp) > 0) {
            return redirect()->back()->with('error', "This Item is used in other Tables. Can't delete");
        }        
        if ($item && $item->delete()) {
            return redirect()->route('boppstock.items.view')->with('success', 'Item deleted Suuccessfully !!');
        } else {
            return redirect()->route('boppstock.items.view')->with('error', 'Something went wrong!');
        }
    }

    public function multidelete(Request $request){
        $selectedIds = $request->input('selected_roles');        
        if (!empty($selectedIds)) {
            PPItem::whereIn('id', $selectedIds)->delete();
            return response()->json(['success' => true, 'message' => 'Selected Items deleted successfully.']);
        }
    }
}
