<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobNames;
use App\Models\JobDetails;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\StreamedResponse;
use DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class JobNamesController extends Controller
{
    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $query = JobNames::query();

    //         $data = $query->orderBy('id')->get();
    //         return DataTables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('job_name', function ($row) {
    //                 return $row->job_name ?? '-';
    //             })
    //             ->addColumn('action', function ($row) {

    //                 return '<div class="dropdown">
    //                             <a href="#" class="text-body" data-bs-toggle="dropdown">
    //                                 <i class="ph-list"></i>
    //                             </a>
    //                             <div class="dropdown-menu dropdown-menu-end">
    //                                 <a href="#" onclick="editUser(this)" data-id="'.$row->id.'" data-name="'.$row->job_name.'" class="dropdown-item">
    //                                     <i class="ph-pencil me-2"></i>Edit
    //                                 </a>                                    
    //                                 <a href="' . route('jobnames.remove', $row->id) . '" data-id="' . $row->id . '" class="dropdown-item delete-button">
    //                                     <i class="ph-trash me-2"></i>Delete
    //                                 </a>
    //                             </div>
    //                         </div>';
    //             })                                
    //             ->addColumn('created_at', function ($row) {
    //                 return $row->created_at ? $row->created_at->format('d M Y') : 'N/A';
    //             })
    //             ->make(true);
    //     }

    //     // $query = JobNames::query();
    //     // $data = $query->orderBy('id')->get();
    //     return view('admin.pages.jobnames.index', compact('data'));
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
            $columnName = $columnName_arr[$columnIndex]['name'];
            $columnSortOrder = $order_arr[0]['dir'];
            $searchValue = $search_arr['value'];

            // Total records
            $totalRecords = JobNames::count();

            // Total filtered
            $totalRecordswithFilter = JobNames::where('job_name', 'like', '%' . $searchValue . '%')->count();

            // Fetch data with pagination, search and sorting
            $records = JobNames::where('job_name', 'like', '%' . $searchValue . '%')
                ->orderBy('created_at', 'desc')
                ->skip($start)
                ->take($rowperpage)
                ->get();

            $data_arr = [];
            $index = $start + 1;

            foreach ($records as $record) {
                $action = '';
                if(hasPermission('Job Name Update', 'Update') || hasPermission('Job Name Delete', 'Delete')){
                    $action .= '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">';
                }
                if (hasPermission('Job Name Update', 'Update')) {
                    $action .= '<a href="#" onclick="editUser(this)" data-id="'.$record->id.'" data-name="'.$record->job_name.'" data-status="'.$record->status.'" class="btn btn-sm btn-primary-2">
                                    <i class="ph-pencil me-2"></i>Edit
                                </a>';
                }
                if (hasPermission('Job Name Delete', 'Delete')) {
                    $action .= '<a href="' . route('jobnames.remove', $record->id) . '" data-id="' . $record->id . '" class="btn btn-sm btn-danger delete-button">
                                            <i class="ph-trash me-2"></i>Delete
                                        </a>';
                }

                
                if(hasPermission('Job Name Update', 'Update') || hasPermission('Job Name Delete', 'Delete')){
                    $action .= '</div></div>';      
                }
                else{
                    $action = '-';
                }
                $data_arr[] = [
                    "DT_RowIndex" => $index++,
                    "id" => $record->id,
                    "job_name" => $record->job_name ?? '-',
                    "status" => '<label class="switch">
                                    <input type="checkbox" class="status-toggle" data-id="'.$record->id.'" '.($record->status ? 'checked' : '').'>
                                    <span class="slider round"></span>
                                </label>',

                    "created_at" => $record->created_at ? $record->created_at->format('d M Y') : 'N/A',
                    "action" => $action,
                ];
            }

            return response()->json([
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $data_arr,
            ]);
        }

        return view('admin.pages.jobnames.index');
    }



    public function save(Request $request)
    {
        
        $request->validate([            
            'job_name' => 'required|string|max:255',                                    
        ]);
        
        if (!empty($request->id)) {
            
            $name = JobNames::find($request->id);
                        
            $name->job_name = $request->input('job_name');            
            $name->status = $request->input('status');            
            if ($name->save()) {                
                return redirect()->route('jobnames.view')->with('success', 'Job name updated successfully.');
            }
            else{
                return redirect()->route('jobnames.view')->with('error', 'Job name Not updated successfully.');
            }
        }
        else{
            $name = new JobNames();
            $name->job_name = $request->input('job_name');

            if ($name->save()) {                             
                return redirect()->route('jobnames.view')->with('success', 'Job name added successfully.');
            }
            else{
                return redirect()->route('jobnames.view')->with('error', 'Job name Not added successfully.');
            }

        }

    }

    public function remove(Request $request, $id)
    {
        $jobname = JobNames::findOrFail($id);
        $jobs = JobDetails::where('job_name_id', $id)->get();
        if (count($jobs) > 0) {               
            return redirect()->back()->with('error', "This Job name is used in other  Tables. Can't delete");
        }  
                
        if ($jobname->delete()) {
            return back()->with('success', 'JobName deleted successfully.');
        } else {
            return back()->with('error', 'Something went wrong.');
        }
    }

    
    public function multidelete(Request $request)
    {
        $selectedIds = $request->input('selected_roles');        
        if (!empty($selectedIds)) {
            JobNames::whereIn('id', $selectedIds)->delete();
            return response()->json(['success' => true, 'message' => 'Selected Job Names deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'No users selected for deletion.']);
    }

    public function updateStatus(Request $request, $id){
        $jobname = JobNames::where('id', $id)->first();
        $jobname->status = $request->status;
        if ($jobname->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Status updated'
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong !'
            ]);
        }
        

    }
    

    public function sampleFileDownloadUser()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="user_csv_sample.csv"',
        ];

        $columns = ['Id','Name', 'Email', 'Phone', 'Role', 'Created At'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
