<?php
namespace App\Action\Prediction;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Collection;

class AllPredictions{
    public function run($request = null){
        if($request->today ?? false){
           $date = \Carbon\Carbon::today()->toDateString();
        }elseif($request->tomorrow ?? false){
           $date = \Carbon\Carbon::tomorrow()->toDateString();
        }elseif($request->yesterday ?? false){
            $date = \Carbon\Carbon::yesterday()->toDateString();
        }else{
            // $date = \Carbon\Carbon::today()->toDateString();
           
        }

        $client = new Client(); //GuzzleHttp\Client
        $response = $client->get('https://football-prediction-api.p.rapidapi.com/api/v2/predictions/',[
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-rapidapi-key' => 'edmL7VpyK8msh29ZfiWpoBMyNAwAp1eM0RkjsnpfADSAsb6Tr5',
                    'x-rapidapi-host' => 'football-prediction-api.p.rapidapi.com'
                ],
                'query' => [
                    'iso_date' => $date ?? null,
                ]
            ]
        );


        $response = json_decode($response->getBody(),true);



        $data = collect($response['data']);
        // dd($data);

        return $data;
    }

    public function filter($request){
        $client = new Client(); //GuzzleHttp\Client
        $response = $client->get('https://football-prediction-api.p.rapidapi.com/api/v2/predictions/',[
                'http_errors' => false,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-rapidapi-key' => 'edmL7VpyK8msh29ZfiWpoBMyNAwAp1eM0RkjsnpfADSAsb6Tr5',
                    'x-rapidapi-host' => 'football-prediction-api.p.rapidapi.com'
                ],
                
                'query' => [
                    'market' => $request->market ?? null,
                    'federation' => $request->federation ?? null,
                    'iso_date' => $request->date ?? null,
                ]
                
            ]
        );

        $response = json_decode($response->getBody(),true);

        $data = collect($response['data']);
        return $data ?? [];
    }
}
