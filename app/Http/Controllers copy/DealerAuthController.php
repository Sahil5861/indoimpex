<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Country;
use App\Models\Dealer;
use App\Models\User;
use App\Models\Plan;
use App\Models\DealersPlan;
use App\Models\Payment;

use App\Mail\OTPMail;

use Razorpay\Api\Api;

class DealerAuthController extends Controller
{
    public function dealerRegister(){
        $countries = Country::all();
        return view('auth.dealer.register', compact('countries'));
    }
    public function dealerstore(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'contact_person_id' => 'nullable|max:6',
            'authenticated' => 'required',
            'gst_no' => 'nullable|string|max:15|required_if:authenticated,1',

            // contact person validation
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
            'role_id' => 'required',
            'password' => 'required|min:8',
        ]);

        $dealer = new Dealer();

        $dealer->business_name = $request->input('name');
        $dealer->business_email = $request->input('email');
        $dealer->phone_number = $request->input('phone');
        $dealer->city = $request->input('city');
        $dealer->state = $request->input('state');
        $dealer->country = $request->input('country');
        $dealer->authenticated = $request->input('authenticated');
        $dealer->GST_number = $request->input('gst_no');
        $dealer->save(); 
        
        $user = new User();
        $user->name = $request->input('contact_name');
        $user->email = $request->input('contact_email');
        $user->phone = $request->input('contact_phone');
        $user->role_id = $request->input('role_id');
        $user->password =Hash::make($request->input('password'));
        $user->real_password = $request->input('password');
        $user->dealers_id = $dealer->id; // Associate with the dealer
        $user->OTP = rand(100000, 999999);
        $user->save();

        // Send OTP To user's email

        $email = $user->email;
        $dealer_email = $dealer->business_email;
        // $email = env('Email');
        $msg = "Verify OTP";
        $otp = $user->OTP;
        $userid = $user->id;
        $dealerid = $dealer->id;


        Mail::to($email)->send(new OTPMail($otp, $id));
        Mail::to($dealer_email)->send(new OTPMail($otp, $dealerid));
        // return response()->json(['message' => 'OTP email sent successfully']);

        
        return redirect()->route('dealer.email.verify', ['id'=>$user->id]);
    }

    public function emailverify($id){
        $user = User::findOrFail($id);
        return view('auth.dealer.email',compact('user'));
    }

    public function emailverifycheck(Request $request ,$id){
        $user = User::findOrFail($id);
        if($user->OTP == $request->input('otp')){
            $user->email_verified_at = now();
            $user->OTP = null;
            $user->save();

            Auth::login($user);

            return redirect()->route('dealer.plans', ['id'=>$user->id]);
        }
        else{
            return back()->with('error', 'Invalid OTP');
        }
    }

    public function dealerPlans($id){
        $plans = Plan::where('deleted_at', null)->get();
        $dealer = Dealer::where('id', $id)->get();
        return view('auth.dealer.plans', compact('plans', 'dealer'));
    }
    public function checkOut($planid){
        $plan = Plan::where('id', $planid)->first();
        
        
        return view('auth.dealer.checkout', compact('plan'));
    }
    public function procesPayment(Request $request){
        $plan = Plan::findOrFail($request->plan_id);
        $dealer = Auth::user();
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $order = $api->order->create([
            'receipt' => 'order_'.time(),
            'amount' => $plan->price * 100, // amount in paise
            'currency' => 'INR',
        ]);
        return view('auth.dealer.payment', [
            'orderId' => $order['id'],
            'plan' => $plan,
            'dealer' => $dealer,
        ]);
    }

    public function paymentSuccess(Request $request){
        // Verify payment and update the database as needed
        $input = $request->all();
        
        // Initialize the Razorpay API
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    
        // Retrieve the payment details
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
            
        // Verify the payment signature

        $attributes = [
            'razorpay_order_id' => $input['razorpay_order_id'],
            'razorpay_payment_id' => $input['razorpay_payment_id'],
            'razorpay_signature' => $input['razorpay_signature']
        ];

        $api->utility->verifyPaymentSignature($attributes);

        $plan = Plan::where('id',$request->input('plan_id'))->first();
        
        
        $payment = new Payment;
        $payment->user_id = Auth::id();  // Storing the ID of the currently authenticated user
        $payment->plan_id = $request->input('plan_id');
        $payment->razorpay_payment_id = $request->input('razorpay_payment_id');
        $payment->razorpay_order_id = $request->input('razorpay_order_id');
        $payment->amount = $request->input('amount');
        $payment->currency = 'INR'; 
        $payment->save();


        // Add to Dealers Plan
        $dealersplan = new DealersPlan();
        $dealersplan->dealer_id = Auth::id();
        $dealersplan->name = $plan->name;
        $dealersplan->description = $plan->description;
        $dealersplan->price = $plan->price;
        $dealersplan->special_price = $plan->special_price;    
        $dealersplan->expiry_date = $plan->expiry_date; 
        
        $dealersplan->save();
        return redirect()->route('dashboard')->with('success', 'Payment successful! Plan activated.');
    }

}
