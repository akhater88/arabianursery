<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google API Keys
    |--------------------------------------------------------------------------
    */
    'api_keys' => [
        'maps_api' => env('GOOGLE_MAPS_API_KEY', 'default'),
        'geocoding_api' => env('GOOGLE_GEOCODING_API', 'default'),
    ],
];
