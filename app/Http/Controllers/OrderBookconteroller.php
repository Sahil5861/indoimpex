<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderBook;
use App\Models\ProductionReport;
use App\Models\JobDetails;
use App\Models\JobNames;

use App\Models\JobBopp;
use App\Models\JobFabric;
use App\Models\Party;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\StreamedResponse;
use DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

class OrderBookconteroller extends Controller
{

    public function index(Request $request)
    {                
        if ($request->ajax()) {
            $query = OrderBook::query();

            $data = $query->orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()                
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                }) 
                ->addColumn('order_number', function ($row){
                    return $row->order_unique_number;
                })
                ->addColumn('party_name', function ($row){                    
                    $party =  Party::where('id', $row->party_id)->first();                    
                    return $party->party_name ?? '';
                })
                
                ->addColumn('job_name', function ($row){
                    return $job_name = JobNames::where('id', $row->order_job_code)->first()->job_name;
                })
                
                ->addColumn('quantity_pcs', function ($row){
                    $job_name = JobNames::where('id',$row->order_job_code)->first();
                    $job_details = JobDetails::where('job_name_id', $job_name->id)->first();

                    $total_weight = $job_details->bag_total_weight > 0 ? $job_details->bag_total_weight : 1;
                    
                    return $row->quantity_type == 'pc' ? $row->quantity : number_format((float) $row->quantity / $total_weight, 2, '.', '');                
                    // return $row->quantity_type == 'pc' ? $row->quantity : number_format((float) $row->quantity);                
                })

