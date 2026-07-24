<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    // ... whatever else is already here ...

    'finance' => [
        'token' => env('FINANCE_API_TOKEN'),
        'url'   => env('FINANCE_API_URL'),
    ],

];