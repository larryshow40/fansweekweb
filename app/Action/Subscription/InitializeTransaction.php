<?php
namespace App\Action\Subscription;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use GuzzleHttp\Client;

class InitializeTransaction{
    public static function initialize(){
        $client = new Client(); //GuzzleHttp\Client
        $response = $client->post(
            'https://api.paystack.co/transaction/initialize',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' =>'Bearer '.config('paystack.secretKey'),
                ],

                'json' => [
                    "email" => Sentinel::getUser()->email,
                    "amount"=> 1000*100
                ]

            ]
        );
        return json_decode($response->getBody(), true);
    }
}
