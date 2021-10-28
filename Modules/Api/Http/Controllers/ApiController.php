<?php

namespace Modules\Api\Http\Controllers;

use App\CompanyCode;
use App\CompanyCodeComment;
use App\Dislike;
use App\Http\Controllers\Controller;
use App\Like;
use Modules\User\Entities\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use carbon\carbon;
use Activation;
use App\Action\Subscription\CancelSubscription;
use App\Action\Subscription\CreateSubscription;
use App\Http\Resources\CompanyCodeResource;
use App\Subscription;
use App\SubscriptionTransaction;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use App\Action\Subscription\VerifyTransaction;

class ApiController extends Controller
{

    public function storeCode(Request $request)
    {
        return Sentinel::getUser()->id;
        if (CompanyCode::where('code', $request->code)->Where('name', $request->name)->exists()) {
        return redirect()->back()->with('error', 'Oops! Code exists already');;
        } else {
        $code = new CompanyCode;
        $code->user_id = Sentinel::getUser()->id;
        $code->name = $request->name;
        $code->code = $request->code;
        $code->save();
        return response()->json([
        "status" => true,
        'message' => "Added Successfully",
        "data" => $code
        ]);
        }
    }


    public function likeCode(Request $request)
    {
    if (Dislike::where('company_code_id', $request->code_id)->where('user_id', Sentinel::getUser()->id)->first()) {
    $getLiked = false;
    } else {
    if ($like = Like::where('company_code_id', $request->code_id)->where('user_id', Sentinel::getUser()->id)->first()) {
    $like->delete();
    } else {
    $like = new Like();
    $like->user_id = Sentinel::getUser()->id;
    $like->company_code_id = $request->code_id ?? $_POST['code_id'];
    $like->save();
    }
    }

    $getLiked = Like::where('company_code_id', $request->code_id)->where('user_id', Sentinel::getUser()->id)->exists();

    return response()->json([
    "status" => true,
    'message' => "Liked",
    "data" => $getLiked
    ]);
    }

    public function dislikeCode(Request $request)
    {
    if (Like::where('company_code_id', $request->code_id)->where('user_id', Sentinel::getUser()->id)->first()) {
    $getdisLiked = false;
    } else {
    if ($dislike = Dislike::where('company_code_id', $request->code_id)->where('user_id', Sentinel::getUser()->id)->first()) {
    $dislike->delete();
    } else {
    $dislike = new Dislike;
    $dislike->user_id = Sentinel::getUser()->id;
    $dislike->company_code_id = $request->code_id;
    $dislike->save();
    }
    $getdisLiked = Dislike::where('company_code_id', $request->code_id)->where('user_id', Sentinel::getUser()->id)->exists();
    }
    return response()->json([
    "status" => true,
    'message' => "Disiked",
    "data" => $getdisLiked
    ]);
    }

    public function storeComment(Request $request)
    {
    if (empty($request->body)) {
    return redirect()->back()->with('error', 'Please fill the comment field');
    }

    $input = $request->all();
    $input['user_id'] = Sentinel::getUser()->id;

    $comment = CompanyCodeComment::create($input);

    return response()->json([
    "status" => true,
    'message' => "Added Successfully",
    "data" => $comment
    ]);
    }

    public function listCodes()
    {
    // $codes = CompanyCode::where('end_date', '<=', Carbon::now()->toDateTimeString())->paginate(10);
        $codes = CompanyCode::with('user')->withCount(['likes', 'dislikes'])->where('end_date', '<=', Carbon::now()->toDateTimeString())->get();
            return CompanyCodeResource::collection($codes);
            // return response()->json([
            // "status" => true,
            // 'message' => "Codes Retrieved Successfully",
            // "data" => $codes
            // ]);
            }

            // public function paystackWebhook(Request $request)
            // {

