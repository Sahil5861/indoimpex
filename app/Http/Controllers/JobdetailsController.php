<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;
use App\Models\JobDetails;
use App\Models\JobTypes;
use App\Models\JobNames;
use App\Models\PPItem;
use App\Models\NonwovenItem;
use App\Models\NonWovenCategory;
use App\Models\PPWovenItem;
use App\Models\PPWovenCategory;
use App\Models\Party;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\StreamedResponse;
use DataTables;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use App\Models\JobCylinder;
use App\Models\JobBopp;
use App\Models\JobMetal;
use App\Models\JobFabric;
use App\Models\JobLamination;
use App\Models\JobHandle;
use App\Models\JobCut;
use App\Models\JobFlexo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class JobdetailsController extends Controller
{
    public function __construct(Type $var = null) {
        $this->data['jobtypes'] = Jobtypes::all();
        $lastCode = JobDetails::orderBy('job_unique_code', 'desc')->value('job_unique_code');

        // dd($lastCode); exit;
        $nextCode = str_pad(((int)$lastCode + 1), 4, '0', STR_PAD_LEFT);

        $this->data['lastID'] = $nextCode;

        $this->data['parties'] = Party::all();

        // dd($this->data['nextUniqueCode']); exit;
    }

    public function index_all(Request $request)
    {
        $type = 'all';
        if ($request->ajax()) {
            $query = JobDetails::query();

            if ($type == 'all') {                
                $data = $query->where('approval_status', '1')->where('job_status', '1')->orderBy('created_at', 'desc')->get();
            }
            else if ($type == 'pending') {
                $data = $query->where('approval_status', '0')->where('job_status', '1')->orderBy('created_at', 'desc')->get();
            }
            else if($type== 'saved') {
                if (Auth::User()->role_id == 1) {                    
                    $data = $query->where('approval_status', '0')->where('job_status', '0')->orderBy('created_at', 'desc')->get();
                }
                else{
                    $data = $query->where('approval_status', '0')->where('job_status', '0')->where('saved_by', Auth::User()->id)->orderBy('created_at', 'desc')->get();
                }
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('art_work', function ($row) {
                    $id = $row->id;

                    // Get image JSON(s) as array
                    $images = DB::table('job_kld_images')
                                ->where('job_id', $id)
                                ->pluck('kld_images')
                                ->toArray();

                    $html = '<div style="display: flex; flex-wrap: wrap; gap: 5px; justify-content:center;">';

                    $id = $row->id;
                    foreach ($images as $key => $image) {  
                        $imagePath = public_path('images/job-images/' . $image);
                        $imageUrl = asset('images/job-images/' . $image);
                        $fallbackImageUrl = asset('images/no-image.png'); // Use a fallback image
                                                
                        if (File::exists($imagePath)) {
                            $html .= '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">
                                        <a href="' . $imageUrl . '" data-fancybox="kld_gallery_img_' . $id . '" data-caption="Artwork ' . ($key + 1) . '">
                                            <img src="' . $imageUrl . '" alt="'.$image.'" style="width:100%; width:60px; object-fit:cover;">
                                        </a>
                                    </div>';

                        } else {
                            $html .= '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">
                                        <img src="' . $fallbackImageUrl . '" alt="No image" style="width:70px; object-fit:cover;">
                                    </div>';
                        }                        
                    }                    
                    return $html;
                })
                ->addColumn('action', function ($row) {                    
                    $action2 = '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">';
                    if (hasPermission('Job Details All Update', 'Update')) {
                    $action2 .= '<a href="#" onclick="editUser(this)" data-id="'.$row->id.'" class="btn-sm" title="Update this Job">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="#006db5" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </a>';
                    }

                    if (hasPermission('Job Details All Delete', 'Delete')) {
                        $action2 .= '<a href="#" data-url="' . route('jobdetails.remove', $row->id) . '" data-id="' . $row->id . '" class="btn-sm delete-button" title="Delete this Job">
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="#ef4444" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </a> ';
                    }
                            
                       
                    if ($row->approval_status == 0){
                        if (hasPermission('Job Details All Approve', 'Approve')) {
                            $action2 .= '<a href="#" 
                                            data-id="' . $row->id . '" 
                                            data-url="'.route('approveJob',$row->id).'"                                        
                                            title="Approve this Job"
                                            class="btn-sm approve-job-btn">
                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="#26a69a" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                        </a>';
                        }
                    }
                    if (hasPermission('Job Details All Show', 'Show')) {                        
                        $action2 .= '<a href="#" onclick="showJob(this)" data-id="'.$row->id.'" class="btn-sm" title="View this Job">
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="#059669" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </a>';                                        
                    }


                    if (hasPermission('Job Details All Update', 'Update') || hasPermission('Job Details All Approve', 'Approve') || hasPermission('Job Details All Delete', 'Delete') || hasPermission('Job Details All Show', 'Show')) {
                        return $action2;
                    }
                    else{
                        return '--';
                    }
                })  
                ->addColumn('party_name', function ($row){
                    return $row->party_id && $row->party ? $row->party->party_name : '';
                })     
                
                ->addColumn('job_name', function ($row){
                    return $row->job_name_id && $row->jobName ? $row->jobName->job_name : '';
                })    
                
                ->addColumn('total_weight', function ($row){
                    return $row->bag_total_weight == 0 ? '0' : $row->bag_total_weight;
                })    
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : 'N/A';
                })
                ->rawColumns(['art_work', 'action']) // Important to render HTML
                ->make(true);
        }
        $this->data['request_type'] = $type;
        

        return view('admin.pages.jobdetails.index', $this->data );   
    }

    public function index_pending(Request $request)
    {
        $type = 'pending';
        if ($request->ajax()) {
            $query = JobDetails::query();

            if ($type == 'all') {                
                $data = $query->where('approval_status', '1')->where('job_status', '1')->orderBy('created_at', 'desc')->get();
            }
            else if ($type == 'pending') {
                $data = $query->where('approval_status', '0')->where('job_status', '1')->orderBy('created_at', 'desc')->get();
            }
            else if($type== 'saved') {
                if (Auth::User()->role_id == 1) {                    
                    $data = $query->where('approval_status', '0')->where('job_status', '0')->orderBy('created_at', 'desc')->get();
                }
                else{
                    $data = $query->where('approval_status', '0')->where('job_status', '0')->where('saved_by', Auth::User()->id)->orderBy('created_at', 'desc')->get();
                }
            }   

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('art_work', function ($row) {
                    $id = $row->id;

                    // Get image JSON(s) as array
                    $images = DB::table('job_kld_images')
                                ->where('job_id', $id)
                                ->pluck('kld_images')
                                ->toArray();

                    $html = '<div style="display: flex; flex-wrap: wrap; gap: 5px; justify-content:center;">';

                    $id = $row->id;
                    foreach ($images as $key => $image) {  
                        $imagePath = public_path('images/job-images/' . $image);
                        $imageUrl = asset('images/job-images/' . $image);
                        $fallbackImageUrl = asset('images/no-image.png'); // Use a fallback image
                                                
                        if (File::exists($imagePath)) {
                            $html .= '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">
                                            <a href="' . $imageUrl . '" data-fancybox="kld_gallery_img_' . $id . '" data-caption="Artwork ' . ($key + 1) . '">
                                                <img src="' . $imageUrl . '" alt="img" style="width:60px;object-fit:cover;">
                                            </a>
                                        </div>';

                        } else {
                            $html .= '<img src="' . $fallbackImageUrl . '" alt="No image" style="width:100%; height:70px; object-fit:cover;">';
                        }                        
                    }                    
                    return $html;
                })
                ->addColumn('action', function ($row) {                    
                    $action2 = '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">';
                    if (hasPermission('Job Details Pending Update', 'Update')) {
                    $action2 .= '<a href="#" onclick="editUser(this)" data-id="'.$row->id.'" class="btn-sm" title="Update this Job">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="#006db5" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </a>';
                    }

                    if (hasPermission('Job Details Pending Delete', 'Delete')) {
                        $action2 .= '<a href="#" data-url="' . route('jobdetails.remove', $row->id) . '" data-id="' . $row->id . '" class="btn-sm delete-button" title="Delete this Job">
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="#ef4444" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </a> ';
                    }
                            
                       
                    if ($row->approval_status == 0){
                        if (hasPermission('Job Details Pending Approve', 'Approve')) {
                            $action2 .= '<a href="#" 
                                            data-id="' . $row->id . '" 
                                            data-url="'.route('approveJob',$row->id).'"                                        
                                            title="Approve this Job"
                                            class="btn-sm approve-job-btn">
                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="#26a69a" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                        </a>';
                        }
                    }

                    if (hasPermission('Job Details Pending Show', 'Show')) {                        
                        $action2 .= '<a href="#" onclick="showJob(this)" data-id="'.$row->id.'" class="btn-sm" title="View this Job">
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="#059669" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </a>';                                        
                    }                                      

                    if (hasPermission('Job Details Pending Update', 'Update') || hasPermission('Job Details Pending Approve', 'Approve') || hasPermission('Job Details Pending Delete', 'Delete') || hasPermission('Job Details Pending Show', 'Show')) {
                        return $action2;
                    }
                    else{
                        return '--';
                    }
                })  
                ->addColumn('party_name', function ($row){
                    return $row->party_id && $row->party ? $row->party->party_name : '';
                })     
                
                ->addColumn('job_name', function ($row){
                    return $row->job_name_id && $row->jobName ? $row->jobName->job_name : '';
                })    
                
                ->addColumn('total_weight', function ($row){
                    return $row->bag_total_weight == 0 ? '0' : $row->bag_total_weight;
                })    
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : 'N/A';
                })
                ->rawColumns(['art_work', 'action']) // Important to render HTML
                ->make(true);
        }
        $this->data['request_type'] = $type;
        

        return view('admin.pages.jobdetails.index', $this->data );   
    }

    public function index_saved(Request $request)
    {
        $type = 'saved';
        if ($request->ajax()) {
            $query = JobDetails::query();

            if ($type == 'all') {                
                $data = $query->where('approval_status', '1')->where('job_status', '1')->orderBy('created_at', 'desc')->get();
            }
            else if ($type == 'pending') {
                $data = $query->where('approval_status', '0')->where('job_status', '1')->orderBy('created_at', 'desc')->get();
            }
            else if($type== 'saved') {
                if (Auth::User()->role_id == 1) {                    
                    $data = $query->where('approval_status', '0')->where('job_status', '0')->orderBy('created_at', 'desc')->get();
                }
                else{
                    $data = $query->where('approval_status', '0')->where('job_status', '0')->where('saved_by', Auth::User()->id)->orderBy('created_at', 'desc')->get();
                }
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('art_work', function ($row) {
                    $id = $row->id;

                    // Get image JSON(s) as array
                    $images = DB::table('job_kld_images')
                                ->where('job_id', $id)
                                ->pluck('kld_images')
                                ->toArray();

                    $html = '<div style="display: flex; flex-wrap: wrap; gap: 5px; justify-content:center;">';

                    $id = $row->id;
                    foreach ($images as $key => $image) {  
                        $imagePath = public_path('images/job-images/' . $image);
                        $imageUrl = asset('images/job-images/' . $image);
                        $fallbackImageUrl = asset('images/no-image.png'); // fallback image

                        // Get file extension in lowercase
                        $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));

                        if (File::exists($imagePath)) {
                            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                // ✅ Image file
                                $html .= '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">
                                            <a href="' . $imageUrl . '" data-fancybox="kld_gallery_img_' . $id . '" data-caption="Artwork ' . ($key + 1) . '">
                                                <img src="' . $imageUrl . '" alt="'.$image.'" style="width:60px; height:60px; object-fit:cover;">
                                            </a>
                                        </div>';
                            } elseif ($extension === 'pdf') {
                                // ✅ PDF file
                                $html .= '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">
                                            <a href="' . $imageUrl . '" data-fancybox="kld_gallery_img_' . $id . '" data-caption="PDF ' . ($key + 1) . '" 
                                            style="display:inline-block; width:max-content; height:60px; background:#f5f5f5; border:1px solid #ddd; paddng:8px; 
                                                    display:flex; align-items:center; justify-content:center; font-size:12px; text-decoration:none;">
                                                '.$image.'
                                            </a>
                                        </div>';
                            } else {
                                // ❌ Other file types - show fallback image
                                $html .= '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">
                                            <img src="' . $fallbackImageUrl . '" alt="No image" style="width:60px; height:60px; object-fit:cover;">
                                        </div>';
                            }
                        } else {
                            // ❌ File does not exist - show fallback
                            $html .= '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">
                                        <img src="' . $fallbackImageUrl . '" alt="No image" style="width:60px; height:60px; object-fit:cover;">
                                    </div>';
                        }
                    }
                   
                    return $html;
                })
                ->addColumn('action', function ($row) {                    
                    $action2 = '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">';
                    if (hasPermission('Job Details Saved Update', 'Update')) {
                    $action2 .= '<a href="#" onclick="editUser(this)" data-id="'.$row->id.'" class="btn-sm" title="Update this Job">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="#006db5" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </a>';
                    }

                    if (hasPermission('Job Details Saved Delete', 'Delete')) {
                        $action2 .= '<a href="#" data-url="' . route('jobdetails.remove', $row->id) . '" data-id="' . $row->id . '" class="btn-sm delete-button" title="Delete this Job">
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="#ef4444" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </a> ';
                    }
                            
                       
                    if ($row->approval_status == 0){
                        if (hasPermission('Job Details Saved Approve', 'Approve')) {
                            $action2 .= '<a href="#" 
                                            data-id="' . $row->id . '" 
                                            data-url="'.route('approveJob',$row->id).'"                                        
                                            title="Approve this Job"
                                            class="btn-sm approve-job-btn">
                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="#26a69a" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                        </a>';
                        }
                    }

                    if (hasPermission('Job Details Saved Show', 'Show')) {                        
                        $action2 .= '<a href="#" onclick="showJob(this)" data-id="'.$row->id.'" class="btn-sm" title="View this Job">
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="#059669" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </a>';                                        
                    }

                    if (hasPermission('Job Details Saved Update', 'Update') || hasPermission('Job Details Saved Approve', 'Approve') || hasPermission('Job Details Saved Delete', 'Delete') || hasPermission('Job Details Saved Show', 'Show')) {
                        return $action2;
                    }
                    else{
                        return '--';
                    }
                })  
                ->addColumn('saved_by', function ($row){
                    $saved_by_id =  $row->saved_by;
                    $saved_by_name = User::where('id', $saved_by_id)->value('name');
                    return ucfirst($saved_by_name);
                })
                ->addColumn('party_name', function ($row){
                    return $row->party_id && $row->party ? $row->party->party_name : '';
                })     
                
                ->addColumn('job_name', function ($row){
                    return $row->job_name_id && $row->jobName ? $row->jobName->job_name : '';
                })    
                
                ->addColumn('total_weight', function ($row){
                    return $row->bag_total_weight == 0 ? '0' : $row->bag_total_weight;
                })    
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : 'N/A';
                })
                ->rawColumns(['art_work', 'action']) // Important to render HTML
                ->make(true);
        }
        $this->data['request_type'] = $type;
        

        return view('admin.pages.jobdetails.index', $this->data );   
    }

    public function approveJob($id){
        $job = JobDetails::find($id);
        $job->approval_status = '1';        
        $job->job_status = '1';        
        if ($job->save()) {
            ActivityLogger::log('Job Details', 'Approve Job', 'Job Approved of Id ' . $job->job_unique_code);
            return redirect()->back()->with('success', 'Job Approved !!'); 
        }
    }


    public function removeImage(Request $request){
        $id = $request->id;
        $table = $request->table;

        // dd($id, $table); exit;

        $deleted = DB::table($table)->where('id', $id)->delete();

        if ($deleted) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 500);   
    }

    


    // public function checkjobName(Request $request){
    //     $value = $request->value;
    //     $job_name = JobNames::whereRaw('LOWER(TRIM(job_name)) = ?', [strtolower(trim($value))])->first();
    //     if ($request->has('job_id') && $request->job_id != '') {
    //         $job_detail = JobDetails::where('id', $request->job_id)->first();
    //         $job_name_id = $job_detail->job_name_id;

    //         $job_name = JobNames::where('id', $job_name_id)->first()->job_name;

    //         if ($job_name == $value) {
    //             return response()->json([
    //                 'status'=> false,                
    //             ]);
    //         }
    //         exit;
    //     }

    //     if ($job_name) {
    //         return response()->json([
    //             'status'=> true,
    //             'message' => 'This Job name already exists',
    //             'job_name' => $job_name,
    //             'job_id' => $request->job_id
    //         ]);
    //     }
    //     else{
    //         return response()->json([
    //             'status'=> false,                
    //         ]);
    //     }
    // }

    public function checkjobName(Request $request){

        $value = strtolower(trim($request->value));
        
        // Editing a job
        if ($request->has('job_id') && !empty($request->job_id)) {
            $jobDetail = JobDetails::find($request->job_id);

            // If job detail not found
            if (!$jobDetail) {
                return response()->json(['status' => false]);
            }

            // Get original job name for this job_id
            $originalJobName = strtolower(trim($jobDetail->jobName->job_name ?? ''));

            // If input value matches original name, allow it (no duplication error)
            if ($value === $originalJobName) {
                return response()->json(['status' => false]); // no error
            }
        }

        // Check if the job name already exists (excluding current editing one if job_id is passed)
        $jobExists = JobNames::whereRaw('LOWER(TRIM(job_name)) = ?', [$value])
            ->when($request->has('job_id') && !empty($request->job_id), function ($query) use ($request) {
                $jobDetail = JobDetails::find($request->job_id);
                if ($jobDetail) {
                    $query->where('id', '!=', $jobDetail->job_name_id);
                }
            })
            ->first();

        if ($jobExists) {
            return response()->json([
                'status' => true,
                'message' => 'This Job name already exists',                
            ]);
        }

        return response()->json(['status' => false]);
    }

    


    public function getJobdetailsEdit(Request $request){
        $id = $request->id;
        $jobdetails = JobDetails::where('id', $id)->first();
        
        if ($jobdetails) {
            $party = Party::where('id', $jobdetails->party_id)->first() ?? [];
            $colors = explode(',', $jobdetails->colors) ?? [];
            $colors_cmyk = explode(',', $jobdetails->colors_cmyk) ?? [];
            $color_name = explode(',', $jobdetails->color_name) ?? [];

            $jobname = JobNames::where('id', $jobdetails->job_name_id)->first() ?? [];
            $jobtype = Jobtypes::where('id', $jobdetails->job_type_id)->first() ?? [];
            $job_cylinder = JobCylinder::where('job_detail_id', $jobdetails->id)->first() ?? [];            
            $job_bopp = JobBopp::where('job_detail_id', $jobdetails->id)->first() ?? [];
            $job_metalised = JobMetal::where('job_detail_id', $jobdetails->id)->first() ?? [];
            $job_fabric = JobFabric::where('job_detail_id', $jobdetails->id)->first() ?? [];
            $job_lamination = JobLamination::where('job_detail_id', $jobdetails->id)->first() ?? [];
            $job_handle = JobHandle::where('job_detail_id', $jobdetails->id)->first() ?? [];
            $job_cut = JobCut::where('job_detail_id', $jobdetails->id)->first() ?? [];

            // images

            $kld_images = DB::table('job_kld_images')->where('job_id', $jobdetails->id)->get();
            $mockup_images = DB::table('job_images')->where('job_id', $jobdetails->id)->get();
            $approval_images = DB::table('job_approve_image')->where('job_id', $jobdetails->id)->get();
            $separation_images = DB::table('job_suppression_images')->where('job_id', $jobdetails->id)->get();

            
            $this->jobdetail = [
                'jobdetails' => $jobdetails,
                'colors' => $colors,
                'color_name' => $color_name,
                'colors_cmyk' => $colors_cmyk,
                'party' => $party,
                'jobname' => $jobname,
                'jobtype' => $jobtype,
                'job_cylinder'  => $job_cylinder,
                'job_bopp' => $job_bopp,
                'job_metalised' => $job_metalised,
                'job_fabric' => $job_fabric,
                'job_lamination' => $job_lamination,
                'job_handle' => $job_handle,
                'job_cut' => $job_cut,
                'kld_images' => $kld_images,
                'mockup_images' => $mockup_images,
                'approval_images' => $approval_images,
                'separation_images' => $separation_images
            ];
            return response()->json([
                'status' => true,
                'data' => $this->jobdetail
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'Job details not found !'
            ]);
        }
    }

    public function store(Request $request)
    {        
        
        
        $request->validate([
            'job_type' => 'required|integer',
            'job_code' => 'required|string|max:255',
            // 'party_name' => 'required|string|max:100',
            // 'job_name' => 'required|string|max:100',  
            // 'bag_total_weight' => 'required'        
            // 'printing_type' => 'required|string|max:100',
            // 'bag_type' => 'required|string|max:100',
            // 'bag_total_weight' => 'nullable|numeric',
            // 'bag_circum' => 'nullable|numeric',
            // 'bag_pet' => 'nullable|numeric',
            // 'bag_gazette' => 'nullable|numeric',
            // 'is_metallized' => 'required|boolean',
            // 'job_description' => 'nullable|string',            
        ]);

        // dd($request->all()); exit;


        $type = $request->type;

        $job_status = $request->job_status;
        $approve = 0;
        if ($job_status == 1) {
            $approve = 1;
        }

        if (!empty($request->id)) {
            $party_name = Party::where('party_name', $request->party_name)->first();    
            if (!$party_name) {
                return redirect()->back()->withInputs('error', 'Party Name not found !');
            }
            else{
                
                $party_id = $party_name->id;
            }
            // ✅ Update JobDetails record
            $jobdetails = JobDetails::find($request->id);

            $job_name = JobNames::where('job_name', $request->job_name)->first();

            if (!$job_name) {                
                $job_name = new JobNames();
                $job_name->job_name = $request->input('job_name');
                $job_name->save();
                $job_id = $job_name->id;
            }
            else{
                $job_id = $jobdetails->job_name_id;
            }

            

            if (!$jobdetails) {
                return redirect()->back()->with('error', 'Job not found.');
            }

            $jobdetails->job_type_id = $request->input('job_type');
            $jobdetails->job_unique_code = $request->input('job_code');
            $jobdetails->party_id = $party_id;
            $jobdetails->job_name_id = $job_id;
            $jobdetails->printing_type = $request->input('printing_type');
            $jobdetails->bag_job_type = $request->input('job_bag_type');
            $jobdetails->bag_type = $request->input('job.bag_type');
            $jobdetails->bag_total_weight = $request->input('bag_total_weight') ?? 0.0;
            $jobdetails->bag_circum = $request->input('job.bag_circum');
            $jobdetails->bag_pet = $request->input('job.bag_pet');
            $jobdetails->bag_gazette = $request->input('job.bag_gazette', 0);
            $jobdetails->job_description = $request->input('job.job_description');
            $jobdetails->is_metallized = $request->input('bopp_metal_type');
            $jobdetails->submit_date = $request->input('submit_date'); 
            $jobdetails->job_status = $request->job_status; 
            $jobdetails->bottom_enclave = $request->input('bottom_enclave') ?? null;
            if ($request->job_status == 0) {
                $jobdetails->approval_status = '0'; 
            } 
            else if ($request->job_status == 1 && \Auth::User()->role_id = '1') {
                $jobdetails->approval_status = '1'; 
            }          
            $jobdetails->saved_by = \Auth::User()->id;
            $jobdetails->colors = implode(', ',$request->colors);
            $jobdetails->colors_cmyk = implode(', ',$request->colors_cmyk);
            $jobdetails->color_name = implode(', ',$request->color_name);
            
            // dd($request->all()); exit;
            
            if ($jobdetails->save()) {
                $job_detail_id = $jobdetails->id;

                // dd($jobdetails, $request->all()); exit;

                $destinationPath = public_path('images/job-images/');

                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $now = Carbon::now();

                $job_name = Str::slug($request->input('job_name'), '_');

                // Handle Mockup Images
                if ($request->hasFile('files')) {
                    // DB::table('job_images')->where('job_id', $job_detail_id)->delete();
                    foreach ($request->file('files') as $file) {
                        $filename = $job_name . '_MKP_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move($destinationPath, $filename);

                        DB::table('job_images')->insert([
                            'job_id' => $job_detail_id,
                            'job_images' => $filename,
                            'date' => $now                          
                        ]);
                    }
                }

                
                // Handle KLD Images
                if ($request->hasFile('kld')) {
                    // DB::table('job_kld_images')->where('job_id', $job_detail_id)->delete();
                    foreach ($request->file('kld') as $file) {
                        $filename = $job_name . '_KLD_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move($destinationPath, $filename);

                        DB::table('job_kld_images')->insert([
                            'job_id' => $job_detail_id,
                            'kld_images' => $filename,  
                            'date' => $now                          
                        ]);
                    }
                }

                // Handle suppression Images
                if ($request->hasFile('suppression')) {
                    // DB::table('job_suppression_images')->where('job_id', $job_detail_id)->delete();
                    foreach ($request->file('kld') as $file) {
                        $filename = $job_name . '_SEP_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move($destinationPath, $filename);

                        DB::table('job_suppression_images')->insert([
                            'job_id' => $job_detail_id,
                            'kld_images' => $filename,  
                            'date' => $now                          
                        ]);
                    }
                }

                // Handle Approve Images
                if ($request->hasFile('approve')) {
                    // DB::table('job_approve_image')->where('job_id', $job_detail_id)->delete();
                    foreach ($request->file('approve') as $file) {
                        $filename = $job_name . '_APRV_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move($destinationPath, $filename);

                        DB::table('job_approve_image')->insert([
                            'job_id' => $job_detail_id,
                            'approve_image' => $filename, 
                            'date' => $now                           
                        ]);
                    }
                }

                // ✅ Update or Create Cylinder
                $args2 = $request->input('cylinder');
                if (!empty($args2)) {
                    JobCylinder::updateOrCreate(
                        ['job_detail_id' => $job_detail_id],
                        $args2
                    );
                }                

                // ✅ Update or Create BOPP
                $bopp = $request->input('bopp');
                if (!empty($bopp['bopp_item_code'])) {
                    JobBopp::updateOrCreate(
                        ['job_detail_id' => $job_detail_id],
                        $bopp
                    );
                }
                // else{
                //     JobBopp::where('job_detail_id', $job_detail_id)->delete();
                // }

                // ✅ Update or Create Metal
                

                if ($request->input('bopp_metal_type') == '1') {
                    $metal = $request->input('metal');
                    // JobMetal::updateOrCreate(
                    //     ['job_detail_id' => $request->id],
                    //     $metal                        
                    // );

                    $job_metal = JobMetal::where('job_detail_id', $request->id)->first() ?? new JobMetal();
                    $job_metal->job_detail_id = $request->id;
                    $job_metal->metal_item_code = $metal['metal_item_code'];
                    $job_metal->job_metal_size = $metal['job_metal_size'];
                    $job_metal->job_metal_type = $metal['job_metal_type'];
                    $job_metal->job_metal_micron = $metal['job_metal_micron'];
                    $job_metal->job_metal_weight = $metal['job_metal_weight'];

                    $job_metal->save();
                }

                // [
                //     'metal_item_code' => $metal['metal_item_code'],
                //     'job_metal_size' => $metal['job_metal_size'],
                //     'job_metal_type' => $metal['job_metal_type'],
                //     'job_metal_micron' => $metal['job_metal_micron'],
                //     'job_metal_weight' => $metal['job_metal_weight'],                
                // ]

                // dd($request->all()); exit;

                // ✅ Update or Create Fabric
                $fabric = $request->input('fabric');
                if (!empty($fabric['fabric_item_code'])) {
                    JobFabric::updateOrCreate(
                        ['job_detail_id' => $job_detail_id],
                        $fabric
                    );
                }

                // ✅ Update or Create Lamination
                $lamination = $request->input('lamination');
                if (!empty($lamination)){
                    JobLamination::updateOrCreate(
                        ['job_detail_id' => $job_detail_id],
                        array_merge($lamination, [
                            'job_lamination_size' => $fabric['job_fabric_size'] ?? null,
                        ])
                    );
                }

                 // ✅ Handle: Update only if present
                $handle = $request->input('handle');
                if (!empty($handle['job_handle_weight'])) {
                    JobHandle::updateOrCreate(
                        ['job_detail_id' => $job_detail_id],
                        $handle
                    );
                }

                // ✅ Cut: Update only if present
                $cut = $request->input('cut');
                if (!empty($cut['cut_wastage'])) {
                    JobCut::updateOrCreate(
                        ['job_detail_id' => $job_detail_id],
                        $cut
                    );
                }

                // ✅ Flexo
                $flexo = $request->input('flexo_b');
                if (!empty($flexo['flexo_circum'])) {
                    JobFlexo::updateOrCreate(
                        ['job_id' => $job_detail_id],
                        $flexo
                    );
                }

                if ($request->job_status == 0) {                    
                    ActivityLogger::log('Job Details', 'Job Saved and Updated', 'Job Saved of Id ' . $jobdetails->job_unique_code);
                }
                else{
                    ActivityLogger::log('Job Details', 'Job Submitted and Updated', 'Job Submitted of Id ' . $jobdetails->job_unique_code);
                }
                return redirect()->back()->with('success', 'Job details Updated successfully.');
            }
            else{
                return redirect()->back()->with('error', 'Job detailsnot updated successfully.');
            }
        }
        else{   

            // dd($request->all()); exit;
            $party_name = Party::where('party_name', $request->party_name)->first();    

            if (!$party_name) {
                return redirect()->back()->with('error', 'Party name does not exists.');
            }
            else{
                
                $party_id = $party_name->id;
            }
                        
        
            $jobdetails = new JobDetails();
            $jobdetails->job_type_id = $request->input('job_type');
            $jobdetails->job_unique_code = $request->input('job_code');
            $jobdetails->party_id = $party_id;
            
            $jobdetails->printing_type = $request->input('printing_type');
            $jobdetails->bag_job_type = $request->input('job_bag_type');
            $jobdetails->bag_type = $request->input('job.bag_type');
            $jobdetails->bag_total_weight = $request->input('bag_total_weight');
            $jobdetails->bag_circum = $request->input('job.bag_circum');
            $jobdetails->bag_pet = $request->input('job.bag_pet');
            $jobdetails->bag_gazette = $request->input('job.bag_gazette', 0);
            $jobdetails->job_description = $request->input('job.job_description');
            $jobdetails->is_metallized = $request->input('bopp_metal_type');
            $jobdetails->approval_status = '0';
            $jobdetails->submit_date = $request->input('submit_date') ?? now()->format('Y-m-d');
            $jobdetails->job_status = $request->job_status;
            $jobdetails->saved_by = \Auth::User()->id;
            $jobdetails->colors = implode(', ',$request->colors); 
            $jobdetails->colors_cmyk = implode(', ',$request->colors_cmyk);
            $jobdetails->color_name = implode(', ',$request->color_name);

            $jobdetails->bottom_enclave = $request->input('bottom_enclave') ?? null;

            $job_name = JobNames::where('job_name', $request->job_name)->first();

            // dd($jobdetails); exit;

            if ($job_name) {
                return redirect()->back()->with('error', 'Job Name already exists.');
            }
            else{
                $job_name = new JobNames();
                $job_name->job_name = $request->input('job_name');
                $job_name->save();
                $job_id = $job_name->id;
            }

            $jobdetails->job_name_id = $job_id;


            if ($jobdetails->save()) {                   
                $job_detail_id = $jobdetails->id;               
                $destinationPath = public_path('images/job-images/');

                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $now = Carbon::now();

                $job_name = Str::slug($request->input('job_name'), '_');


                // Handle Mockup Images
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $filename = $job_name . '_MKP_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move($destinationPath, $filename);

                        DB::table('job_images')->insert([
                            'job_id' => $job_detail_id,
                            'job_images' => $filename,
                            'date' => $now                          
                        ]);
                    }
                }

                // Handle KLD Images
                if ($request->hasFile('kld')) {
                    foreach ($request->file('kld') as $file) {
                        $filename = $job_name . '_KLD_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move($destinationPath, $filename);

                        DB::table('job_kld_images')->insert([
                            'job_id' => $job_detail_id,
                            'kld_images' => $filename,  
                            'date' => $now                          
                        ]);
                    }
                }

                if ($request->hasFile('suppression')) {
                    foreach ($request->file('suppression') as $file) {
                        $filename = $job_name . '_SEP_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move($destinationPath, $filename);

                        DB::table('job_suppression_images')->insert([
                            'job_id' => $job_detail_id,
                            'kld_images' => $filename,  
                            'date' => $now                          
                        ]);
                    }
                }

                // Handle Approve Images
                if ($request->hasFile('approve')) {
                    foreach ($request->file('approve') as $file) {
                        $filename = $job_name . '_APRV_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move($destinationPath, $filename);

                        DB::table('job_approve_image')->insert([
                            'job_id' => $job_detail_id,
                            'approve_image' => $filename, 
                            'date' => $now                           
                        ]);
                    }
                }

                

                $args2 = $request->input('cylinder');
                if ($request->has('cylinder')) {                    
                    $job_cylinder = new JobCylinder($args2);
                    $job_cylinder->job_detail_id = $job_detail_id;
                    $job_cylinder->save();
                }

                $bopp = $request->input('bopp');
                if (!empty($bopp['bopp_item_code'])) {
                    $job_bopp = new JobBopp($bopp);
                    $job_bopp->job_detail_id = $job_detail_id;
                    $job_bopp->save();
                }

                // ✅ Save Metal (if bopp_metal_type is 1)
                if ($request->input('bopp_metal_type') == '1') {
                    $metal = $request->input('metal');
                    $job_metal = new JobMetal($metal);
                    $job_metal->job_detail_id = $job_detail_id;
                    $job_metal->save();
                }

                // ✅ Save Fabric
                $fabric = $request->input('fabric');
                if (!empty($fabric['fabric_item_code'])) {                    
                    $job_fabric = new JobFabric($fabric);
                    $job_fabric->job_detail_id = $job_detail_id;
                    $job_fabric->save();
                }

                // ✅ Save Lamination
                $lamination = $request->input('lamination');
                $job_lamination = new JobLamination($lamination);
                $job_lamination->job_detail_id = $job_detail_id;
                $job_lamination->job_lamination_size = $fabric['job_fabric_size'] ?? 0; // from fabric
                $job_lamination->job_lamination_mix = $request->input('lamination.job_lamination_mix') ?? 0;                
                $job_lamination->job_lamination_gsm = $request->input('lamination.job_lamination_gsm') ?? 0;                
                $job_lamination->job_lamination_weight = $request->input('lamination.job_lamination_weight') ?? 0;                
                
                $job_lamination->save();

                // ✅ Save Handle if weight exists
                $handle = $request->input('handle');
                if (!empty($handle['job_handle_weight'])) {
                    $job_handle = new JobHandle($handle);
                    $job_handle->job_detail_id = $job_detail_id;
                    $job_handle->save();
                }

                // ✅ Save Cut if wastage exists
                $cut = $request->input('cut');
                if (!empty($cut['cut_wastage'])) {
                    $job_cut = new JobCut($cut);
                    $job_cut->job_detail_id = $job_detail_id;
                    $job_cut->save();
                }

                // ✅ Save Flexo if circumference exists
                $flexo = $request->input('flexo_b');
                if (!empty($flexo['flexo_circum'])) {
                    $job_flexo = new JobFlexo($flexo);
                    $job_flexo->job_id = $job_detail_id;
                    $job_flexo->save();
                }  
                
                if ($request->job_status == 0) {                    
                    ActivityLogger::log('Job Details', 'Job Saved', 'Job Saved of Id ' . $jobdetails->job_unique_code);
                }
                else{
                    ActivityLogger::log('Job Details', 'Job Submitted', 'Job Submitted of Id ' . $jobdetails->job_unique_code);
                }
                return redirect()->back()->with('success', 'Job details added successfully.');
            }
            else{
                return redirect()->route($route)->with('error', 'Job details Not added successfully.');
            }

        }

    }


    public function getBoppItems(Request $request){
        $item_code = $request->item_code;
        $boppItems = PPItem::where('item_code', 'like', '%' . $item_code . '%')->get();
        if ($boppItems) {
            # code...
            return response()->json([
                'status' => true,
                'boppItems' => $boppItems
            ]);        
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'Item not found !!'
            ]);
        }
    }

    public function getFabricItems(Request $request){
        $item_code = $request->item_code;
        $fabricItems = NonwovenItem::where('item_code', 'like', '%' . $item_code . '%')->get();
        if ($fabricItems) {
            # code...
            return response()->json([
                'status' => true,
                'fabricItems' => $fabricItems
            ]);        
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'Item not found !!'
            ]);
        }
    }


    public function getFabricPPItems(Request $request){
        $item_code = $request->item_code;
        $fabricItems = PPWovenItem::where('item_code', 'like', '%' . $item_code . '%')->get();
        if ($fabricItems) {
            # code...
            return response()->json([
                'status' => true,
                'fabricItems' => $fabricItems
            ]);        
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'Item not found !!'
            ]);
        }
    }


    private function uploadFiles(Request $request, $key, $destinationPath)
    {
        if ($request->hasFile($key)) {
            foreach ($request->file($key) as $file) {
                if ($file && $file->isValid()) {
                    $filename = time().'_'.uniqid().'_'.$file->getClientOriginalExtension();
                    $file->move($destinationPath, $filename);
                }
            }
        }        
    }


    public function checkboppitem(Request $request){
        $check_item = PPItem::where('item_code', $request->bopp_item_code)->first();
        if ($check_item) {
            return response()->json([
                'status' => true,
                'item' => $check_item
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'Item not found !'                
            ]);
        }
    }

    public function checkfibreitem(Request $request){
        $check_item = NonwovenItem::where('item_code', $request->item_code)->first();
        if ($check_item) {
            $item = NonWovenCategory::where('category_value', $check_item->non_color)->first();
            return response()->json([
                'status' => true,
                'check_item' => $check_item,
                'item' => $item            
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'Item not found !'                
            ]);
        }
    }

    public function checkfibrePPitem(Request $request){
        $check_item = PPWovenItem::where('item_code', $request->item_code)->first();
        if ($check_item) {
            $item = PPWovenCategory::where('category_value', $check_item->pp_category)->first();
            return response()->json([
                'status' => true,
                'check_item' => $check_item,
                'item' => $item            
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'Item not found !'                
            ]);
        }
    }

    public function getParyDetails(Request $request){
        $party = Party::where('party_name', 'like', '%' . $request->party_name . '%')->where('status', 1)->get();
        // $party = Party::where('party_name',$request->party_name)->get();
        if (!empty($party)) {            
            return response()->json([
                'status' => true,
                'party' => $party
            ]);
        }
        else{
            return response()->json([
                'status' => false,                
            ]);
        }
    }

    public function getJobDetails(Request $request){
        $job = JobNames::where('job_name', 'like', '%' . $request->job_name . '%')->get();
        if (!empty($job)) {            
            return response()->json([
                'status' => true,
                'job' => $job
            ]);
        }
        else{
            return response()->json([
                'status' => false,                
            ]);
        }
    }
   
    public function remove(Request $request, $id)
    {
        $jobdetail = JobDetails::findOrFail($id);

        $uniqui_id = $jobdetail->job_unique_code;

        if ($jobdetail->delete()) {
            ActivityLogger::log('Job Details', 'Job Removed', 'Job Deleted of Id ' . $uniqui_id);
            return back()->with('success', 'JobDetails deleted successfully.');
        } else {
            return back()->with('error', 'Something went wrong.');
        }
    }

    public function multidelete(Request $request){
        $ids = $request->selectedIds;
        $uniqueIds = JobDetails::whereIn('id', $ids)->pluck('job_unique_code')->toArray();
        // print_r($ids); exit;

        $uniqueIdsString = implode(', ', $uniqueIds);
        $jobdetails = JobDetails::whereIn('id', $ids)->delete();

        ActivityLogger::log('Job Details', 'Job Removed', 'Jobs deleted with Unique IDs: ' . $uniqueIdsString);
        
        return response()->json([
            'status' => true,
            'message' => 'Items deleted'
        ]);
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
}
