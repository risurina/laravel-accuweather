<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Integrations\AccuWeather\Api\AccuWeatherRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForecastController extends Controller
{
    public function __invoke(Request $request, $locationKey)
    {
        // TODO: Create api validator helper
        $days = [1, 5, 10, 15];
        $hours = [1, 12, 24, 72, 120];
        $rules = [
            'schedule' => 'required|in:daily,hourly',
            'day' => [
                'required_if:schedule,==,daily',
                'in:' . implode(',', $days)
            ],
            'hour' => [
                'required_if:schedule,==,hourly',
                'in:' . implode(',', $hours)
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // cache the request for 1 hour.
        $schedule = $request->input('schedule');
        $scheduleNumber = $schedule == 'daily'
            ? $request->input('day')
            : $request->input('hour');

        $seconds = 60 * 60;
        $cacheKey = implode('-', [
            'forecast',
            $schedule,
            $scheduleNumber,
            $locationKey
        ]);

        return cache()->remember($cacheKey, $seconds, function () use ($schedule, $scheduleNumber, $locationKey) {
            $accuWeatherRequest = new AccuWeatherRequest;
            return $schedule === 'daily'
                ? $accuWeatherRequest->getDailyForecast($scheduleNumber, $locationKey)
                : $accuWeatherRequest->getHourlyForecast($scheduleNumber, $locationKey);
        });
    }
}
