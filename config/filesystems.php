<?php

return [
    'default' => 'local',
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => dirname(__DIR__) . '/storage/uploads',
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],
    ],
];