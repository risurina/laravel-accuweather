<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Integrations\AccuWeather\Api\AccuWeatherRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdministrativeAreaController extends Controller
{
    public function __invoke(Request $request)
    {
        // TODO: Create api validator helper
        $rules = [
            'countryCode' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $countryCode = $request->input('countryCode');
        // cache the request for 1 hour.
        $seconds = 60 * 60;
        $adminArea = cache()->remember('admin-area-' . $countryCode, $seconds, function () use ($countryCode) {
            $accuWeatherRequest = new AccuWeatherRequest;
            return $accuWeatherRequest->getAdminArea($countryCode);
        });
        return $adminArea;
    }
}
