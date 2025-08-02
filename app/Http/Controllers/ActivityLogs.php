<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\User;
use DataTables;

class ActivityLogs extends Controller
{
    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $query = Log::query()->orderby('action_date', 'desc')->with('user'); // Make sure 'user' relation is defined

    //         return DataTables::of($query)
    //             ->addIndexColumn() // For S.NO

    //             ->addColumn('user', function ($row) {
    //                 return $row->user ? $row->user->name : 'N/A';
    //             })

    //             ->addColumn('module', function ($row) {
    //                 return $row->module ?? 'N/A';
    //             })

    //             ->addColumn('action_name', function ($row) {
    //                 return $row->action ?? 'N/A';
    //             })

    //             ->addColumn('comment', function ($row) {
    //                 return $row->comments ?? '';
    //             })

    //             ->addColumn('action', function ($row) {
    //                 $actions = '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">';

    //                 $actions .= '<a href="#" data-url="'.route('admin.activitylogs.delete', $row->id).'" 
    //                                 class="btn-sm delete-button" title="Delete Log">
    //                                 <svg viewBox="0 0 24 24" width="24" height="24" stroke="#ef4444" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
    //                             </a>';                    

    //                 $actions .= '</div>';

    //                 return $actions ?: '--';
    //             })

    //             ->editColumn('action_date', function ($row) {
    //                 return \Carbon\Carbon::parse($row->action_date)->format('d F Y, g : i A');
    //             })            

    //             ->rawColumns(['checkbox', 'action']) // Render HTML safely
    //             ->make(true);
    //     }

    //     $this->data['modules'] = Log::orderBy('action_date', 'desc')->pluck('module')->unique()->values();        
    //     $userIds = Log::orderBy('action_date')->pluck('user_id')->unique()->values();
    //     $this->data['users'] = User::whereIn('id', $userIds)->get();        

    //     return view('admin.pages.activity_logs.index', $this->data);      
    // }

    public function index(Request $request)
{
    if ($request->ajax()) {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir'];
        $searchValue = $search_arr['value'];

        $mymodule = $request->input('mymodule');
        $user_id = $request->input('user_id');

        $query = Log::with('user')->orderBy('action_date', 'desc');

        if ($request->has('mymodule') && $request->input('mymodule') != '') {
            $query = $query->where('module', $mymodule);
        }

        if ($request->has('user_id') && $request->input('user_id') != '') {
            $query = $query->where('user_id', $user_id);
        }

        // Apply search filter
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('module', 'like', '%' . $searchValue . '%')
                    ->orWhere('action', 'like', '%' . $searchValue . '%')
                    ->orWhere('comments', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('user', function ($q2) use ($searchValue) {
                        $q2->where('name', 'like', '%' . $searchValue . '%');
                    });
            });
        }

        $totalRecords = Log::count();
        $totalRecordswithFilter = $query->count();

        $logs = $query->skip($start)
            ->take($rowperpage)
            ->orderBy($columnName, $columnSortOrder)
            ->get();

        $data_arr = [];
        foreach ($logs as $index => $log) {
            $actions = '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">
                            <a href="#" data-url="' . route('admin.activitylogs.delete', $log->id) . '" 
                                class="btn-sm delete-button" title="Delete Log">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="#ef4444" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                            </a>
                        </div>';

            $data_arr[] = [
                'id' => $log->id,
                'DT_RowIndex' => $start + $index + 1,
                'user' => $log->user->name ?? 'N/A',
                'module' => $log->module ?? 'N/A',
                'action_name' => $log->action ?? 'N/A',
                'comment' => $log->comments ?? '',
                'action_date' => \Carbon\Carbon::parse($log->action_date)->format('d F Y, g : i A'),
                'action' => $actions,
            ];
        }

        return response()->json([
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        ]);
    }

    $this->data['modules'] = Log::orderBy('action_date', 'desc')->pluck('module')->unique()->values();
    $userIds = Log::orderBy('action_date')->pluck('user_id')->unique()->values();
    $this->data['users'] = User::whereIn('id', $userIds)->get();

    return view('admin.pages.activity_logs.index', $this->data);
}

    public function destroy(Request $request, $id){
        $log = Log::find($id);
        $log->delete();
        return redirect()->back()->with('success', 'Log Deleted Successfully !!');
    }

}
