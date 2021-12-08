<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Auth;
class PaymentController extends Controller
{
    
    
    public function pay(Request $request, $plan) {
        
        if($plan === 'monthly' ){
            $plan_price = 'price_1JwOIrBvAQf7YmD9RDv3jzjc';
        }

        if($plan === 'yearly'){
            $plan_price = 'price_1JwOIcBvAQf7YmD9iQA2pz4I';
        }
        

        try{
            $stripeCharge =  Auth::user()->newSubscription($plan, $plan_price)->create($request->pmethod);
        }catch(IncompletePayment $exception) {
            return redirect()->route('cashier.payment',
            [$exception->payment->id, 'redirect' => route('home')]
            );
        }
    
    }
}
