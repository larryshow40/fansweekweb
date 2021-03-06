<?php

namespace Modules\Common\Http\Controllers;

use App\Action\Subscription\CancelSubscription;
use App\Comment;
use App\CompanyCode;
use App\CompanyCodeComment;
use App\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use App\VisitorTracker;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\User\Entities\Activation;
use Modules\Post\Entities\Post;
use Modules\Setting\Entities\Setting;
use Session;

class CommonController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['totalVisits']               = VisitorTracker::get();
        $data['totalUniqueVisitors']       = VisitorTracker::where('date', 'like', date('Y') . '%')->get();
        $count = 0;
        foreach ($data['totalUniqueVisitors']->groupBy('ip') as $key => $visitor) {
            $count += $visitor->groupBy('url')->count();
        }

        $data['totalUniqueVisits']         = $count;
        $data['totalUniqueVisitors']       = $data['totalUniqueVisitors']->groupBy('ip')->count();

        $data['totalVisitors']             = $data['totalVisits']->groupBy('ip')->count();
        $data['usageBrowsers']             = $data['totalVisits']->groupBy('agent_browser');
        $data['registeredUsers']           = Activation::get();
        $data['publishedPost']             = Post::where('visibility', 1)->where('status', 1)->get();
        $data['submittedPost']             = Post::where('submitted', 1)->get();

        $month = date('Y-m');
        $visitors = VisitorTracker::where('date', 'like', '%' . $month . '%')->get();
        for ($i = 1; $i <= date('t'); $i++) {
            if ($i < 10) {
                $i = str_pad($i, 2, "0", STR_PAD_LEFT);
            }
            // visits count
            $visits                    = $visitors->where('date', date('Y-m-' . $i));
            $data['dates'][] = $i;
            $data['visits'][]          = $visits->count();
            //visitor count
            $data['visitors'][]        = $visits->groupBy('ip')->count();
        }

        $data['dates']                 = implode(',', $data['dates']);
        $data['visits']                = implode(',', $data['visits']);
        $data['visitors']              = implode(',', $data['visitors']);

        $data['posthits']              = Post::with('image')->orderBy('total_hit', 'DESC')->where('total_hit', '!=', 0)->paginate(10);

        $data['browserColor'] = ['#254f37', '#8f97db', '#db9cd0', '#dbc98f', '#9fdb8f', '#8fdbc3', '#8fcfdb', '#6F7841', '#a61616', '#051057'];


        return view('common::index', compact('data'));
    }

    public function companyCode()
    {
        $codes = CompanyCode::latest()->paginate(10);
        return view('common::codes', compact('codes'));
    }

    public function subscriptions()
    {
        $subscriptions = Subscription::paginate(10);
        return view('common::subscriptions', compact('subscriptions'));
    }

    public function cancelSubscription($id)
    {
        try {
            DB::beginTransaction();
            $subscription = Subscription::find($id);

            if ($subscription) {
                $subscription->subscription_status = 'cancelled';
                $subscription->status = 0;
                $subscription->update();
                CancelSubscription::cancel($subscription->subscription_code, $subscription->email_token);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Cancelled Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function storeCompanyCode(Request $request)
    {
        if (CompanyCode::where('code', $request->code)->Where('name', $request->name)->exists()) {
            return redirect()->back()->with('error', 'Oops! Code exists already');;
        } else {
            $code = new CompanyCode;
            $code->user_id = Sentinel::getUser()->id;
            $code->name = $request->name;
            $code->code = $request->code;
            $code->end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i');
            $code->save();
            return redirect()->back()->with('success', 'Added Successfully');
        }
    }

    public function deleteCompanyCode(Request $request, $id)
    {
        $code = CompanyCode::find($id)->delete();
        return redirect()->back()->with('success', 'Deleted Successfully');
    }

    public function updateCompanyCode(Request $request, $id)
    {
        $code = CompanyCode::find($id);
        $code->name = $request->name;
        $code->code = $request->code;
        $code->save();
        return redirect()->back()->with('success', 'Updated Successfully');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('common::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('common::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('common::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
