<?php

namespace App\Http\Controllers\Site;

use App\Action\Subscription\CreatePlan;
use App\Action\Subscription\CreateSubscription;
use App\Action\Subscription\VerifyTransaction;
use App\Http\Controllers\Controller;
use App\Subscription;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(){
        $subscriptions = Subscription::where('user_id', Sentinel::getUser()->id)->paginate(10);
        $hasSubscription = Subscription::where('user_id', Sentinel::getUser()->id)->latest()->first();
        if ($hasSubscription && $hasSubscription->status == 1) {
            $activeSubscription = 1;
        } else {
           $activeSubscription = 0;
        }
        return view('site.pages.subscription.index', compact('subscriptions', 'activeSubscription'));
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