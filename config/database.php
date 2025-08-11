<?php

/**
 * Database Configuration
 *
 * Manages database connections with support for:
 * - Multiple connections with the same driver
 * - Environment variable overrides
 * - Secure defaults for character encoding
 */

$defaultConnection = env('DB_CONNECTION', 'default');

// ==================== PRIMARY CONNECTION ====================
$connections = [
    $defaultConnection => [
        'driver'    => env('DB_DRIVER', 'mysql'),     // Database driver (mysql, pgsql, etc.)
        'host'      => env('DB_HOST', '127.0.0.1'),   // Server hostname/IP
        'port'      => env('DB_PORT', '3306'),        // Connection port
        'database'  => env('DB_DATABASE', 'rivulet'), // Database name
        'username'  => env('DB_USERNAME', 'root'),    // Database username
        'password'  => env('DB_PASSWORD', ''),        // Database password
        'charset'   => 'utf8mb4',                     // Recommended charset for full Unicode support
        'collation' => 'utf8mb4_unicode_ci',          // Recommended collation
        'prefix'    => '',                            // Optional table prefix
        'strict'    => true,                          // Enable strict mode
        'engine'    => null,                          // Table storage engine (e.g., InnoDB)
    ],
];

// ==================== ADDITIONAL CONNECTIONS ====================
// Automatically load additional connections when their host is defined
// in environment variables (DB_CONNECTION_2_HOST, DB_CONNECTION_3_HOST, etc.)
for ($i = 2; env("DB_CONNECTION_{$i}_HOST"); $i++) {
    $connections["connection_$i"] = [
        'driver'    => env('DB_DRIVER', 'mysql'), // Shared driver across connections
        'host'      => env("DB_CONNECTION_{$i}_HOST"),
        'port'      => env("DB_CONNECTION_{$i}_PORT", '3306'),
        'database'  => env("DB_CONNECTION_{$i}_DATABASE"),
        'username'  => env("DB_CONNECTION_{$i}_USERNAME"),
        'password'  => env("DB_CONNECTION_{$i}_PASSWORD"),
        'charset'   => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix'    => '',
        'strict'    => true,
        'engine'    => null,
    ];
}

return [
    'default'     => $defaultConnection, // Default connection name
    'connections' => $connections,       // All available connections
    'migrations'  => 'migrations',       // Migrations table name

    // Recommended for production:
    'redis'       => [
        'client'  => env('REDIS_CLIENT', 'phpredis'),
        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix'  => env('REDIS_PREFIX', 'rivulet_database_'),
        ],
        // ... (redis connection details)
    ],
];
