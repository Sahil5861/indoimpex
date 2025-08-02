<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MainUser;
use App\Models\Role;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\StreamedResponse;
use DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class MainUserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MainUser::where('type', '!=', 'Super Admin')->with('role')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<div class="dropdown">
                                <a href="#" class="text-body" data-bs-toggle="dropdown">
                                    <i class="ph-list"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="#" onclick="editUser(this)" data-id="'.$row->id.'" data-email="'.$row->email.'" data-first_name="'.$row->first_name.'" data-last_name="'.$row->last_name.'" data-username="'.$row->username.'" data-type="'.$row->type.'" class="dropdown-item">
                                        <i class="ph-pencil me-2"></i>Edit
                                    </a>
                                    <form action="' . route('admin.mainUsers.delete', $row->id) . '" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this user?\')">
                                        ' . csrf_field() . '                                        
                                        <button type="submit" class="dropdown-item">
                                            <i class="ph-trash me-2"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </div>';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : 'N/A';
                })
                ->make(true);
        }

        $roles = Role::all();

        return view('admin.pages.main_users.index', compact('roles'));
    }

    public function store(Request $request)
    {
        
        $request->validate([            
            'username' => 'required|string|max:255',            
            'first_name' => 'required|string|max:255',            
            'last_name' => 'required|string|max:255',            
            'email' => 'required|string|email|max:255|unique:users',            
            'role' => 'required',
            'password' => $request->id ? 'nullable|string|min:8' : 'required|string|min:8',
        ]);
                
        $hashedPassword = Hash::make($request->password);
        if (!empty($request->id)) {
            
            $user = MainUser::find($request->id);

            

            $data = [
                'username' => $request->username,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'type' => $request->role,
            ];

            if ($request->filled('password')) {
                $data['hashed_password'] = Hash::make($request->password);
            }
        
            $user->update($data);

            return redirect()->route('admin.mainUsers')->with('success', 'User updated successfully.');
        }
        else{

            MainUser::create([
                'username' => $request->username,     
                'first_name' => $request->first_name,       
                'last_name' => $request->last_name,       
                'email' => $request->email,
                'hashed_password' => $hashedPassword,
                'type' => $request->role,
            ]);
    
            return redirect()->route('admin.mainUsers')->with('success', 'User created successfully.');

        }

    }
   
    public function remove(Request $request, $id)
    {
        $user = MainUser::findOrFail($id);

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

    public function deleteSelected(Request $request)
    {
        $selectedUsers = $request->input('selected_users');
        if (!empty($selectedUsers)) {
            User::whereIn('id', $selectedUsers)->delete();
            return response()->json(['success' => true, 'message' => 'Selected users deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'No users selected for deletion.']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        if (($handle = fopen($path, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ','); // Skip the header row

            // Fetch all roles and map role names to IDs
            $roles = Role::pluck('id', 'name')->toArray();

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // Validate data
                if (count($data) < 6) {
                    \Log::warning('Skipping row with insufficient columns:', $data);
                    continue;
                }

                // Map role name to role ID
                $roleName = $data[4];
                $roleId = $roles[$roleName] ?? null;

                // Check if role_id is valid
                if (!$roleId) {
                    \Log::warning('Invalid role name:', $roleName);
                    continue;
                }

                // Handle date conversion with error handling
                try {
                    $createdAt = \Carbon\Carbon::createFromFormat('d-M-y', $data[5])->format('Y-m-d');
                } catch (\Exception $e) {
                    \Log::error('Date format error:', ['data' => $data, 'exception' => $e->getMessage()]);
                    $createdAt = null; // or use a default date
                }

                // Create or update the user
                User::updateOrCreate(
                    ['email' => $data[2]], // Assuming email is unique
                    [
                        'name' => $data[1],
                        'phone' => $data[3],
                        'role_id' => $roleId,
                        'created_at' => $createdAt,
                        'password' => Hash::make('defaultpassword'), // Provide a default password
                    ]
                );
            }

            fclose($handle);
        }

        return redirect()->route('admin.user')->with('success', 'Users imported successfully.');
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

}
