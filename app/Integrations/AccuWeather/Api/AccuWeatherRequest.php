<?php

namespace App\Integrations\AccuWeather\Api;

use App\Integrations\AccuWeather\Api\AccuWeatherClient;

class AccuWeatherRequest
{
    /**
     * @var AccuWeatherClient $client
     */
    protected $client;

    public function __construct()
    {
        $this->client = new AccuWeatherClient;
    }

    public function getRegions()
    {
        $url = '/locations/v1/regions';
        return $this->client->get($url)->json();
    }

    public function getCountries($regionCode)
    {
        $url = '/locations/v1/countries/' . $regionCode;
        return $this->client->get($url)->json();
    }

    public function getAdminArea($countryCode)
    {
        $url = '/locations/v1/adminareas/' . $countryCode;
        return $this->client->get($url)->json();
    }

    public function getCities($countryCode, $adminCode)
    {
        $url = '/locations/v1/cities/' . $countryCode . '/' . $adminCode;
        return $this->client->get($url)->json();
    }

    public function getDailyForecast($day, $locationKey)
    {
        $allowedDays = [1, 5, 10, 15];
        if (in_array($day, $allowedDays)) {
            $days = $allowedDays[0];
        }
        $url = '/forecasts/v1/daily/' . $day . 'day/' . $locationKey;
        return $this->client->get($url)->json();
    }

    public function getHourlyForecast($hour, $locationKey)
    {
        $allowedHours = [1, 12, 24, 72, 120];
        if (in_array($hour, $allowedHours)) {
            $hour = $allowedHours[0];
        }
        $url = '/forecasts/v1/daily/' . $hour . 'day/' . $locationKey;
        return $this->client->get($url)->json();
    }
}
