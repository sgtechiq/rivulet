<?php

/**
 * Session Configuration
 *
 * Controls how session data is stored and managed.
 * Uses environment variables for environment-specific settings.
 */
return [
    // Storage driver (file, db, redis)
    'driver'    => 'file',

    // Session lifetime in minutes (2 hours)
    'lifetime'  => 120,

    // Storage path for file driver
    'path'      => '/tmp',

                                                    // Security settings
    'secure'    => env('APP_ENV') === 'production', // HTTPS-only in production
    'http_only' => true,                            // Prevent JavaScript access to cookies
];
