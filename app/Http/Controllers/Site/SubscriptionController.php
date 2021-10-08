<?php

namespace App\Http\Controllers\Site;

use App\Action\Subscription\CreatePlan;
use App\Action\Subscription\CreateSubscription;
use App\Action\Subscription\VerifyTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(){
        return view('site.pages.subscription.index');
    }

    public function subscribe(){
        // return CreatePlan::create();
        return redirect()->away(CreateSubscription::create()['data']['authorization_url']);
    }

    public function handleCallback(Request $request){
        if(VerifyTransaction::verify($request->reference)['data']['status'] == "success"){
            return redirect()->route('home')->with('success','Subscription Successful');
        }else{
            return redirect()->route('home')->with('error','Something went wrong, Please try again!');
        }
    }
}