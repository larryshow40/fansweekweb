<?php

namespace App\Http\Controllers\Api;

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
use App\Subscription;
use App\SubscriptionTransaction;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255'],
            'password'      => ['required', 'string', 'min:6'],
            'phone'         => ['min:11', 'max:14'],
            'dob'           => 'required',
            'gender'        => 'required',
        ]);
        try {

            $user = User::where('email', $request->email)->first();

            if (!blank($user)) {
                if ($user->withActivation == "") {

                    $user->password             = bcrypt($request->password);
                    $user->first_name           = $request->first_name;
                    $user->last_name            = $request->last_name;
                    $user->dob                  = $request->dob;
                    $user->phone                = $request->phone;
                    $user->gender               = $request->gender;
                    $user->is_password_set      = 1;
                    $user->save();

                    $activation         = Activation::create($user);

                    sendMail($user, $activation->code, 'activate_account', $request->password);

                    return $this->returnToken($user, $request);
                } else {
                    return response()->json([
                        "status" => false,
                        'message' => "Email already exists.",
                    ]);
                }
            }

            $user               = Sentinel::register($request->all());
            $role               = Sentinel::findRoleBySlug('user');

            $role->users()->attach($user);

            $activation         = Activation::create($user);

            sendMail($user, $activation->code, 'activate_account', $request->password);

            return $this->returnToken($user, $request);
        } catch (\Exception $e) {
            return $this->resourceError($e->getMessage());
        }
    }
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email'         => ['required', 'string', 'email', 'max:255'],
                'password'      => ['required', 'string'],
            ]);
            $user = User::where('email', $request->email)->first();

            if (blank($user)) {
                return response()->json([
                    "status" => false,
                    'message' => "Invalid Credentials",
                ]);
            } elseif ($user->is_user_banned == 0) {
                return response()->json([
                    "status" => false,
                    'message' => "Your account has been banned.",
                ]);
            }

            if (!Hash::check($request->get('password'), $user->password)) {
                return response()->json([
                    "status" => false,
                    'message' => "Invalid Credentials",
                ]);
            }

            $user = Sentinel::authenticate($request->all());

            return $this->returnToken($user, $request);
        } catch (\Exception $e) {
            return $this->resourceError($e->getMessage());
        }
    }
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
                $like->company_code_id =  $request->code_id ?? $_POST['code_id'];
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
                $dislike->company_code_id =  $request->code_id;
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
        $codes = CompanyCode::where('end_date', '<=', Carbon::now()->toDateTimeString())->get();

        return response()->json([
            "status" => true,
            'message' => "Codes Retrieved Successfully",
            "data" => $codes
        ]);
    }

    public function paystackWebhook(Request $request)
    {

        $ip = $request->ip();
        // if($ip == '52.31.139.75' || $ip == '52.49.173.169' ||$ip == '52.214.14.220'){
            $paymentDetails = $request->all();
            switch ($paymentDetails['event']) {
                case 'subscription.create':
                    try {
                        $data = $paymentDetails['data'];
                        DB::beginTransaction();
                        $subscription = new Subscription();
                        $subscription->subscription_code = $data['subscription_code'];
                        $subscription->customer_email = $data['customer']['email'];
                        $subscription->user_id = User::firstWhere('email', $data['customer']['email'])->id;
                        $subscription->customer_code = $data['customer']['customer_code'];
                        $subscription->amount = $data['amount']/100;
                        $subscription->subscription_status = $data['status'];
                        $subscription->status = 1;
                        $subscription->next_payment_date = $data['next_payment_date'];
                        $subscription->plan_code = $data['plan']['plan_code'];
                        $subscription->authorization = $data['authorization'];
                        $subscription->save();
                        DB::commit();
                        return response()->json([
                            'message' => 'Subscription Created Successfully',
                        ], 200);
                    } catch (\Exception $e) {
                        return response()->json([
                            'message' => $e->getTraceAsString(),
                        ], 500);
                    }
                    
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

                        if(SubscriptionTransaction::where('email', $data['customer']['email'])->count() > 0){
                            $lastSubscription = Subscription::where('customer_email', $data['customer']['email'])->latest()->where('status', 1)->where('subscription_status', 'active')->first();
                            if($lastSubscription){
                                $lastSubscription->next_payment_date = Carbon::parse($lastSubscription->next_payment_date)->addMonth();
                                $lastSubscription->update();
                            }
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
        // }
    }

}
