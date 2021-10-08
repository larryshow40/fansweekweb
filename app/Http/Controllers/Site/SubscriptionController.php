<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    public function index(){
        return view('site.pages.subscription.index');
    }
}