<?php

return [
    'prefix' => env('APP_NAME', 'rivulet') . '_',
    'expiry' => 0, // 0 = session, or seconds
    'path' => '/',
    'domain' => null,
    'secure' => env('APP_ENV') === 'production',
    'httponly' => true,
    'samesite' => 'lax',
];