                ->addColumn('quantity_kg', function ($row){
                    $job_name = JobNames::where('id',$row->order_job_code)->first();
                    $job_details = JobDetails::where('job_name_id', $job_name->id)->first();
                    return $row->quantity_type == 'kg' ? $row->quantity : number_format((float)$job_details->bag_total_weight * $row->quantity * 0.001, 2, '.', '');                
                    // return $row->quantity_type == 'kg' ? $row->quantity : $row->quantity;                
                })
                // ->addColumn('job_name', function ($row){
                //     $order_job_code = JobDetails::where('id', $row->order_job_code)->first();
                //     $job_name = JobNames::where('id', $order_job_code->job_name_id)->first()->party_name;
                // })

                
                ->addColumn('submit_date', function ($row) {
                    return $row->submit_date ? \Carbon\Carbon::parse($row->submit_date)->format('d M Y') : '-';
                })         
                ->addColumn('deliver_by', function ($row) {
                    return $row->deliver_by ? \Carbon\Carbon::parse($row->deliver_by)->format('d M Y') : '-';
                })              
                ->addColumn('action', function ($row) {
                    
                    $action = '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">';                    
                    
                    if (hasPermission('Order Book Update', 'Update')) {
                        $action .= '<a href="#" onclick="editOrderBook('.$row->id.')" class="btn-sm" title="Update Order">
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="#006db5" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>';
                    }
                    if (hasPermission('Order Book Delete', 'Delete')) {
                        $action .= '<a href="#" data-url="' . route('admin.orderbooks.remove', $row->id) . '" data-id="' . $row->id . '" class="btn-sm delete-button" title="Delete Order">
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="#ef4444" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </a>';
                    }
                    $action .= '</div>';      

                    if (hasPermission('Order Book Update', 'Update') || hasPermission('Order Book Delete', 'Delete')) {                        
                        return $action;
                    }
                    else{
                        return '--';
                    }
                })
                ->rawColumns(['action', 'job_name'])
                ->make(true);
        }
        return view('admin.pages.orderbook.index');
    }  
    public function save(Request $request)
    {
        
        $request->validate([            
            'party_name' => 'required|string|max:255',                        
            'job_name' => 'required|string|max:255',                        
            'quantity' => 'required|integer',                        
            'quantity_type' => 'required',                        
            'deliver_by' => 'required',                        

        ]);

        

        $id = $request->input('id');

        
        $order_job_code = $request->order_job_code;
        
        
        

        $party_name = Party::where('party_name',$request->input('party_name'))->first();
        $main_order = !empty($id) ? OrderBook::where('id', $id)->first() :  new OrderBook();
        

        // print_r($request->order_unique_number); exit;

        $formatted_unique_number= 'INDIMX' . str_pad($request->order_unique_number, 3, '0', STR_PAD_LEFT);

        // $main_order->order_unique_number = $request->order_unique_number;
        $main_order->order_unique_number = $formatted_unique_number;
        $main_order->order_job_code = $request->order_job_code;

        $bopp_details = JobBopp::where('id', $order_job_code)->first();
        if ($bopp_details) {            
            $main_order->order_bopp_code = $bopp_details->bopp_item_code ?? '';
        }

        $fabric_details = JobFabric::where('id', $order_job_code)->first();
        if ($fabric_details) {            
            $main_order->order_fabric_code = $fabric_details->fabric_item_code ?? '';
        }
        $main_order->party_id = $party_name->id;        
        $main_order->quantity = $request->quantity;
        $main_order->quantity_type = $request->quantity_type;
        $main_order->deliver_by = $request->deliver_by;
        $main_order->submit_date = Carbon::now();

        // dd($main_order); exit;
        
        
        

        if ($main_order->save()) {

            if (empty($id)) {                
                $production_report = new ProductionReport();
    
                $production_report->pro_order_id = $main_order->order_unique_number;
                $production_report->status = '0';            
                $production_report->save();
            }

            return redirect()->route('orderbooks.items.view')->with('success', !empty($request->id) ? 'Ordder Added Successfully !' : 'Ordder Updated Successfully !');    
        }
        else{
            return redirect()->back()->with('error', 'Something went wrong !');
        }
    }

    public function getjobcodelist(Request $request, $val){
        $jobcode = JobDetails::where('job_unique_code', 'like', '%' . $val . '%')->pluck('job_unique_code');
        if ($jobcode) {                
            return response()->json([
                'status' => true,
                'jobcode' => $jobcode
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'Not found !'
            ]);
        }
    }

    public function getpartynamelist(Request $request, $val){
        $parties = Party::where('party_name', 'like', '%' . $val . '%')->pluck('party_name');
        if ($parties) {                
            return response()->json([
                'status' => true,
                'parties' => $parties
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'Not found !'
            ]);
        }
    }

    public function getOrderbookdata(Request $request){
        $orderbook = Orderbook::where('id', $request->id)->first();
        $response = [];
        if ($orderbook) {
            $response['orderbook'] = $orderbook;
            $party_name = Party::where('id', $orderbook->party_id)->first()->party_name ?? '';
            $jobName = JobNames::where('id', $orderbook->order_job_code)->first();            
            $job_name = $jobName->job_name;
            $job_id = $jobName->id;

            $JobDetails = JobDetails::where('job_name_id', $job_id)->first();

            


            // $job_name = Jobname::where('id', )
            $response['party_name'] = $party_name;
            $response['job_id'] = $job_id ?? '';
            $response['job_name'] = $job_name ?? '';
            $response['job_code'] = $JobDetails->job_unique_code ?? '';
            // $response['JobDetails_id'] = $JobDetails->id ?? '';
            // print_r($response); exit;


            return response()->json([
                'status' => true,
                'data' => $response
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => ' No data found !'
            ]);
        }
    }

    public function getjobcode(Request $request){
        $val = $request->jobcode;
        $jobcode = JobDetails::where('job_unique_code', $val)->first();
        
        $name_id = $jobcode->job_name_id;
        
        $job_name = JobNames::where('id', $name_id)->first();
        $party = Party::where('id', $jobcode->party_id)->first();

        if ($jobcode) {                
            return response()->json([
                'status' => true,
                'job_name' => $job_name,
                'party' => $party,
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'Not found !'
            ]);
        }
    }

    public function getjobcodeParty(Request $request){
        $name = $request->job_name;
        $job_name = JobNames::where('job_name', $name)->first();

        $response = [];
        if ($job_name) {            
            $response['job_name'] = $job_name;
            $job_details = JobDetails::where('job_name_id', $job_name->id)->first();
            $response['job_code'] = $job_details->job_unique_code;

            $party = Party::where('id', $job_details->party_id)->first();
            $response['party'] = $party;

            return response()->json([
                'status' => true,
                'data' => $response
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'Job Name Not found !'
            ]);
        }

        



    }

    public function getJobNames(Request $request){
        $job_name = $request->val;
        $job_names = JobNames::where('job_name', 'like', '%' . $job_name . '%')->get();

        if (count($job_names) > 0) {
            return response()->json([
                'status' => true,
                'job_names' => $job_names
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'No Job Names found !'
            ]);
        }
    }

    public function getData(Request $request)
    {
        $id = $request->id;        
        $party = OrderBook::findOrFail($id);
        return response()->json($party);
    }
   
    public function remove(Request $request, $id)
    {
        $order_book = OrderBook::findOrFail($id);

        if ($order_book->delete()) {
            return back()->with('success', 'OrderBook deleted successfully.');
        } else {
            return back()->with('error', 'Something went wrong.');
        }
    }

    public function updateStatus($id, Request $request)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $order_book = User::findOrFail($id);
        if ($order_book) {
            $order_book->status = $request->status;
            $order_book->save();
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
