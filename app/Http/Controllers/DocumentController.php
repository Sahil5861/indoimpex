<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Document;
use App\Models\DocumentType;

use DataTables;
class DocumentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Document::query();

            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            $data = $query->orderBy('id')->get();
            $documentTypes = DocumentType::pluck('type', 'id')->toArray();
            return DataTables::of($data)
                // Fetch all document types
                ->addIndexColumn()
                ->addColumn('document_type', function ($row) use ($documentTypes) {
                    return $documentTypes[$row->document_type_id] ?? 'Unknown'; // Use type_id or the appropriate foreign key
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status == '1' ? 'checked' : '';
                    $text = $checked ? 'Active' : 'Inactive';
                    return '<label class="switch">
                                    <input type="checkbox" class="status-checkbox status-toggle" data-id="' . $row->id . '" ' . $checked . '>
                                    <span class="slider round status-text"></span>
                            </label>';
                })
                ->addColumn('header_image', function ($row) {
                    return $row->header_image ? asset($row->header_image) : '';
                })
                ->addColumn('footer_image', function ($row) {
                    return $row->footer_image ? asset($row->footer_image) : '';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="dropdown">
                                    <a href="#" class="text-body" data-bs-toggle="dropdown">
                                        <i class="ph-list"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="' . route('admin.document.edit', $row->id) . '" class="dropdown-item">
                                            <i class="ph-pencil me-2"></i>Edit
                                        </a>
                                        <a href="' . route('admin.document.delete', $row->id) . '" data-id="' . $row->id . '" class="dropdown-item delete-button">
                                            <i class="ph-trash me-2"></i>Delete
                                        </a>
                                    </div>
                                </div>';
                })
                ->rawColumns(['action', 'status', 'header_img', 'footer_img', 'document_type'])
                ->make(true);
        }

        return view('admin.pages.documents.index');
    }
    public function create()
    {
        $doc_types = DocumentType::all();
        return view('admin.pages.documents.create', compact('doc_types'));
    }

    public function edit($id)
    {
        $doc = Document::firstWhere('id', $id);
        $doc_types = DocumentType::all();
        return view('admin.pages.documents.edit', compact('doc', 'doc_types'));
    }
    public function store(Request $request)
    {

        $validate = $request->validate([
            'name' => 'required',
            'doctype' => 'required',
            'docfor' => 'required',
            'content' => 'required',
            'bgimage' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'header_img' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'header_img_height' => 'required',
            'header_image_type' => 'required',
            'footer_img' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'footer_img_height' => 'required',
            'footer_image_type' => 'required',
        ]);

        $doc = $request->id ? Document::firstWhere('id', $request->id) : new Document();
        $this->assignDocumentData($doc, $request);

        $doc->header_image = $this->handleImageUpload($request, 'header_img', 'uploads/doc/header', $doc->header_image);
        $doc->footer_image = $this->handleImageUpload($request, 'footer_img', 'uploads/doc/footer', $doc->footer_image);
        $doc->background_image = $this->handleImageUpload($request, 'bgimage', 'uploads/doc/bg', $doc->background_image);

        if ($doc->save()) {
            return redirect()->route('admin.document')->with('success', 'Document Added Successfully !!');
        } else {
            return back()->with('error', 'Something went wrong !!');
        }


    }

    public function assignDocumentData(Document $doc, Request $request)
    {
        $doc->document_name = $request->name;
        $doc->document_for = $request->docfor;
        $doc->header_image_height = (float) $request->header_img_height * 96;
        $doc->footer_image_height = (float) $request->footer_img_height * 96;
        $doc->header_image_type = $request->header_image_type;
        $doc->footer_image_type = $request->footer_image_type;
        $doc->document_for = $request->docfor;
        $doc->document_content = $request->content;
        $doc->document_type_id = $request->doctype;
    }

    private function handleImageUpload(Request $request, $fieldName, $destinationPath, $currentImage = null)
    {
        if ($request->hasFile($fieldName)) {
            $image = $request->file($fieldName);
            $imageName = time() . '.' . $image->getClientOriginalName();
            $image->move(public_path($destinationPath), $imageName);

            // Optionally delete the old image if necessary
            if ($currentImage && file_exists(public_path($currentImage))) {
                unlink(public_path($currentImage));
            }

            return $destinationPath . '/' . $imageName;
        }

        return $currentImage;
    }
    public function remove(Request $request, $id)
    {
        $doc = Document::firstwhere('id', $request->id);
        if (!empty($doc->header_image) && file_exists(public_path($doc->header_image))) {
            unlink(public_path($doc->header_image)); // Delete the existing image
        }
        if (!empty($doc->footer_image) && file_exists(public_path($doc->footer_image))) {
            unlink(public_path($doc->footer_image)); // Delete the existing image
        }
        if (!empty($doc->background_image) && file_exists(public_path($doc->background_image))) {
            unlink(public_path($doc->background_image)); // Delete the existing image
        }
        if ($doc->delete()) {
            return back()->with('success', 'Document deleted Suuccessfully !!');
        } else {
            return back()->with('error', 'Something went wrong !!');
        }
    }

    public function updateStatus($id, Request $request)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $doc = Document::findOrFail($id);
        if ($doc) {
            $doc->status = $request->status;
            $doc->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }

    }

    public function deleteSelected(Request $request)
    {
        $selectedDocuments = $request->input('selected_documents');
        if (!empty($selectedDocuments)) {
            Document::whereIn('id', $selectedDocuments)->delete();
            return response()->json(['success' => true, 'message' => 'Selected Document deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'No Document Type selected for deletion.']);
    }

    public function sampleFileDownloadDocument()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="document_type_sample.csv"',
        ];

        $columns = ['ID', 'Name', 'Type', 'Content', 'Header Image', 'Footer Image', 'Status'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }    


}