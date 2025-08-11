<?php

return [
    'default' => env('LOG_CHANNEL', 'file'),
    'channels' => [
        'file' => [
            'driver' => 'file',
            'path' => dirname(__DIR__) . '/storage/logs/app.log',
            'level' => env('LOG_LEVEL', 'debug'),
        ],
    ],
];