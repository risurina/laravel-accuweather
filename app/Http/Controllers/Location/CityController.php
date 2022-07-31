<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Integrations\AccuWeather\Api\AccuWeatherRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    public function __invoke(Request $request)
    {
        // TODO: Create api validator helper
        $rules = [
            'countryCode' => 'required',
            'adminCode' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $countryCode = $request->input('countryCode');
        $adminCode = $request->input('adminCode');
        // cache the request for 1 hour.
        $seconds = 60 * 60;
        $cacheKey = 'cities-' . $countryCode . '-' . $adminCode;
        $cities = cache()->remember($cacheKey, $seconds, function () use ($countryCode, $adminCode) {
            $accuWeatherRequest = new AccuWeatherRequest;
            return $accuWeatherRequest->getCities($countryCode, $adminCode);
        });
        return $cities;
    }
}
