<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\JobDetails;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\StreamedResponse;
use DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PartyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Party::query();

            $data = $query->get();
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    $action = '';
                    if (hasPermission('Party Update', 'Update') || hasPermission('Party Delete', 'Delete')) {
                        // $action = '<div class="dropdown">
                        //         <a href="#" class="text-body" data-bs-toggle="dropdown">
                        //             <i class="ph-list"></i>
                        //         </a>
                        //         <div class="dropdown-menu dropdown-menu-end">
                        //             <a href="#" onclick="editUser(this)" data-id="'.$row->id.'" data-email="'.$row->email.'" data-first_name="'.$row->first_name.'" data-last_name="'.$row->last_name.'" data-username="'.$row->username.'" data-type="'.$row->type.'" class="dropdown-item">
                        //                 <i class="ph-pencil me-2"></i>Edit
                        //             </a>                                    
                        //             <a href="' . route('admin.party.delete', $row->id) . '" data-id="' . $row->id . '" class="dropdown-item delete-button">
                        //                 <i class="ph-trash me-2"></i>Delete
                        //             </a>
                        //         </div>
                        //     </div>';
                        //     return $action;

                        $action = '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">';                        
                    }   
                    if (hasPermission('Party Update', 'Update')) {
                        $action .= '<a href="#" onclick="editUser(this)" data-id="'.$row->id.'" data-email="'.$row->email.'" data-first_name="'.$row->first_name.'" data-last_name="'.$row->last_name.'" data-username="'.$row->username.'" data-type="'.$row->type.'" class="btn-sm" title="Update Party">
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="#006db5" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>';
                    }             
                    if (hasPermission('Party Delete', 'Delete')) {
                        $action .= '<a href="#" data-url="' . route('admin.party.delete', $row->id) . '" data-id="' . $row->id . '" class="btn-sm delete-button" title="Delete Party">
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="#ef4444" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </a>';
                    }

                    if (hasPermission('Party Update', 'Update') || hasPermission('Party Delete', 'Delete')) {
                        $action .= '</div>';      
                        return $action;
                    }
                    else{
                        return '--';
                    }
                })
                ->addColumn('status', function ($row){
                    return '<label class="switch">
                                    <input type="checkbox" class="status-toggle" data-id="'.$row->id.'" '.($row->status ? 'checked' : '').'>
                                    <span class="slider round"></span>
                                </label>';
                })
                ->addColumn('party_name', function ($row){
                    return $row->party_name ? $row->party_name : '';
                })
                ->editColumn('created_at', function ($row) {
                    // return $row->created_at ? Carbon::parse($row->created_at)->format('d M Y, h:i  A') : 'N/A';
                    return $row->created_at;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        $query = Party::query();

        $data = $query->orderBy('id')->get();

        return view('admin.pages.party.index', compact('data'));
    }

    public function store(Request $request)
    {
        
        $request->validate([            
            'party_name' => 'required|string|max:255',                        
        ]);

        
                
        $hashedPassword = Hash::make($request->password);
        if (!empty($request->id)) {
            
            $party = Party::find($request->id);
                        
            $party->party_name = $request->input('party_name');
            $party->party_number = $request->input('contact_number') ??null;
            $party->party_email = $request->input('email') ?? null;
            $party->party_gst = $request->input('gst') ?? null;
            $party->party_address = $request->input('address') ?? null;

            if ($party->save()) {                
                return redirect()->route('admin.party')->with('success', 'User updated successfully.');
            }
            else{
                return redirect()->route('admin.party')->with('error', 'User Not updated successfully.');
            }
        }
        else{

            $party = new Party();

            $party->party_name = $request->input('party_name');
            $party->party_number = $request->input('contact_number') ?? null;
            $party->party_email = $request->input('email') ?? null;
            $party->party_gst = $request->input('gst') ?? null;
            $party->party_address = $request->input('address') ?? null;

            if ($party->save()) {                
                return redirect()->route('admin.party')->with('success', 'User added successfully.');
            }
            else{
                return redirect()->route('admin.party')->with('error', 'User Not added successfully.');
            }

        }

    }

    public function getData(Request $request)
    {
        $id = $request->id;        
        $party = Party::findOrFail($id);
        return response()->json($party);
    }
   
    public function remove(Request $request, $id)
    {
        $party = Party::findOrFail($id);

        $jobs = JobDetails::where('party_id', $id)->get();
        if (count($jobs) > 0) {            
            return redirect()->back()->with('error', "This Party is used in other  Tables. Can't delete");
        }   
            
        if ($party->delete()) {
            return back()->with('success', 'Party deleted successfully.');
        } else {
            return back()->with('error', 'Something went wrong.');
        }
    }

    public function updateStatus($id, Request $request)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $party = Party::findOrFail($id);
        if ($party) {
            $party->status = $request->status;
            $party->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function deleteSelected(Request $request)
    {
        $selectedUsers = $request->input('selected_users');
        if (!empty($selectedUsers)) {
            User::whereIn('id', $selectedUsers)->delete();
            return response()->json(['success' => true, 'message' => 'Selected users deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'No users selected for deletion.']);
    }
}
