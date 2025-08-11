<?php

/**
 * Mail Configuration
 *
 * Configures mail services with support for multiple mailers.
 * Uses environment variables for all sensitive credentials.
 */

// Initialize mailers array and set default mailer from environment
$mailers       = [];
$defaultMailer = env('MAIL_MAILER', 'smtp');

// Configure default mailer
$mailers[$defaultMailer] = [
    'transport'  => $defaultMailer,                // Mail transport protocol (smtp, sendmail, etc.)
    'host'       => env('MAIL_HOST'),              // Mail server hostname
    'port'       => env('MAIL_PORT', 587),         // Mail server port (default: 587)
    'username'   => env('MAIL_USERNAME'),          // Authentication username
    'password'   => env('MAIL_PASSWORD'),          // Authentication password
    'encryption' => env('MAIL_ENCRYPTION', 'tls'), // Connection encryption (tls/ssl)
    'from'       => [                              // Default sender information
        'address' => env('MAIL_FROM_ADDRESS'),         // Default from address
        'name'    => env('MAIL_FROM_NAME'),            // Default from name
    ],
];

// Configure additional mailers when their host is defined in environment
for ($i = 2; env("MAIL_MAILER_{$i}_HOST"); $i++) {
    $mailerName           = "mailer_$i";
    $mailers[$mailerName] = [
        'transport'  => env("MAIL_MAILER_{$i}", 'smtp'),           // Mailer-specific transport
        'host'       => env("MAIL_MAILER_{$i}_HOST"),              // Mailer-specific host
        'port'       => env("MAIL_MAILER_{$i}_PORT", 587),         // Mailer-specific port
        'username'   => env("MAIL_MAILER_{$i}_USERNAME"),          // Mailer-specific username
        'password'   => env("MAIL_MAILER_{$i}_PASSWORD"),          // Mailer-specific password
        'encryption' => env("MAIL_MAILER_{$i}_ENCRYPTION", 'tls'), // Mailer-specific encryption
        'from'       => [                                          // Mailer-specific sender info
            'address' => env("MAIL_MAILER_{$i}_FROM_ADDRESS"),
            'name'    => env("MAIL_MAILER_{$i}_FROM_NAME"),
        ],
    ];
}

return [
    'default' => $defaultMailer, // Name of default mailer to use
    'mailers' => $mailers,       // All configured mailer instances
];