            // // $ip = $request->ip();
            // // if($ip == '52.31.139.75' || $ip == '52.49.173.169' ||$ip == '52.214.14.220'){
            // $paymentDetails = $request->all();
            // return $paymentDetails;
            // switch ($paymentDetails['event']) {
            // case 'subscription.create':
            // try {
            // $data = $paymentDetails['data'];
            // DB::beginTransaction();
            // $subscription = new Subscription();
            // $subscription->subscription_code = $data['subscription_code'];
            // $subscription->customer_email = $data['customer']['email'];
            // $subscription->user_id = User::firstWhere('email', $data['customer']['email'])->id;
            // $subscription->customer_code = $data['customer']['customer_code'];
            // $subscription->amount = $data['amount']/100;
            // $subscription->subscription_status = $data['status'];
            // $subscription->status = 1;
            // $subscription->next_payment_date = $data['next_payment_date'];
            // $subscription->plan_code = $data['plan']['plan_code'];
            // $subscription->authorization = $data['authorization'];
            // $subscription->save();

            // // $transaction = new SubscriptionTransaction();
            // // $transaction->reference = $data['subscription_code'];
            // // $transaction->user_id = User::firstWhere('email', $data['customer']['email'])->id;
            // // $transaction->amount = $data['amount'] / 100;
            // // $transaction->paid_at = $data['createdAt'];
            // // $transaction->email = $data['customer']['email'];
            // // $transaction->status = $data['status'];
            // // $transaction->save();
            // DB::commit();
            // return response()->json([
            // 'message' => 'Subscription Created Successfully',
            // ], 200);
            // } catch (\Exception $e) {
            // return response()->json([
            // 'message' => $e->getTraceAsString(),
            // ], 500);
            // }

            // case 'charge.success':
            // try {
            // $data = $paymentDetails['data'];

            // DB::beginTransaction();

            // $transaction = new SubscriptionTransaction();
            // $transaction->reference = $data['reference'];
            // $transaction->user_id = User::firstWhere('email', $data['customer']['email'])->id;
            // $transaction->amount = $data['amount'] / 100;
            // $transaction->paid_at = $data['paid_at'];
            // $transaction->email = $data['customer']['email'];
            // $transaction->status = $data['status'];

            // if(SubscriptionTransaction::where('email', $data['customer']['email'])->count() > 0){
            // $lastSubscription = Subscription::where('customer_email', $data['customer']['email'])->latest()->where('status', 1)->where('subscription_status', 'active')->first();
            // if($lastSubscription){
            // $lastSubscription->next_payment_date = Carbon::parse($lastSubscription->next_payment_date)->addMonth();
            // $lastSubscription->update();
            // }
            // }
            // $transaction->save();
            // DB::commit();
            // return response()->json([
            // 'message' => 'Subscription Transaction Successful',
            // ], 200);
            // } catch (\Exception $e) {
            // return response()->json([
            // 'message' => $e->getMessage(),
            // ], 500);
            // }

            // case 'invoice.payment_failed':
            // try {
            // $data = $paymentDetails['data'];

            // DB::beginTransaction();

            // $lastSubscription = Subscription::where('customer_email', $data['customer']['email'])->latest()->where('status', 1)->where('subscription_status', 'active')->first();
            // if ($lastSubscription) {
            // $lastSubscription->subscription_status = 'cancelled';
            // $lastSubscription->status = 0;
            // $lastSubscription->update();
            // }
            // DB::commit();
            // return response()->json([
            // 'message' => 'Subscription Transaction Failed',
            // ], 200);
            // } catch (\Exception $e) {
            // return response()->json([
            // 'message' => $e->getMessage(),
            // ], 500);
            // }
            // break;
            // default:
            // }


            // // }
            // }

