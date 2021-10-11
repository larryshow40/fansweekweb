<?php
namespace App\Action\Subscription;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use GuzzleHttp\Client;

class CreateSubscription{
    public static function create(){
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
                    "amount" => 1000 * 100,
                    "plan"=> "PLN_xc3curswwx9blik" 
                ]

            ]
        );
        return json_decode($response->getBody(), true);
    }
}