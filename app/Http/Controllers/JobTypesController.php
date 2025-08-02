<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobTypes;
use App\Models\JobDetails;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\StreamedResponse;
use DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class JobTypesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = JobTypes::query();

            $data = $query->orderBy('created_at', 'desc');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
    $action = '';

    if (hasPermission('Job Type Update', 'Update') || hasPermission('Job Type Delete', 'Delete')) {
        $action .= '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">';

        // Edit button
        if (hasPermission('Job Type Update', 'Update')) {
            $action .= '<a href="#" onclick="editUser(this)" 
                            data-id="' . $row->id . '" 
                            data-type="' . $row->job_type . '" 
                            data-value="' . $row->type_value . '" 
                            class="btn-sm" title="Edit Job Type">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="#006db5" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </a>';
        }

        // Delete button
        if (hasPermission('Job Type Delete', 'Delete')) {
            $action .= '<a href="#" 
                            data-url="' . route('jobtypes.remove', $row->id) . '" 
                            data-id="' . $row->id . '" 
                            class="btn-sm delete-button" 
                            title="Delete Job Type">
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

        $action .= '</div>'; // close flex container
    } else {
        $action = '--';
    }

    return $action;
})
->addColumn('created_at', function ($row) {
    return $row->created_at ? $row->created_at->format('d M Y') : 'N/A';
})
->rawColumns(['action'])
->make(true);

        }

        $query = JobTypes::query();

        $data = $query->orderBy('id')->get();

        return view('admin.pages.jobtypes.index', compact('data'));
    }

    public function save(Request $request)
    {
        
        $request->validate([            
            'job_type' => 'required|string|max:255',                        
            'type_value' => 'required|string|max:255'                        
        ]);
        
        if (!empty($request->id)) {
            
            $type = JobTypes::find($request->id);
                        
            $type->job_type = $request->input('job_type');
            $type->type_value = $request->input('type_value');
            if ($type->save()) {                
                return redirect()->route('jobtypes.view')->with('success', 'Job type updated successfully.');
            }
            else{
                return redirect()->route('jobtypes.view')->with('error', 'Job type Not updated successfully.');
            }
        }
        else{
            $type = new JobTypes();
            $type->job_type = $request->input('job_type');
            $type->type_value = $request->input('type_value');

            if ($type->save()) {                             
                return redirect()->route('jobtypes.view')->with('success', 'Job type added successfully.');
            }
            else{
                return redirect()->route('jobtypes.view')->with('error', 'Job type Not added successfully.');
            }

        }

    }

    public function remove(Request $request, $id)
    {
        $jobtype = JobTypes::findOrFail($id);        

        $jobs = JobDetails::where('job_type_id', $id)->get();
        if (count($jobs) > 0) {            
            return redirect()->back()->with('error', "This Job type is used in other  Tables. Can't delete");
        }        
        if ($jobtype->delete()) {
            return back()->with('success', 'JobTypes deleted successfully.');
        } else {
            return back()->with('errors', 'Something went wrong.');
        }
    }

    
    public function multidelete(Request $request)
    {
        $selectedIds = $request->input('selected_roles');        
        if (!empty($selectedIds)) {
            JobTypes::whereIn('id', $selectedIds)->delete();
            return response()->json(['success' => true, 'message' => 'Selected Job Types deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'No users selected for deletion.']);
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
