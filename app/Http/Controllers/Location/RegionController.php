<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Integrations\AccuWeather\Api\AccuWeatherRequest;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function __invoke()
    {
        // cache the request for 1 hour.
        $seconds = 60 * 60;
        return cache()->remember('regions', $seconds, function () {
            $accuWeatherRequest = new AccuWeatherRequest;
            return $accuWeatherRequest->getRegions();
        });
    }
}
