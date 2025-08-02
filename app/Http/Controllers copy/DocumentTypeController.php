<?php

namespace App\Http\Controllers;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use App\Models\DocumentType;


use DataTables;
class DocumentTypeController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $query = DocumentType::query();

            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            $data = $query->orderBy('id')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $checked = $row->status == '1' ? 'checked' : '';
                    $text = $checked ? 'Active' : 'Inactive';
                    return '<label class="switch">
                                    <input type="checkbox" class="status-checkbox status-toggle" data-id="' . $row->id . '" ' . $checked . '>
                                    <span class="slider round status-text"></span>
                            </label>';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                })
                ->addColumn('action', function ($row) {
                    return '<div class="dropdown">
                                    <a href="#" class="text-body" data-bs-toggle="dropdown">
                                        <i class="ph-list"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="' . route('admin.document_type.edit', $row->id) . '" class="dropdown-item">
                                            <i class="ph-pencil me-2"></i>Edit
                                        </a>
                                        <a href="' . route('admin.document_type.delete', $row->id) . '" data-id="' . $row->id . '" class="dropdown-item delete-button">
                                            <i class="ph-trash me-2"></i>Delete
                                        </a>
                                    </div>
                                </div>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.pages.document_type.index');
    }

    public function create()
    {
        return view('admin.pages.document_type.create');
    }

    public function edit($id){
        $doc_type = DocumentType::whereNull('deleted_at')->find($id);
        return view('admin.pages.document_type.edit', compact('doc_type'));
    }
    
    
    public function store(Request $request){

        $validate = $request->validate([
            'name' => 'required',
            'vars' => 'required',
        ]);
        if (!empty($request->id)) {
            $doc_type = DocumentType::firstwhere('id', $request->id);
            $doc_type->type = $request->input('name');
            $doc_type->variables = $request->input('vars');


            if ($doc_type->save()) {
                return redirect()->route('admin.document_type')->with('success', 'Document Type' . $request->id . ' Updated Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        } else {
            $doc_type = new DocumentType();
            $doc_type->type = $request->input('name');
            $doc_type->variables = $request->input('vars');

            if ($doc_type->save()) {
                return redirect()->route('admin.document_type')->with('success', 'Document Type and added Suuccessfully !!');
            } else {
                return back()->with('error', 'Something went wrong !!');
            }
        }
    } 
    
    public function remove(Request $request, $id){
        $doc_type = DocumentType::firstwhere('id', $request->id);

        if ($doc_type->delete()) {
            return back()->with('success', 'Document Type deleted Suuccessfully !!');
        } else {
            return back()->with('error', 'Something went wrong !!');
        }
    } 
    
    public function updateStatus($id, Request $request){
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $doc_type = DocumentType::findOrFail($id);
        if ($doc_type) {
            $doc_type->status = $request->status;
            $doc_type->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }

    }

    public function deleteSelected(Request $request){
        $selectedDocumentType = $request->input('selected_document_type');
        if (!empty($selectedDocumentType)) {
            DocumentType::whereIn('id', $selectedDocumentType)->delete();
            return response()->json(['success' => true, 'message' => 'Selected Document Types deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'No Document Type selected for deletion.']);
    }    

    public function import(Request $request){
        $validate = $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);
        if ($validate == false) {
            return redirect()->back();
        }
        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        if (($handle = fopen($path, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ','); // Skip the header row

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                DocumentType::create([
                    'id' => $data[0],
                    'Type' => $data[1],
                    'Vars' => $data[2],
                ]);
            }

            fclose($handle);
        }

        return redirect()->route('admin.brand')->with('success', 'Brands imported successfully.');

    }
    public function export(Request $request){
        try {
            $status = $request->query('status', null); // Get status from query parameters

            $response = new StreamedResponse(function () use ($status) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Add headers for CSV
                $sheet->fromArray(['ID', 'Type', 'Variables', 'Created At', 'Status'], null, 'A1');

                // Fetch brands based on status
                $query = DocumentType::query();
                if ($status !== null) {
                    $query->where('status', $status);
                }
                $doc_types = $query->get();
                $doc_typesData = [];
                foreach ($doc_types as $doc_type) {
                    $doc_typesData[] = [
                        $doc_type->id,
                        $doc_type->type,
                        $doc_type->variables,
                        $doc_type->created_at->format('d M Y'),
                        $doc_type->status == 1 ? 'Active' : 'Inactive',
                    ];
                }
                $sheet->fromArray($doc_typesData, null, 'A2');

                // Write CSV to output
                $writer = new Csv($spreadsheet);
                $writer->setUseBOM(true);
                $writer->save('php://output');
            });

            // Set headers for response
            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename="DocumentTypes.csv"');

            return $response;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sampleFileDownloadDocumentTypes()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="document_type_sample.csv"',
        ];

        $columns = ['ID', 'Type', 'Variable', 'Created At', 'Status'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }    

      
}
