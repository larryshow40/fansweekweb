<?php

namespace App\Http\Controllers\Site;

use App\Action\Subscription\CreatePlan;
use App\Action\Subscription\InitializeTransaction;
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
        return redirect()->away(InitializeTransaction::initialize()['data']['authorization_url']);
    }

    
}
