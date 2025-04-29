<?php
return [
    'v2' => [
        'sitekey' => env('GOOGLE_RECAPTCHA_V2_SITEKEY'),
        'secret'  => env('GOOGLE_RECAPTCHA_V2_SECRET'),
    ],

    'v3' => [
        'sitekey' => env('GOOGLE_NOCAPTCHA_V3_SITEKEY'),
        'secret'  => env('GOOGLE_RECAPTCHA_V3_SECRET'),
        'score'   => 0.5,
    ],

    'secret' => env('GOOGLE_RECAPTCHA_V3_SECRET', ''), // default fallback
];
