<?php

namespace App\Integrations\AccuWeather\Api;

use Illuminate\Support\Facades\Http;
use Exception;

class AccuWeatherClient
{
    /**
     * @var Http $client
     */
    protected $client;

    /**
     * @var string $apiKey
     */
    protected $apiKey;

    /**
     * AccuWeatherClient constructor
     */
    public function __construct()
    {
        $this->apiKey = config('accuweather.api_key');
        $baseUrl = 'http://dataservice.accuweather.com';

        if (!$this->apiKey) {
            throw new Exception('Accu weather api key is not set.', 1);
        }

        $this->client = Http::baseUrl($baseUrl);
    }

    public function get(string $url, $query = [])
    {
        // attach api key in every request
        $query['apikey'] = $this->apiKey;
        return $this->client->get($url, $query);
    }
}
