<?php

$defaultConnection = env('DB_CONNECTION', 'default');

$connections = [];
$connections[$defaultConnection] = [
    'driver' => env('DB_DRIVER', 'mysql'),
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'rivulet'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
];

// Support multiple connections (same driver)
for ($i = 2; env("DB_CONNECTION_{$i}_HOST"); $i++) {
    $connections["connection_$i"] = [
        'driver' => env('DB_DRIVER', 'mysql'), // Enforce same driver
        'host' => env("DB_CONNECTION_{$i}_HOST"),
        'port' => env("DB_CONNECTION_{$i}_PORT", '3306'),
        'database' => env("DB_CONNECTION_{$i}_DATABASE"),
        'username' => env("DB_CONNECTION_{$i}_USERNAME"),
        'password' => env("DB_CONNECTION_{$i}_PASSWORD"),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ];
}

return [
    'default' => $defaultConnection,
    'connections' => $connections,
    'migrations' => 'migrations', // Table name for migrations
];