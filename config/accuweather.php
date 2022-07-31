<?php

return [
    /**
     * The token use to authenticate api call.
     */
    'api_key' => env('ACCUWEATHER_API_KEY'),

    'base_url' => env('ACCUWEATHER_BASE_URL', 'http://dataservice.accuweather.com')
];
