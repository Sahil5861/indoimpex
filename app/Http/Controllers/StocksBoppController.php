<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bopp;
use App\Models\BoppRoll;
use App\Models\BoppIssue;

use Carbon\Carbon;
use DataTables;

class StocksBoppController extends Controller
{
    public function index(Request $request)
    {               
        return view('admin.stocks.bopp.index');
    }  

    public function indexroll (Request $request)
    {               
        return view('admin.stocks.bopp.index_roll');
    }  

    public function index_received(Request $request){
        if ($request->ajax()) {
            $bopp_received = Bopp::orderBy('submit_date', 'desc')->get();

            return DataTables::of($bopp_received)  
                ->addIndexColumn()                              
                ->editcolumn('bopp_category', function ($row){
                    $boppcategories = [
                        'G' => 'GLOSS',
                        'M' => 'MATT',
                        'ME' => 'METALLISE',
                        'WCME' => 'W C METALLISE'                                   
                    ];                
                    return $boppcategories[$row->bopp_category];
                })
                ->editcolumn('bopp_roll', function ($row){                    
                    $bopproll = BoppRoll::where('bopp_id', $row->id)->first();
                    return $bopproll->roll_number;
                })
                ->editcolumn('bopp_weight', function ($row){                    
                    $bopproll = BoppRoll::where('bopp_id', $row->id)->first();
                    return $bopproll->weight;
                })
                ->editColumn('add_date', function ($row){                    
                    return \Carbon\Carbon::parse($row->submit_date)->format('d M y, h:i A');
                })

                ->rawColumns(['bopp_category', 'bopp_roll', 'bopp_weight', 'add_date'])
                ->make(true);
        }
        return view('admin.stocks.bopp.received');
    }

    public function index_issued(Request $request){
        if ($request->ajax()) {
            $bopp_received = BoppIssue::orderBy('created_at', 'desc')->get();

            return DataTables::of($bopp_received)  
                ->addIndexColumn()  
                ->editcolumn('bopp_roll', function ($row){                    
                    $bopproll = BoppRoll::where('id', $row->bopp_roll_id)->first();
                    return $bopproll->roll_number;
                })                                            
                ->editColumn('add_date', function ($row){                    
                    return \Carbon\Carbon::parse($row->submit_date)->format('d M y, h:i A');
                })
                ->rawColumns(['bopp_roll', 'add_date'])
                ->make(true);
        }
        return view('admin.stocks.bopp.issued');
    }

    public function getBoppData(Request $request)
    {
        if ($request->ajax()) {
            $data = Bopp::whereIn('id', function ($query) {
                    $query->selectRaw('MAX(id)')
                        ->from('bopp')
                        ->groupBy('item_code');
            })->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('bopp_category', function ($row) {
                    $categories = [
                        'G' => 'GLOSS',
                        'M' => 'MATT',
                        'ME' => 'METALLISE',
                        'WCME' => 'W C METALLISE'  
                    ];
                    return $categories[$row->bopp_category];
                })
                ->addColumn('balance', function ($row) {
                    $cutWastage = BoppIssue::itemCutWastage($row->item_code);
                    $rollUsed = BoppIssue::itemRollUsed($row->item_code);
                    $totalWeight = BoppRoll::sumUnique($row->item_code);

                    return $cutWastage > 0 || $rollUsed > 0
                        ? $totalWeight - ($cutWastage + $rollUsed)
                        : $totalWeight;
                })
                ->rawColumns(['balance'])
                ->make(true);
        }
    }


    public function getBoppRollData(Request $request){
        if ($request->ajax()) {
            $rolls = BoppRoll::with('bopp')->get();

            return DataTables::of($rolls)
                ->addIndexColumn()
                ->editColumn('bopp_size', function ($roll) {
                    return optional($roll->bopp)->bopp_size;
                })
                ->editColumn('roll_number', function ($roll) {
                    return $roll->roll_number;
                })
                ->addColumn('action', function ($roll){
                    $action2 = '<div class="d-flex justify-content-center align-items-center" style="gap:10px;">';
                    $action2 .= '<a href="#" onclick="addIssueBopp('.$roll->id.')" data-id="'.$roll->id.'" class="btn-sm" title="Add Issue">
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1 text-warning"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                                    </a>';  
                    $action2 .= '</div>';
                    return $action2;                    
                })
                ->editColumn('bopp_party_name', function ($roll) {
                    return optional($roll->bopp)->bopp_party_name;
                })
                ->editColumn('bopp_category', function ($roll) {
                    $categories = [
                        'G' => 'GLOSS',
                        'M' => 'MATT',
                        'ME' => 'METALLISE',
                        'WCME' => 'W C METALLISE'
                    ];
                    return $categories[optional($roll->bopp)->bopp_category] ?? 'N/A';
                })
                ->editColumn('bopp_micron', function ($roll) {
                    return optional($roll->bopp)->bopp_micron;
                })
                ->addColumn('balance', function ($roll) {
                    $issued = \App\Models\BoppIssue::where('bopp_roll_id', $roll->id)->first();
                    if ($issued) {
                        $cut = \App\Models\BoppIssue::sumCutWastage($roll->id);
                        $used = \App\Models\BoppIssue::sumRollUsed($roll->id);
                        return $roll->weight - ($cut + $used);
                    }
                    return $roll->weight;
                })
                ->rawColumns(['balance', 'action'])
                ->make(true);
        }
    }


