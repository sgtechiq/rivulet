<?php

/**
 * Database Configuration
 *
 * Predefined templates for different drivers. Copy and customize connections as needed.
 * Each connection can have its own driver.
 * Secure defaults for charset/collation.
 */

// Default connection (customize)
$connections = [
    'default' => [
        'driver'    => env('DB_DRIVER', 'mysql'),
        'host'      => env('DB_HOST', '127.0.0.1'),
        'port'      => env('DB_PORT', '3306'),
        'database'  => env('DB_DATABASE', 'rivulet'),
        'username'  => env('DB_USERNAME', 'root'),
        'password'  => env('DB_PASSWORD', ''),
        'charset'   => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix'    => '',
        'strict'    => true,
        'engine'    => null,
    ],
    // Example secondary (copy and rename/change driver)
    'secondary' => [
        'driver'    => 'pgsql',
        'host'      => env('SECONDARY_DB_HOST', '127.0.0.1'),
        'port'      => env('SECONDARY_DB_PORT', '5432'),
        'database'  => env('SECONDARY_DB_DATABASE', 'otherdb'),
        'username'  => env('SECONDARY_DB_USERNAME', 'user'),
        'password'  => env('SECONDARY_DB_PASSWORD', ''),
        'charset'   => 'utf8',
        'prefix'    => '',
        'strict'    => true,
    ],
    // SQLite example (no host/port)
    'sqlite_conn' => [
        'driver'    => 'sqlite',
        'database'  => env('SQLITE_DB_DATABASE', 'database.sqlite'),
        'prefix'    => '',
    ],
];

return [
    'default'     => env('DB_CONNECTION', 'default'),
    'connections' => $connections,
    'migrations'  => 'migrations',

    // Redis (for cache/queue, not DB)
    'redis'       => [
        'client'  => env('REDIS_CLIENT', 'phpredis'),
        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix'  => env('REDIS_PREFIX', 'rivulet_database_'),
        ],
        // Connection details...
    ],
];