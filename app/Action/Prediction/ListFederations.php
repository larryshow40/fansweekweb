<?php

namespace App\Action\Prediction;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Collection;
use GuzzleHttp\Exception\ClientException;


class ListFederations
{
    public function run()
    {
        try {
            $client = new Client(); //GuzzleHttp\Client
            $response = $client->get(
                'https://football-prediction-api.p.rapidapi.com/api/v2/list-federations/',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'x-rapidapi-key' => 'edmL7VpyK8msh29ZfiWpoBMyNAwAp1eM0RkjsnpfADSAsb6Tr5',
                        'x-rapidapi-host' => 'football-prediction-api.p.rapidapi.com'
                    ]
                ]
            );

            $response = json_decode($response->getBody(), true);

            $data = collect($response['data']);

            return $data ?? [];
        } catch (ClientException $e) {
            return [];
        }
    }
}
