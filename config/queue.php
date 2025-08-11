<?php

/**
 * Queue Configuration
 *
 * Defines queue connection settings for background job processing.
 * Uses environment variables for configuration where specified.
 */

return [
    // Default queue connection to use
    // Can be overridden with QUEUE_CONNECTION environment variable
    'default'     => env('QUEUE_CONNECTION', 'database'),

    // Available queue connections configuration
    'connections' => [
        // Database queue driver
        'database' => [
            'driver'      => 'database', // Store jobs in database
            'table'       => 'jobs',     // Database table name for jobs
            'queue'       => 'default',  // Default queue name
            'retry_after' => 90,         // Seconds before failed job is retried
        ],

        // Redis queue driver
        'redis'    => [
            'driver'      => 'redis',   // Store jobs in Redis
            'connection'  => 'default', // Redis connection name
            'queue'       => 'default', // Default queue name
            'retry_after' => 90,        // Seconds before failed job is retried
        ],
    ],

    // Failed job logging (uncomment to enable)
    /*
    'failed' => [
        'driver' => 'database',           // Where to store failed jobs
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'failed_jobs',         // Table for failed jobs
    ],
    */
];
