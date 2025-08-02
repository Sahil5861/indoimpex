<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NonWovenItem;
use App\Models\JobFabric;
use App\Models\NonWovenCategory;
use DataTables;

class NonWovenItemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = NonWovenItem::query();

            $data = $query->orderBy('created_at', 'desc')->get();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('non_color', function ($row) {
                    return $row->category->category_name ?? '-';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : '';
                })
                ->addColumn('action', function ($row) {
                    $action = '';
                    if (hasPermission('Non Woven Fabric Items Update', 'Update') || hasPermission('Non Woven Fabric Items Delete', 'Delete')) {
                        $action .= '<div class="dropdown">
                                    <a href="#" class="text-body" data-bs-toggle="dropdown">
                                        <i class="ph-list"></i>
                                    </a>
                                        <div class="dropdown-menu dropdown-menu-end">';
                    }
                    if (hasPermission('Non Woven Fabric Items Update', 'Update')) {
                        $action .= '<a href="#" onclick="editRole(this)"
                                        data-id="'.$row->id.'"
                                        data-item_code="'.$row->item_code.'"
                                        data-non_size="'.$row->non_size.'"
                                        data-non_color="'.$row->non_color.'"
                                        data-non_gsm="'.$row->non_gsm.'"
                                        class="dropdown-item">
                                        <i class="ph-pencil me-2"></i>Edit
                                    </a>';
                    }
                    if (hasPermission('Non Woven Fabric Items Delete', 'Delete')) {
                        $action .= '<a href="' . route('non-wovenfabricstock.items.delete', $row->id) . '" data-id="' . $row->id . '" class="dropdown-item delete-button">
                                        <i class="ph-trash me-2"></i>Delete
                                    </a>';
                    }
                    $action .= '</div></div>';      
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $categories = NonWovenCategory::all();
        return view('admin.pages.non-woven-fabric-stock.item_code', compact('categories'));
    }  

    public function save(Request $request){

        
        $request->validate([
            'item_code' => 'required',
            'size' => 'required',
            'color' => 'required',
            'gsm' => 'required'
        ]);

        if (!empty($request->id)) {
            $item = NonWovenItem::where('id', $request->id)->first();

            $item->item_code = $request->item_code;
            $item->non_size = $request->size;
            $item->non_color = $request->color;  // use dropdown value
            $item->non_gsm = $request->gsm;


            if ($item->save()) {
                return back()->with('success', 'Item Updated Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        }
        else{
            $item = new NonWovenItem();

            $item->item_code = $request->item_code;
            $item->non_size = $request->size;
            $item->non_color = $request->color;  // use dropdown value
            $item->non_gsm = $request->gsm;

            
            if ($item->save()) {
                return back()->with('success', 'Item added Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        }
    }

    public function multidelete(Request $request){
        $selectedIds = $request->input('selected_roles');
        // print_r($selectedIds); exit;
        if (!empty($selectedIds)) {
            NonWovenItem::whereIn('id', $selectedIds)->delete();
            return response()->json(['success' => true, 'message' => 'Selected Items deleted successfully.']);
        }
    }

    public function remove(Request $request, $id){
        $item = NonWovenItem::find($id);

        $job_fibre = JobFabric::where('fabric_item_code', $item->item_code)->get();    
        if (count($job_fibre) > 0) {
            return redirect()->back()->with('error', "This Item is used in other Tables. Can't delete");
        }
        dd($job_fibre,'hii'); exit;
        if ($item && $item->delete()) {
            return redirect()->route('non-wovenfabricstock.items.view')->with('success', 'Item deleted Suuccessfully !!');
        } else {
            return redirect()->route('non-wovenfabricstock.items.view')->with('error', 'Something went wrong!');
        }
    }

}
