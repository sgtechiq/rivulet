<?php

return [
    'token_expiry' => env('AUTH_TOKEN_EXPIRY', 3600), // in seconds
    'guards' => [
        'api' => [
            'driver' => 'token',
            'hash' => false,
        ],
    ],
    'user_model' => 'App\\Models\\Users', // Set to null if no user model
    'store_token' => true, // If true and user_model set, store token in DB for revocation
];