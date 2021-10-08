<?php
namespace App\Action\Subscription;

use GuzzleHttp\Client;

class VerifyTransaction{
    public static function verify($ref){
        $verify = new Client(); //GuzzleHttp\Client
        $res = $verify->get(
            'https://api.paystack.co/transaction/verify/' . $ref,
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . config('paystack.secretKey'),
                ],

            ]
        );
        return json_decode($res->getBody(), true);   
    }
}