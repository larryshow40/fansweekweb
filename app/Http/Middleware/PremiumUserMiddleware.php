<?php

namespace App\Http\Middleware;

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
        if ($hasSubscription && $hasSubscription->status == true) {
            return $next($request);
        } else {
            // abort(404);
            return redirect()->route('site.subscription.index')->with('error', 'Please subscribe to view page!');
        }
    }
}
