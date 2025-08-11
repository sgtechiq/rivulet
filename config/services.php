<?php

/**
 * Third-Party Services Configuration
 *
 * Stores credentials and settings for external integrations.
 * All sensitive values are loaded from environment variables.
 */
return [
    // Firebase Cloud Messaging (FCM) for push notifications
    'firebase' => [
        'api_key' => env('NOTIFICATION_FIREBASE_API_KEY'), // Server key for Firebase API
    ],

    // Pusher for real-time notifications
    'pusher'   => [
        'app_id'     => env('NOTIFICATION_PUSHER_APP_ID'),     // Pusher application ID
        'app_key'    => env('NOTIFICATION_PUSHER_APP_KEY'),    // Public key
        'app_secret' => env('NOTIFICATION_PUSHER_APP_SECRET'), // Private secret key
        'cluster'    => env('NOTIFICATION_PUSHER_CLUSTER'),    // Server cluster (e.g., 'mt1')
    ],

    // Slack webhook integration
    'slack'    => [
        'webhook' => env('NOTIFICATION_SLACK_WEBHOOK'), // Incoming webhook URL
    ],

    // WhatsApp Business API
    'whatsapp' => [
        'api_key' => env('NOTIFICATION_WHATSAPP_API_KEY'), // WhatsApp Business API key
    ],

    // SMS Gateway configurations
    'sms'      => [
        'provider'    => env('NOTIFICATION_SMS_PROVIDER', 'twilio'), // Default provider
        'account_sid' => env('NOTIFICATION_SMS_ACCOUNT_SID'),        // Account SID
        'auth_token'  => env('NOTIFICATION_SMS_AUTH_TOKEN'),         // Auth token
        'from'        => env('NOTIFICATION_SMS_FROM'),               // Sender number/ID
    ],
];