            public function paystackWebhook(Request $request)
            {
            $ip = $request->ip();
            $paymentDetails = $request->all();
            switch ($paymentDetails['event']) {
            case 'charge.success':
            try {
            $data = $paymentDetails['data'];

            DB::beginTransaction();

            $transaction = new SubscriptionTransaction();
            $transaction->reference = $data['reference'];
            $transaction->user_id = User::firstWhere('email', $data['customer']['email'])->id;
            $transaction->amount = $data['amount'] / 100;
            $transaction->paid_at = $data['paid_at'];
            $transaction->email = $data['customer']['email'];
            $transaction->status = $data['status'];

            $lastSubscription = Subscription::where('customer_email', $data['customer']['email'])->latest()->where('status', 1)->where('subscription_status', 'active')->first();

            if($lastSubscription){
            $lastSubscription->next_payment_date = Carbon::parse($lastSubscription->next_payment_date)->addMonth();
            $lastSubscription->update();
            }else{
            CreateSubscription::create($data['customer']['email']);
            }
            $transaction->save();
            DB::commit();
            return response()->json([
            'message' => 'Subscription Transaction Successful',
            ], 200);
            } catch (\Exception $e) {
            return response()->json([
            'message' => $e->getMessage(),
            ], 500);
            }
            case 'invoice.payment_failed':
            try {
            $data = $paymentDetails['data'];

            DB::beginTransaction();
            $lastSubscription = Subscription::where('customer_email', $data['customer']['email'])->latest()->where('status', 1)->where('subscription_status', 'active')->first();
            if ($lastSubscription) {
            $lastSubscription->subscription_status = 'cancelled';
            $lastSubscription->status = 0;
            $lastSubscription->update();
            CancelSubscription::cancel($lastSubscription->subscription_code, $lastSubscription->email_token);
            }
            DB::commit();
            return response()->json([
            'message' => 'Subscription Transaction Failed',
            ], 200);
            } catch (\Exception $e) {
            return response()->json([
            'message' => $e->getMessage(),
            ], 500);
            }

            break;
            default:
            }

            }

            public function testwebhook(){
            $client = new Client();
            $response = $client->post(
            'https://fansweek.com/api/paystack/webhook',
            [
            'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' =>'Bearer '.config('paystack.secretKey'),
            ],

            'json' => [
            "event" => "subscription.create",
            "data" => [
            "domain" => "test",
            "status" => "active",
            "subscription_code" => "SUB_20810j9og1hw2sy",
            "amount" => 50000,
            "cron_expression" => "0 0 28 * *",
            "next_payment_date" => "2016-05-19T07:00:00.000Z",
            "open_invoice" => null,
            "createdAt" => "2016-03-20T00:23:24.000Z",
            "plan" => [
            "name" => "Monthly retainer",
            "plan_code" => "PLN_8wa5t89ms15a8en",
            "description" => null,
            "amount" => 100,
            "interval" => "monthly",
            "send_invoices" => true,
            "send_sms" => true,
            "currency" => "NGN"
            ],
            "authorization" => [
            "authorization_code" => "AUTH_96xphygz",
            "bin" => "539983",
            "last4" => "7357",
            "exp_month" => "10",
            "exp_year" => "2017",
            "card_type" => "MASTERCARD DEBIT",
            "bank" => "GTBANK",
            "country_code" => "NG",
            "brand" => "MASTERCARD",
            "account_name" => "BoJack Horseman"
            ],
            "customer" => [
            "first_name" => "BoJack",
            "last_name" => "Horseman",
            "email" => "lanreshorinwa@gmail.com",
            "customer_code" => "CUS_xnxdt6s1zg1f4nx",
            "phone" => "",
            "metadata" => [],
            "risk_action" => "default"
            ],
            "created_at" => "2016-10-01T10:59:59.000Z"
            ]
            ]

            ]
            );
            return json_decode($response->getBody(), true);
            }



            public function paystackCallback(Request $request)
            {
            if (VerifyTransaction::verify($request->reference)['data']['status'] == "success") {
            return redirect()->route('home')->with('success', 'Subscription Successful');
            } else {
            return redirect()->route('home')->with('error', 'Something went wrong, Please try again!');
            }
            }

            public function testing(){
            dd(Subscription::all(), SubscriptionTransaction::all());
            }
}