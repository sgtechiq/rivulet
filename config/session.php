<?php

return [
    'driver' => 'file', // file (native), db, redis
    'lifetime' => 120, // minutes
    'path' => '/tmp', // for file
    'secure' => env('APP_ENV') === 'production', // HTTPS only
    'http_only' => true,
];