<?php

namespace App\Action\Prediction;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\BadResponseException;

class LastTenAway
{
    public function run($id)
    {
        $client = new Client(); //GuzzleHttp\Client
        $response = $client->get(('https://football-prediction-api.p.rapidapi.com/api/v2/away-last-10/' . $id),
            [
                'http_errors' => false,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-rapidapi-key' => 'edmL7VpyK8msh29ZfiWpoBMyNAwAp1eM0RkjsnpfADSAsb6Tr5',
                    'x-rapidapi-host' => 'football-prediction-api.p.rapidapi.com'
                ]
            ]
        );
        if ($response) {

            $response = json_decode($response->getBody(), true);

            $data = $response['data'];

            return $data;
        } else {
            return [];
        }
    }
}
