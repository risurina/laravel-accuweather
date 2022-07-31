<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Integrations\AccuWeather\Api\AccuWeatherRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    public function __invoke(Request $request)
    {
        // TODO: Create api validator helper
        $rules = [
            'regionCode' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $regionCode = $request->input('regionCode');
        // cache the request for 1 hour.
        $seconds = 60 * 60;
        $countries = cache()->remember('countries-' . $regionCode, $seconds, function () use ($regionCode) {
            $accuWeatherRequest = new AccuWeatherRequest;
            return $accuWeatherRequest->getCountries($regionCode);
        });
        return $countries;
    }
}
