<?php

return [
    'name' => env('APP_NAME', 'Rivulet'),
    'env' => env('APP_ENV', 'local'),
    'debug' => env('APP_DEBUG', true),
    'url' => env('APP_URL', 'http://localhost'),
    'key' => env('APP_KEY', ''),

   'providers' => [
    \Rivulet\Providers\RouteServiceProvider::class, 
    \Rivulet\Providers\DatabaseServiceProvider::class,
    \Rivulet\Providers\FilesystemServiceProvider::class,
    \Rivulet\Providers\ViewsServiceProvider::class,
    \Rivulet\Providers\MailServiceProvider::class,
    \Rivulet\Providers\NotificationServiceProvider::class,
    \Rivulet\Providers\QueueServiceProvider::class,
    \Rivulet\Providers\HttpClientServiceProvider::class,
    \Rivulet\Providers\EventServiceProvider::class,
    \Rivulet\Providers\SessionServiceProvider::class,
    \Rivulet\Providers\CookiesServiceProvider::class,
    \Rivulet\Providers\AppServiceProvider::class,
],

    'helpers' => [
        // Global helpers auto-loaded (files in core/Support or app/Helpers)
    ],

    'services' => [
        // Custom services in app/Services to auto-load
    ],
];