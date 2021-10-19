<?php
namespace App\Action\Subscription;

use App\Subscription;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Modules\User\Entities\User;

class CreateSubscription{
    public static function create($email){
        $client = new Client(); //GuzzleHttp\Client
        $response = $client->post(
            'https://api.paystack.co/subscription',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' =>'Bearer '.config('paystack.secretKey'),
                ],
                'json' => [
                    "customer" => $email,
                    "plan" => "PLN_8wa5t89ms15a8en"
                ]
            ]
        );
        $result = json_decode($response->getBody(), true);

        if($result['status'] === true){
            $data = $result['data'];
            DB::beginTransaction();
                $subscription = new Subscription();
                $subscription->subscription_code = $data['subscription_code'];
                $subscription->customer_email = $email;
                $subscription->user_id = User::firstWhere('email', $email)->id;
                $subscription->customer_code = $data['customer'];
                $subscription->email_token = $data['email_token'];
                $subscription->amount = $data['amount']/100;
                $subscription->subscription_status = $data['status'];
                $subscription->status = 1;
                $subscription->next_payment_date = $data['next_payment_date'];
                $subscription->plan_code = "PLN_8wa5t89ms15a8en";
                $subscription->authorization = $data['authorization'];
                $subscription->save();
            DB::commit();
        }
    }
}
