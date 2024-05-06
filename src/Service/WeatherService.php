<?php

// src/Services/WeatherService.php

namespace App\Service;

use GuzzleHttp\Client;

class WeatherService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://www.metaweather.com/api/',
        ]);
    }

    public function getWeather(string $city): array
    {
        $response = $this->client->request('GET', 'location/search/', [
            'query' => [
                'query' => $city,
            ],
        ]);

        $woeid = json_decode($response->getBody()->getContents())[0]->woeid;

        $response = $this->client->request('GET', "location/{$woeid}/");

        return json_decode($response->getBody()->getContents(), true);
    }
}
