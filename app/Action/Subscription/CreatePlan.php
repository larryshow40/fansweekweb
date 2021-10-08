<?php
namespace App\Action\Subscription;

use GuzzleHttp\Client;

class CreatePlan{
    public static function create(){
        $client = new Client(); //GuzzleHttp\Client
        $response = $client->post(
            'https://api.paystack.co/plan',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' =>'Bearer '.config('paystack.secretKey'),
                ],

                'json' => [
                    "name" => "Monthly Subscription", 
                    "interval" => "monthly", 
                    "amount" => 1000 * 100
                ]

            ]
        );

        return json_decode($response->getBody(), true);

         //PLN_xc3curswwx9blik

    }
}