<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobDetails;
use App\Models\JobTypes;
use App\Models\JobNames;
use App\Models\PPItem;
use App\Models\NonwovenItem;
use App\Models\NonWovenCategory;
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
        $this->data['lastID'] = JobDetails::latest('id')->first();
        $this->data['parties'] = Party::all();
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
                        $html .= '<div style="width: 60px; height: 60px; overflow: hidden;">';
                          $html .= '<a href="' . asset('images/job-images/' . $image) . '" data-fancybox="kld_gallery_img_' . $id . '" data-caption="Artwork ' . ($key + 1) . '">
                                    <img src="' . asset('images/job-images/' . $image) . '" alt="img" style="width:100%; height:100%; object-fit:cover;">
                                </a>';
                        $html .= '</div>';                        
                    }

                    $html .= '</div>';
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
                        $html .= '<div style="width: 60px; height: 60px; overflow: hidden;">';
                          $html .= '<a href="' . asset('images/job-images/' . $image) . '" data-fancybox="kld_gallery_img_' . $id . '" data-caption="Artwork ' . ($key + 1) . '">
                                    <img src="' . asset('images/job-images/' . $image) . '" alt="img" style="width:100%; height:100%; object-fit:cover;">
                                </a>';
                        $html .= '</div>';                        
                    }

                    $html .= '</div>';
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
                        $html .= '<div style="width: 60px; height: 60px; overflow: hidden;">';
                          $html .= '<a href="' . asset('images/job-images/' . $image) . '" data-fancybox="kld_gallery_img_' . $id . '" data-caption="Artwork ' . ($key + 1) . '">
                                    <img src="' . asset('images/job-images/' . $image) . '" alt="img" style="width:100%; height:100%; object-fit:cover;">
                                </a>';
                        $html .= '</div>';                        
                    }

                    $html .= '</div>';
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
            return redirect()->back()->with('success', 'Job Approved !!'); 
        }
    }

    // public function pending(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $query = JobDetails::query();

    //         $data = $query->where('approval_status', '0')->where('job_status', '1')->orderBy('id')->get();
    //         return DataTables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('action', function ($row) {
    //                 return '<div class="dropdown">
    //                             <a href="#" class="text-body" data-bs-toggle="dropdown">
    //                                 <i class="ph-list"></i>
    //                             </a>
    //                             <div class="dropdown-menu dropdown-menu-end">
    //                                 <a href="#" onclick="editUser(this)" data-id="'.$row->id.'" data-email="'.$row->email.'" data-first_name="'.$row->first_name.'" data-last_name="'.$row->last_name.'" data-username="'.$row->username.'" data-type="'.$row->type.'" class="dropdown-item">
    //                                     <i class="ph-pencil me-2"></i>Edit
    //                                 </a>                                    
    //                                 <a href="' . route('jobdetails.remove', $row->id) . '" data-id="' . $row->id . '" class="dropdown-item delete-button">
    //                                     <i class="ph-trash me-2"></i>Delete
    //                                 </a>
    //                             </div>
    //                         </div>';
    //             })  
    //             ->addColumn('party_name', function ($row){
    //                 return $row->party_id && $row->party ? $row->party->party_name : '';
    //             })     
                
    //             ->addColumn('job_name', function ($row){
    //                 return $row->job_name_id && $row->jobName ? $row->jobName->job_name : '';
    //             })    
                
    //             ->addColumn('total_weight', function ($row){
    //                 return $row->bag_total_weight == 0 ? '0' : $row->bag_total_weight;
    //             })    
    //             ->addColumn('created_at', function ($row) {
    //                 return $row->created_at ? $row->created_at->format('d M Y') : 'N/A';
    //             })
    //             ->make(true);
    //     }
    //     $this->data['request_type'] = 'pending';

    //     return view('admin.pages.jobdetails.index', $this->data);   
    // }

    // public function saved(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $query = JobDetails::query();

    //         $data = $query->where('approval_status', '0')->where('job_status', '0')->orderBy('id')->get();
    //         return DataTables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('action', function ($row) {
    //                 return '<div class="dropdown">
    //                             <a href="#" class="text-body" data-bs-toggle="dropdown">
    //                                 <i class="ph-list"></i>
    //                             </a>
    //                             <div class="dropdown-menu dropdown-menu-end">
    //                                 <a href="#" onclick="editUser(this)" data-id="'.$row->id.'" data-email="'.$row->email.'" data-first_name="'.$row->first_name.'" data-last_name="'.$row->last_name.'" data-username="'.$row->username.'" data-type="'.$row->type.'" class="dropdown-item">
    //                                     <i class="ph-pencil me-2"></i>Edit
    //                                 </a>                                    
    //                                 <a href="' . route('jobdetails.remove', $row->id) . '" data-id="' . $row->id . '" class="dropdown-item delete-button">
    //                                     <i class="ph-trash me-2"></i>Delete
    //                                 </a>
    //                             </div>
    //                         </div>';
    //             })  
    //             ->addColumn('party_name', function ($row){
    //                 return $row->party_id && $row->party ? $row->party->party_name : '';
    //             })     
                
    //             ->addColumn('job_name', function ($row){
    //                 return $row->job_name_id && $row->jobName ? $row->jobName->job_name : '';
    //             })    
                
    //             ->addColumn('total_weight', function ($row){
    //                 return $row->bag_total_weight == 0 ? '0' : $row->bag_total_weight;
    //             })    
    //             ->addColumn('created_at', function ($row) {
    //                 return $row->created_at ? $row->created_at->format('d M Y') : 'N/A';
    //             })
    //             ->make(true);
    //     }
    //     $this->data['request_type'] = 'saved';

    //     return view('admin.pages.jobdetails.index', $this->data);   
    // }


    public function checkjobName(Request $request){
        $value = $request->value;
        $job_name = JobNames::whereRaw('LOWER(TRIM(job_name)) = ?', [strtolower(trim($value))])->first();

        if ($job_name) {
            return response()->json([
                'status'=> true,
                'message' => 'This Job name already exists'                
            ]);
        }
        else{
            return response()->json([
                'status'=> false,                
            ]);
        }
    }

    public function getJobdetailsEdit(Request $request){
        $id = $request->id;
        $jobdetails = JobDetails::where('id', $id)->first();
        
        if ($jobdetails) {
            $party = Party::where('id', $jobdetails->party_id)->first() ?? [];
            $colors = explode(',', $jobdetails->colors) ?? [];
            $colors_cmyk = explode(',', $jobdetails->colors_cmyk) ?? [];

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
            'party_name' => 'required|string|max:100',
            'job_name' => 'required|string|max:100',  
            'bag_total_weight' => 'required'        
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
            $jobdetails->bag_type = $request->input('job.bag_type');
            $jobdetails->bag_total_weight = $request->input('bag_total_weight') ?? 0.0;
            $jobdetails->bag_circum = $request->input('job.bag_circum');
            $jobdetails->bag_pet = $request->input('job.bag_pet');
            $jobdetails->bag_gazette = $request->input('job.bag_gazette', 0);
            $jobdetails->job_description = $request->input('job.job_description');
            $jobdetails->is_metallized = $request->input('bopp_metal_type');
            $jobdetails->submit_date = $request->input('submit_date'); 
            $jobdetails->job_status = $request->job_status;            
            $jobdetails->saved_by = \Auth::User()->id;
            $jobdetails->colors = implode(', ',$request->colors);
            $jobdetails->colors_cmyk = implode(', ',$request->colors_cmyk);
            
            // dd($request->all()); exit;
            
            if ($jobdetails->save()) {
                $job_detail_id = $jobdetails->id;

                $destinationPath = public_path('images/job-images/');

                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $now = Carbon::now();
                // Handle Mockup Images
                if ($request->hasFile('files')) {
                    DB::table('job_images')->where('job_id', $job_detail_id)->delete();
                    foreach ($request->file('files') as $file) {
                        $filename = Str::random(10) . '_' . $file->getClientOriginalName();
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
                    DB::table('job_kld_images')->where('job_id', $job_detail_id)->delete();
                    foreach ($request->file('kld') as $file) {
                        $filename = Str::random(10) . '_' . $file->getClientOriginalName();
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
                    DB::table('job_suppression_images')->where('job_id', $job_detail_id)->delete();
                    foreach ($request->file('kld') as $file) {
                        $filename = Str::random(10) . '_' . $file->getClientOriginalName();
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
                    DB::table('job_approve_image')->where('job_id', $job_detail_id)->delete();
                    foreach ($request->file('approve') as $file) {
                        $filename = Str::random(10) . '_' . $file->getClientOriginalName();
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
                    JobMetal::updateOrCreate(
                        ['job_detail_id' => $job_detail_id],
                        $metal
                    );
                }

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
                        
            $job_name = JobNames::where('job_name', $request->job_name)->first();

            if ($job_name) {
                return redirect()->back()->with('error', 'Job Name already exists.');
            }
            else{
                $job_name = new JobNames();
                $job_name->job_name = $request->input('job_name');
                $job_name->save();
                $job_id = $job_name->id;
            }

            $jobdetails = new JobDetails();
            $jobdetails->job_type_id = $request->input('job_type');
            $jobdetails->job_unique_code = $request->input('job_code');
            $jobdetails->party_id = $party_id;
            $jobdetails->job_name_id = $job_id;
            $jobdetails->printing_type = $request->input('printing_type');
            $jobdetails->bag_type = $request->input('job.bag_type');
            $jobdetails->bag_total_weight = $request->input('bag_total_weight');
            $jobdetails->bag_circum = $request->input('job.bag_circum');
            $jobdetails->bag_pet = $request->input('job.bag_pet');
            $jobdetails->bag_gazette = $request->input('job.bag_gazette', 0);
            $jobdetails->job_description = $request->input('job.job_description');
            $jobdetails->is_metallized = $request->input('bopp_metal_type');
            $jobdetails->approval_status = '0';
            $jobdetails->submit_date = $request->input('submit_date'); 
            $jobdetails->job_status = $request->job_status;
            $jobdetails->saved_by = \Auth::User()->id;
            $jobdetails->colors = implode(', ',$request->colors); 
            $jobdetails->colors_cmyk = implode(', ',$request->colors_cmyk);


            if ($jobdetails->save()) {                   
                $job_detail_id = $jobdetails->id;               
                $destinationPath = public_path('images/job-images/');

                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $now = Carbon::now();
                // Handle Mockup Images
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $filename = Str::random(10) . '_' . $file->getClientOriginalName();
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
                        $filename = Str::random(10) . '_' . $file->getClientOriginalName();
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
                        $filename = Str::random(10) . '_' . $file->getClientOriginalName();
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
                        $filename = Str::random(10) . '_' . $file->getClientOriginalName();
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
                return redirect()->route('jobdetails.view.pending')->with('success', 'Job details added successfully.');
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

    private function uploadFiles(Request $request, $key, $destinationPath)
    {
        if ($request->hasFile($key)) {
            foreach ($request->file($key) as $file) {
                if ($file && $file->isValid()) {
                    $filename = time().'_'.uniqid().'_'.$file->getClientOriginalName();
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

        if ($jobdetail->delete()) {
            return back()->with('success', 'JobDetails deleted successfully.');
        } else {
            return back()->with('error', 'Something went wrong.');
        }
    }

    public function multidelete(Request $request){
        $ids = $request->selectedIds;
        // print_r($ids); exit;
        $jobdetails = JobDetails::whereIn('id', $ids)->delete();
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
