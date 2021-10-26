<?php

namespace App\Http\Middleware;

use App\FreeSubscription;
use App\Subscription;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;

class PremiumUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $hasSubscription = Subscription::where('user_id', Sentinel::getUser()->id)->latest()->first();
        if ($hasSubscription && $hasSubscription->status == 1) {
            return $next($request);
        }elseif(FreeSubscription::where("user_id", Sentinel::getUser()->id)->first()){
            return $next($request);
        } else {
            // abort(404);
            return redirect()->route('site.subscription.index')->with('error', 'Please subscribe to view page!');
        }
    }
}
