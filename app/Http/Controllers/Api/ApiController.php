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
use App\Action\Subscription\CancelSubscription;
use App\Action\Subscription\CreateSubscription;
use App\Http\Resources\CompanyCodeResource;
use App\Subscription;
use App\SubscriptionTransaction;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use App\Action\Subscription\VerifyTransaction;
use Dotenv\Regex\Success;
use Exception;

class ApiController extends Controller
{


    public function storeCode(Request $request)
    {
        if (CompanyCode::where('code', $request->bet_code)->Where('name', $request->bet_name)->exists()) {
            throw new Exception('error', 'Oops! Code exists already');;
        } else {
            $code = new CompanyCode;
            $code->user_id = Sentinel::getUser()->id;
            $code->name = $request->bet_company;
            $code->code = $request->bet_code;
            $code->end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i');
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
        // $codes = CompanyCode::where('end_date', '>=', Carbon::now()->toDateTimeString())->paginate(10);
        $codes = CompanyCode::with('user')->withCount(['likes', 'dislikes'])->where('end_date', '>=', Carbon::now()->toDateTimeString())->get();
        return CompanyCodeResource::collection($codes);
        // return response()->json([
        //     "status" => true,
        //     'message' => "Codes Retrieved Successfully",
        //     "data" => $codes
        // ]);
    }

    // public function paystackWebhook(Request $request)
    // {

    //     // $ip = $request->ip();
    //     // if($ip == '52.31.139.75' || $ip == '52.49.173.169' ||$ip == '52.214.14.220'){
    //         $paymentDetails = $request->all();
    //         return $paymentDetails;
    //         switch ($paymentDetails['event']) {
    //             case 'subscription.create':
    //                 try {
    //                     $data = $paymentDetails['data'];
    //                     DB::beginTransaction();
    //                     $subscription = new Subscription();
    //                     $subscription->subscription_code = $data['subscription_code'];
    //                     $subscription->customer_email = $data['customer']['email'];
    //                     $subscription->user_id = User::firstWhere('email', $data['customer']['email'])->id;
    //                     $subscription->customer_code = $data['customer']['customer_code'];
    //                     $subscription->amount = $data['amount']/100;
    //                     $subscription->subscription_status = $data['status'];
    //                     $subscription->status = 1;
    //                     $subscription->next_payment_date = $data['next_payment_date'];
    //                     $subscription->plan_code = $data['plan']['plan_code'];
    //                     $subscription->authorization = $data['authorization'];
    //                     $subscription->save();

    //                     // $transaction = new SubscriptionTransaction();
    //                     // $transaction->reference = $data['subscription_code'];
    //                     // $transaction->user_id = User::firstWhere('email', $data['customer']['email'])->id;
    //                     // $transaction->amount = $data['amount'] / 100;
    //                     // $transaction->paid_at = $data['createdAt'];
    //                     // $transaction->email = $data['customer']['email'];
    //                     // $transaction->status = $data['status'];
    //                     // $transaction->save();
    //                     DB::commit();
    //                     return response()->json([
    //                         'message' => 'Subscription Created Successfully',
    //                     ], 200);
    //                 } catch (\Exception $e) {
    //                     return response()->json([
    //                         'message' => $e->getTraceAsString(),
    //                     ], 500);
    //                 }

    //             case 'charge.success':
    //                 try {
    //                     $data = $paymentDetails['data'];

    //                     DB::beginTransaction();

    //                     $transaction = new SubscriptionTransaction();
    //                     $transaction->reference = $data['reference'];
    //                     $transaction->user_id = User::firstWhere('email', $data['customer']['email'])->id;
    //                     $transaction->amount = $data['amount'] / 100;
    //                     $transaction->paid_at = $data['paid_at'];
    //                     $transaction->email = $data['customer']['email'];
    //                     $transaction->status = $data['status'];

    //                     if(SubscriptionTransaction::where('email', $data['customer']['email'])->count() > 0){
    //                         $lastSubscription = Subscription::where('customer_email', $data['customer']['email'])->latest()->where('status', 1)->where('subscription_status', 'active')->first();
    //                         if($lastSubscription){
    //                             $lastSubscription->next_payment_date = Carbon::parse($lastSubscription->next_payment_date)->addMonth();
    //                             $lastSubscription->update();
    //                         }
    //                     }
    //                     $transaction->save();
    //                     DB::commit();
    //                     return response()->json([
    //                         'message' => 'Subscription Transaction Successful',
    //                     ], 200);
    //                 } catch (\Exception $e) {
    //                     return response()->json([
    //                         'message' => $e->getMessage(),
    //                     ], 500);
    //                 }

    //             case 'invoice.payment_failed':
    //                 try {
    //                     $data = $paymentDetails['data'];

    //                     DB::beginTransaction();

    //                         $lastSubscription = Subscription::where('customer_email', $data['customer']['email'])->latest()->where('status', 1)->where('subscription_status', 'active')->first();
    //                         if ($lastSubscription) {
    //                             $lastSubscription->subscription_status = 'cancelled';
    //                             $lastSubscription->status = 0;
    //                             $lastSubscription->update();
    //                         }
    //                     DB::commit();
    //                     return response()->json([
    //                         'message' => 'Subscription Transaction Failed',
    //                     ], 200);
    //                 } catch (\Exception $e) {
    //                     return response()->json([
    //                         'message' => $e->getMessage(),
    //                     ], 500);
    //                 }
    //                 break;
    //             default:
    //         }


    //     // }
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

                    if ($lastSubscription) {
                        $lastSubscription->next_payment_date = Carbon::parse($lastSubscription->next_payment_date)->addMonth();
                        $lastSubscription->update();
                    } else {
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

    public function testwebhook()
    {
        $client = new Client();
        $response = $client->post(
            'https://fansweek.com/api/paystack/webhook',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . config('paystack.secretKey'),
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
                            "plan_code" => "PLN_9ib2zgl76abcx3b",
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

    public function testing()
    {
        dd(Subscription::all(), SubscriptionTransaction::all());
    }




    //New Login for Mobile App


    public function login(Request $request)
    {
        if( settingHelper('captcha_visibility') == 1):
        $request->validate([
            'email'         => ['required', 'string', 'email', 'max:255'],
            'password'      => ['required', 'string'],
            'g-recaptcha-response'      => ['required', 'string'],
        ]);
        else:

            $request->validate([
                'email'         => ['required', 'string', 'email', 'max:255'],
                'password'      => ['required', 'string'],
            ]);

        endif;

        $user = User::where('email', $request->email)->first();

        if (blank($user)) {
            return redirect()->back()->with(['error' => __('your_email_is_invalid')]);
        } elseif($user->is_user_banned == 0) {
            return redirect()->back()->with(['error' => __('your_account_is_banned')]);
        }

        try {

            if (!Hash::check($request->get('password'), $user->password)) {
                return redirect()->back()->with(['error' => 'Invalid Credentials !']);
            }

            Sentinel::authenticate($request->all());

            return redirect()->route('home');

        } catch (NotActivatedException $e) {

            return redirect()->back()->with(['error' => __('your_account_is_not_activated')]);
        }
    }

    public function showRegistrationForm()
    {
        return view('site.auth.register');
    }

    public function register(Request $request)
    {
        if( settingHelper('captcha_visibility') == 1):

            $request->validate([
                'first_name'    => ['required', 'string', 'max:255'],
                'last_name'     => ['required', 'string', 'max:255'],
                'email'         => ['required', 'string', 'email', 'max:255'],
                'password'      => ['required', 'string', 'min:6'],
                'phone'         => ['min:11','max:14'],
                'dob'           => 'required',
                'gender'        => 'required',
                'g-recaptcha-response'      => ['required', 'string'],
            ]);

        else:
            $request->validate([
                'first_name'    => ['required', 'string', 'max:255'],
                'last_name'     => ['required', 'string', 'max:255'],
                'email'         => ['required', 'string', 'email', 'max:255'],
                'password'      => ['required', 'string', 'min:6'],
                'phone'         => ['min:11','max:14'],
                'dob'           => 'required',
                'gender'        => 'required',
            ]);

        endif;

        $request['is_password_set'] = 1;

        try {

            $user = User::where('email', $request->email)->first();

            if(!blank($user)){
                if($user->withActivation == ""){

                    $user->password             = bcrypt($request->password);
                    $user->first_name           = $request->first_name;
                    $user->last_name            = $request->last_name;
                    $user->dob                  = $request->dob;
                    $user->phone                = $request->phone;
                    $user->gender               = $request->gender;
                    $user->is_password_set      = 1;
                    $user->save();

                    $activation         = Activation::create($user);
                    return $this->activation($request->email, $activation->code);

                    // sendMail($user, $activation->code, 'activate_account', $request->password);

                    // return redirect()->route('site.login.form')->with('success', __('check_user_mail_for_active_this_account'));

                }else{
                    return redirect()->back()->with('error', __('the_email_has_already_been_taken'));
                }
            }

            $user               = Sentinel::register($request->all());
            $role               = Sentinel::findRoleBySlug('user');

            $role->users()->attach($user);

            $activation         = Activation::create($user);

            return $this->activation($request->email, $activation->code);


            // sendMail($user, $activation->code, 'activate_account', $request->password);

            // return redirect()->route('site.login.form')->with('success', __('check_user_mail_for_active_this_account'));



        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('test_mail_error_message'));
        }


    }

    public function logout()
    {
        Sentinel::logout();

        return redirect()->route('home');
    }

    public function forgotPassword()
    {
        return view('site.auth.forgot_password');
    }

    public function postForgotPassword(Request $request)
    {
        try {

            $user                   = User::whereEmail($request->email)->first();

            if(blank($user)){
                return redirect()->back()->with('error', __('your_email_is_invalid'));
            }

            if(Reminder::exists($user)):
                $remainder          = Reminder::where('user_id',$user->id)->first();
            else:
                $remainder          = Reminder::create($user);
            endif;
            //send a mail to user
            sendMail($user, $remainder->code, 'forgot_password', '');

            return redirect()->back()->with([
                'success'           => __('reset_code_is_send_to_mail'),
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('test_mail_error_message'));
        }
    }

    public function resetPassword($email, $resetCode)
    {
        $user                   = User::byEmail($email);

        if ($reminder           = Reminder::exists($user, $resetCode)) :
            return view('site.auth.reset_password',['email'=>$email,'resetCode'=>$resetCode]);
        else :
            return redirect('login');
        endif;
    }

    public function PostResetPassword(Request $request, $email, $resetCode)
    {
        Validator::make($request->all(), [
            'password'              => 'confirmed|required|min:5|max:10',
            'password_confirmation' => 'required|min:5|max:10'
        ])->validate();

        try {

            $user = User::byEmail($email);

            if ($reminder = Reminder::exists($user, $resetCode)) :
                Reminder::complete($user, $resetCode, $request->password);
                sendMail($user, '', 'reset_password', $request->password);

                return redirect()->route('site.login.form')->with('success', __('you_can_login_with_new_password'));
            else :
                return redirect()->route('site.login.form');
            endif;

        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('test_mail_error_message'));
        }
    }

    public function activation($email, $activationCode)
    {
        $user       = User::whereEmail($email)->first();

        if (Activation::complete($user, $activationCode)) :

            // sendMail($user, '', 'registration', '');
            return redirect()->route('site.login.form')->with('success', "Registration Successful");

        endif;

        return redirect('/');
    }
}
