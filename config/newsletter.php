<?php

return [
    'driver' => \Spatie\Newsletter\Drivers\MailChimpDriver::class,
    'apiKey' => env('MAILCHIMP_API_KEY'), // Make sure to pull the API key from .env
    'defaultListName' => 'kitabbhandaar', // Ensure this matches exactly (case-sensitive)
    'lists' => [
        'kitabbhandaar' => [
            'id' => env('MAILCHIMP_LIST_ID'), // This will resolve to 8b48ed9c6b
        ],
    ],
    'ssl' => true,
];