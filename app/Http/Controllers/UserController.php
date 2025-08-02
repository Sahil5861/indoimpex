<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\StreamedResponse;
use DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{

    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('role')->latest()->get();
            
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                $checked = $row->status == 1 ? 'checked' : '';
                return '
                    <label class="switch">
                        <input type="checkbox" class="toggle-status" data-id="'.$row->id.'" '.$checked.'>
                        <span class="slider round"></span>
                    </label>
                ';
            })
            ->addColumn('action', function ($row) {
                $action2 = '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">';
                if (hasPermission('Users Update', 'Update')){                        

                    $action2 .= '<a href="#" onclick="editUser(\''.$row->id.'\');" data-id="'.$row->id.'" class="btn-sm" title="Update this User">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="#006db5" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </a>';
                }
                if (hasPermission('Users Delete', 'Delete')){
                    $action2 .= '<a href="#" data-url="' . route('admin.user.delete', $row->id) . '" data-id="' . $row->id . '" class="btn-sm delete-button" title="Delete this User">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="#ef4444" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                </a>';
                }
                
                $action2 .= '<a href="#" data-id="' . $row->id . '" class="btn-sm password-update" title="Update User Password" onclick="updatePassword(this);">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="#6366f1" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>
                            </a>';                                                    
                // $action2 .= '<a href="#" data-id="' . $row->id . '" class="btn-sm password-update" title="View User" onclick="updatePassword(this);">
                //                     <svg viewBox="0 0 24 24" width="24" height="24" stroke="#059669" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                //             </a>';
                
                if (hasPermission('Users Update', 'Update') || hasPermission('Users Delete', 'Delete')) {                        
                    $action2 .= '</div>';      
                    return $action2;
                }
                else{
                    return '---';
                }
            })
            ->addColumn('role', function ($row) {
                return $row->role->role_name ?? 'N/A';
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d M Y') : 'N/A';
            })
            ->rawColumns(['status', 'action']) // 👈 this line is critical
            ->make(true);

        }

        $roles = Role::all();

        return view('admin.users.index', compact('roles'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'sometimes',
            'email' => 'required|string|email|max:255',
            'password' => 'nullable',
            'role_id' => 'required|exists:roles,id',
        ]);

        // print_r($request->all()); exit;

        if (!empty($request->id) && $request->id != '') {
            $user = User::where('id', $request->id)->first();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone ?? '';
            $user->role_id = $request->role_id;
            if ($user->save()) {
                return redirect()->route('admin.users')->with('success', 'User updated successfully.');
            }
        }
        else{
            $hashedPassword = Hash::make($request->password);
            User::create([
    
                'name' => $request->name,
                'phone' => $request->phone ?? '',
                'email' => $request->email,
                'password' => $hashedPassword,
                'role_id' => $request->role_id,
            ]);
        }


        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function getUser(Request $request){
        $id = $request->id;
        $user = User::find($id);
        if ($user) {
            return response()->json([
                'status' => true,
                'user' => $user
            ]);
        }
        else{
            return response()->json([
                'status' => true,
                'message'=> 'No User Found !!'            
            ]);
        }
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:10|min:10',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->role_id = $request->role_id;
        $user->save();

        return redirect()->route('admin.user')
            ->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.user')->with('success', 'User deleted successfully.');
    }

    public function create(){
        $roles = Role::where('name', '!=', 'dealer')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function edit($id){
        $user = User::findOrFail($id);
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function remove(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->delete()) {
            return back()->with('success', 'User deleted successfully.');
        } else {
            return back()->with('error', 'Something went wrong.');
        }
    }

    public function updateStatus($id, Request $request)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $user = User::findOrFail($id);
        if ($user) {
            $user->status = $request->status;
            $user->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function deleteSelected(Request $request){
        $selectedUsers = $request->input('selected_users');
        if (!empty($selectedUsers)) {
            User::whereIn('id', $selectedUsers)->delete();
            return response()->json(['success' => true, 'message' => 'Selected users deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'No users selected for deletion.']);
    }

    public function export(Request $request)
    {
        try {
            $status = $request->query('status', null); // Get status from query parameters

            $response = new StreamedResponse(function () use ($status) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Add headers for CSV
                $sheet->fromArray(["ID", "Name", "Email", "Phone", "Role", "Created At"], null, 'A1');

                // Fetch users based on status
                $query = User::query();
                if ($status !== null) {
                    $query->where('status', $status);
                }
                $users = $query->get();
                $usersData = [];
                foreach ($users as $user) {
                    $role = $user->role->name ?? 'N/A'; // Adjust as per your role relationship

                    // Check if created_at is null before formatting
                    $createdAt = $user->created_at ? $user->created_at->format('d-M-y') : 'N/A';
                    $usersData[] = [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->phone,
                        $role,
                        $createdAt,
                    ];
                }
                $sheet->fromArray($usersData, null, 'A2');

                // Write CSV to output
                $writer = new Csv($spreadsheet);
                $writer->setUseBOM(true);
                $writer->save('php://output');
            });

            // Set headers for response
            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename="users.csv"');

            return $response;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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

    public function updatePassword(Request $request){
        // dd($request->all()); exit;
        $request->validate([
            'password1' => 'required',
            'confirm_password' => 'required|same:password1',
        ]);

        $user = User::where('id', $request->user_id)->first();
        $user->password = Hash::make($request->password1);
        $user->save();

        DB::table('sessions')->where('user_id', $user->id)->delete();        
        return redirect()->route('admin.users')->with('success', 'Password Updated successfully.');
    }


    public function changeStatus(Request $request)
    {
        $user = User::find($request->id);
        ActivityLogger::log('User', 'change Passoword', 'Status changed for the user with Name ' . $user->name );
        if ($user) {
            $user->status = $request->status;
            $user->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }


}
