<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Dealer;

class paymentController extends Controller
{
    public function checkOut($planid){
        $plan = Plan::where('id', $planid)->first();
        
        return view('auth.dealer.checkout', compact('plan'));
    }
}
