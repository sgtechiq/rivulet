<?php

/**
 * Application Configuration File
 *
 * Defines core settings and service providers for the Rivulet framework.
 * Environment variables (.env) take precedence over default values.
 */

return [
                                                       // ==================== CORE APPLICATION SETTINGS ====================
    'name'      => env('APP_NAME', 'Rivulet'),         // Application name
    'env'       => env('APP_ENV', 'local'),            // Environment (local, production, staging)
    'debug'     => env('APP_DEBUG', true),             // Debug mode (true/false)
    'url'       => env('APP_URL', 'http://localhost'), // Base application URL
    'key'       => env('APP_KEY', ''),                 // Encryption key (generate via `php artisan key:generate`)

    // ==================== SERVICE PROVIDERS ====================
    // Register framework and application service providers here.
    // Providers are loaded in the order they are listed.
    'providers' => [
                                                               // Core Framework Providers
        \Rivulet\Providers\RouteServiceProvider::class,        // Routing system
        \Rivulet\Providers\DatabaseServiceProvider::class,     // Database connections
        \Rivulet\Providers\FilesystemServiceProvider::class,   // File storage
        \Rivulet\Providers\ViewsServiceProvider::class,        // Template engine
        \Rivulet\Providers\MailServiceProvider::class,         // Email services
        \Rivulet\Providers\NotificationServiceProvider::class, // Notifications
        \Rivulet\Providers\QueueServiceProvider::class,        // Job queues
        \Rivulet\Providers\HttpClientServiceProvider::class,   // HTTP client
        \Rivulet\Providers\EventServiceProvider::class,        // Event system
        \Rivulet\Providers\SessionServiceProvider::class,      // Session management
        \Rivulet\Providers\CookiesServiceProvider::class,      // Cookie handling
        \Rivulet\Providers\AppServiceProvider::class,          // Application bootstrapping
    ],

    // ==================== HELPER FUNCTIONS ====================
    // Files containing global helper functions (auto-loaded on startup)
    'helpers'   => [
        // Example:
        // __DIR__ . '/../app/Helpers/global.php',
    ],

    // ==================== CUSTOM SERVICES ====================
    // Application-specific services (auto-resolved via dependency injection)
    'services'  => [
        // Example:
        // 'PaymentProcessor' => \App\Services\PaymentProcessor::class,
    ],
];
