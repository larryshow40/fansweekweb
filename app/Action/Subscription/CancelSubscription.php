<?php
namespace App\Action\Subscription;

use GuzzleHttp\Client;

class CancelSubscription{
    public static function cancel($code, $token){
        $client = new Client(); //GuzzleHttp\Client
        $response = $client->post(
            'https://api.paystack.co/subscription/disable',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' =>'Bearer '.config('paystack.secretKey'),
                ],
                'json' => [
                    "code" => $code,
                    "token" => $token
                ]
            ]
        );

        return json_decode($response->getBody(), true);
    }
}
