<?php

return [
    // Third-party services like Firebase, Pusher, etc.
    'firebase' => [
        'api_key' => env('NOTIFICATION_FIREBASE_API_KEY'),
    ],
    'pusher' => [
        'app_id' => env('NOTIFICATION_PUSHER_APP_ID'),
        'app_key' => env('NOTIFICATION_PUSHER_APP_KEY'),
        'app_secret' => env('NOTIFICATION_PUSHER_APP_SECRET'),
        'cluster' => env('NOTIFICATION_PUSHER_CLUSTER'),
    ],
    'slack' => [
        'webhook' => env('NOTIFICATION_SLACK_WEBHOOK'),
    ],
    'whatsapp' => [
        'api_key' => env('NOTIFICATION_WHATSAPP_API_KEY'),
    ],
    'sms' => [
        'provider' => env('NOTIFICATION_SMS_PROVIDER', 'twilio'),
        'account_sid' => env('NOTIFICATION_SMS_ACCOUNT_SID'),
        'auth_token' => env('NOTIFICATION_SMS_AUTH_TOKEN'),
        'from' => env('NOTIFICATION_SMS_FROM'),
    ],
];