<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Party;
use App\Models\PPItem;
use App\Models\NonWovenItem;
use App\Models\PPWovenItem;



class MasterAjaxController extends Controller
{
    public function saveParty(Request $request){
        $party = new Party();

        $party->party_name = $request->party_name;

        if ($party->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Party saved successfully',
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Error saving party',
            ]);
        }
    }

    public function checkbopp(Request $request){
        $code = $request->value;
        $check_item = PPItem::where('item_code', $code)->first();
        

        if ($check_item) {
            return response()->json([
                'status' => false,
                'message' => 'Item Already Exists'
            ]);
        }
        else{
            $bopp_code = $code;
    
            if (preg_match('/^([A-Z])(\d+)\/(\d+)$/', $bopp_code, $matches)) {
                $category = $matches[1];      // G
                $size = $matches[2];          // 1020
                $bopp_micron = $matches[3];   // 20
                
                return response()->json([
                    'status' => true,
                    'message' => 'Item not exists',
                    'size' => $size,
                    'category' => $category,
                    'micron' => $bopp_micron
                ]);
            }
        }

    }

    public function saveboppItem(Request $request){

        $allItems = PPItem::all();
        foreach($allItems as $item){
            if($item->item_code == $request->item_code){
                return response()->json([
                    'success' => false,
                    'message' => 'Item already exists',
                ]);
            }
        }
        $item = new PPItem();
        
        $bopp_code = $request->item_code;
        $size = $request->item_size ;
        $bopp_micron = $request->item_micron;
        $item_category  = $request->item_category;
    
        $item->item_code = $bopp_code;
        $item->bopp_size = $size;
        $item->bopp_category = $item_category;
        $item->bopp_micron = $bopp_micron;

        // dd('hii'); exit;

        if ($item->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Bopp item saved successfully',
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Error Bopp Item party',
            ]);
        }
    }

    public function checkfabric(Request $request){
        $code = $request->value;
        $check_item = NonWovenItem::where('item_code', $code)->first();

        if ($check_item) {
            return response()->json([
                'status' => false,
                'message' => 'Item Already Exists'
            ]);
        }
        else{
            $fabric_code = $code;
    
            if (preg_match('/^([A-Z]+)(\d+)\/(\d+)$/', $fabric_code, $matches)) {
                $non_color = $matches[1];      // G
                $non_size = $matches[2];          // 1020
                $non_gsm = $matches[3];   // 20
                
                return response()->json([
                    'status' => true,
                    'message' => 'Item not exists',
                    'color' => $non_color,
                    'size' => $non_size,
                    'gsm' => $non_gsm
                ]);
            }
            else{
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Code'
                ]);
            }
        }
    }

    public function savefabricItem(Request $request){

        $allItems = NonWovenItem::all();
        foreach($allItems as $item){
            if($item->item_code == $request->item_code){
                return response()->json([
                    'success' => false,
                    'message' => 'Item already exists',
                ]);
            }
        }
        $item = new NonWovenItem();
        
        $fabric_code = $request->item_code;
    
        $item->item_code  = $fabric_code;
        $item->non_color = $request->item_color_fibre;
        $item->non_size = $request->item_size_fibre;
        $item->non_gsm = $request->item_gms_fibre;        

        if ($item->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Fabric item saved successfully',
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Error in Saving Fabric Item',
            ]);
        }
    }


    public function checkfabricpp(Request $request){
        $code = $request->value;
        $check_item = PPWovenItem::where('item_code', $code)->first();

        if ($check_item) {
            return response()->json([
                'status' => false,
                'message' => 'Item Already Exists'
            ]);
        }
        else{
            $fabric_code = $code;
    
            if (preg_match('/^([A-Z]+)(\d+)\/(\d+(?:\.\d+)?)$/', $fabric_code, $matches)) {
                $category = $matches[1];      // G
                $size = $matches[2];          // 1020
                $gms = $matches[3];   // 20
                
                return response()->json([
                    'status' => true,
                    'message' => 'Item not exists',
                    'pp_category' => $category,
                    'pp_size' => $size,
                    'pp_gms' => $gms
                ]);
            }
            else{
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Code'
                ]);
            }
        }
    }



    public function savefabricppItem(Request $request){

        $allItems = PPWovenItem::all();
        foreach($allItems as $item){
            if($item->item_code == $request->item_code){
                return response()->json([
                    'success' => false,
                    'message' => 'Item already exists',
                ]);
            }
        }
        $item = new PPWovenItem();
        
        $fabric_code = $request->item_code;

        $item->item_code  = $fabric_code;
        $item->pp_category = $request->item_category_fibre_pp;
        $item->pp_size = $request->item_size_fibre_pp;
        $item->pp_gms = $request->item_gms_fibre_pp;        

        if ($item->save()) {
            return response()->json([
                'success' => true,
                'message' => 'PP Woven item saved successfully',
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Error in Saving PP Woven Item',
            ]);
        }
    }
}
