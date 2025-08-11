<?php

/**
 * Filesystem Configuration
 *
 * Defines storage disks and their settings. Environment variables can override defaults.
 */

return [
    // ==================== DEFAULT DISK ====================
    'default' => env('FILESYSTEM_DISK', 'local'),

    // ==================== STORAGE DISKS ====================
    'disks'   => [
        'local' => [
            'driver'     => 'local',                                         // Local filesystem driver
            'root'       => dirname(__DIR__) . '/storage/uploads',           // Storage path
            'url'        => env('APP_URL', 'http://localhost') . '/storage', // Base URL
            'visibility' => 'public',                                        // File visibility (public|private)
        ],

        // Example S3 Configuration (uncomment to use)
        // 's3' => [
        //     'driver' => 's3',
        //     'key'    => env('AWS_ACCESS_KEY_ID'),
        //     'secret' => env('AWS_SECRET_ACCESS_KEY'),
        //     'region' => env('AWS_DEFAULT_REGION'),
        //     'bucket' => env('AWS_BUCKET'),
        //     'url'    => env('AWS_URL'),
        //     'endpoint' => env('AWS_ENDPOINT'),
        //     'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        // ],
    ],

];
