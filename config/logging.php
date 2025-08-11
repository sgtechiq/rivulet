<?php

/**
 * Logging Configuration File
 *
 * Sets up basic file logging for the application.
 * Uses environment variables for configuration where specified.
 */
return [
    // The default logging channel to use
    // Can be overridden with LOG_CHANNEL environment variable
    'default'  => env('LOG_CHANNEL', 'file'),

    // Available logging channels configuration
    'channels' => [
        // File-based logging channel
        'file' => [
            'driver' => 'file',                                     // Log driver type (file-based)
            'path'   => dirname(__DIR__) . '/storage/logs/app.log', // Absolute path to log file
            'level'  => env('LOG_LEVEL', 'debug'),                  // Minimum log level to record (debug, info, warning, error)
        ],
    ],
];
