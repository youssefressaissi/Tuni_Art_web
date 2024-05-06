<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherController extends AbstractController
{
    private $httpClient;
    private $apiKey = 'GywZa53kD1MZRvb7HBs9SsmBUWK62MUD';

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function fetchWeather(string $city): array
    {
        try {
            // Fetch location key for the specified city
            $locationKey = $this->fetchLocationKey($city);

            if ($locationKey) {
                // Fetch weather information using the location key and date
                $weatherData = $this->fetchWeatherData($locationKey);
                // Return weather data along with city name
                return [
                    'city' => $city,
                    'weatherData' => $weatherData,
                ];
            } else {
                return [
                    'error' => 'City not found.',
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => 'Failed to fetch weather information. Please try again later.',
            ];
        }
    }

    private function fetchLocationKey(string $city): ?string
    {
        $response = $this->httpClient->request('GET', 'http://dataservice.accuweather.com/locations/v1/cities/search', [
            'query' => [
                'apikey' => $this->apiKey,
                'q' => $city,
            ],
        ]);

        $data = $response->toArray();
        if (!empty($data) && isset($data[0]['Key'])) {
            return $data[0]['Key'];
        }

        return null;
    }

    private function fetchWeatherData(string $locationKey): array
    {
        $response = $this->httpClient->request('GET', 'http://dataservice.accuweather.com/currentconditions/v1/' . $locationKey, [
            'query' => [
                'apikey' => $this->apiKey,
            ],
        ]);

        return $response->toArray();
    }
}