    public function save(Request $request){
        
        $request->validate([
            'bopp.item_code'        => 'required|string|max:100',
            'bopp.submit_date'      => 'required|date_format:d-m-Y',
            'bopp.bopp_size'        => 'required|string|max:50',
            'bopp.bopp_category'    => 'required|string',
            'bopp.bopp_micron'      => 'required|numeric|min:1',
            'bopp.bopp_party_name'  => 'required|string|max:255',

            'roll-number'           => 'required|array|min:1',
            'roll-number.*'         => 'required|string|max:50',

            'weights'               => 'required|array|min:1',
            'weights.*'             => 'required|numeric|min:0.1',

            'remarks'               => 'nullable|array',
            'remarks.*'             => 'nullable|string|max:255',
        ]);

        

        

        $data = $request->input('bopp');
        $time = Carbon::now();

        // Sanitize input (optional if using Laravel's validation/guarding properly)
        $name = e($data['bopp_size']);
        $micron = e($data['bopp_micron']);
        $party_name = e($data['bopp_party_name']);
        $bopp_item = e($data['item_code']);
        $bopp_category = e($data['bopp_category']);
        $submit_date = e($data['submit_date']);

        // Save Bopp
        $bopp = new Bopp();
        $bopp->bopp_size = $name;
        $bopp->bopp_micron = $micron;
        $bopp->bopp_party_name = $party_name;
        $bopp->item_code = $bopp_item;        
        $bopp->bopp_category = $bopp_category;
        $bopp->submit_date = $time;

        // print_r($bopp); exit;
        if ($bopp->save()){
            foreach ($request->input('roll-number') as $key => $rollNumber) {
                $roll = new BoppRoll();
                $roll->item_code = $bopp_item;
                $roll->bopp_id = $bopp->id;
                $roll->roll_number = $rollNumber;
                $roll->weight = $request->input('weights')[$key];
                $roll->remarks = $request->input('remarks')[$key];                
                $roll->save();
            }
        }

        return redirect()->back()->with('success', 'The Bopp is created successfully.');
    }

    public function check_issue_bopp(Request $request){
        $bopp_item_code_issue = $request->bopp_item_code_issue;

        $items = BoppRoll::where('item_code', $bopp_item_code_issue)->get();
        if (count($items) > 0) {
            return response()->json([
                'status' => true,
                'data' => $items,            
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'No Bopp Roll found for this item code.',
            ]);
        }
    }

    public function saveIssue(Request $request){
        
        $bopp_issue = new BoppIssue();
        $time = Carbon::now();

        $bopp_issue->bopp_roll_id = $request->input('bopp_roll_id');
        $bopp_issue->item_code = $request->issue['item_code'];
        $bopp_issue->job_name = $request->issue['job_name'];
        $bopp_issue->cut_wastage = $request->issue['cut_wastage'];
        $bopp_issue->roll_used = $request->issue['roll_used'];
        $bopp_issue->submit_date = $time;

        // print_r($request->all()); exit;
        $bopp_issue->save();

        return redirect()->back()->with('success', 'The Bopp  Issue is created successfully.');
    }

    public function getBoppRoll(Request $request){
        $bopp_id = $request->input('bopp_issue_id');
        $bopp_roll = BoppRoll::find($bopp_id);
        $bopp_issue = Bopp::find($bopp_roll->bopp_id);
        $categories = [
                        'G' => 'GLOSS',
                        'M' => 'MATT',
                        'ME' => 'METALLISE',
                        'WCME' => 'W C METALLISE'
                    ];
        return response()->json([
            'status' => true,
            'data' => [
                'bopp_roll' => $bopp_roll,
                'bopp_issue' => $bopp_issue,
                'categories' => $categories
            ],
        ]);
    }

}
