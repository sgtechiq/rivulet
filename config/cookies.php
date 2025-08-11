<?php

/**
 * Cookie Configuration
 *
 * Defines global cookie settings for the application.
 * Environment variables take precedence where specified.
 */

return [
                                                    // ==================== COOKIE IDENTIFICATION ====================
    'prefix'   => env('APP_NAME', 'rivulet') . '_', // Cookie name prefix (e.g., "rivulet_session")

                     // ==================== EXPIRATION SETTINGS ====================
    'expiry'   => 0, // Lifetime in seconds (0 = session cookie, expires when browser closes)

                        // ==================== SCOPE CONTROL ====================
    'path'     => '/',  // Accessible paths (root by default)
    'domain'   => null, // Accessible domains (null = current domain only)

                                                   // ==================== SECURITY SETTINGS ====================
    'secure'   => env('APP_ENV') === 'production', // HTTPS-only in production
    'httponly' => true,                            // Prevent JavaScript access (recommended)
    'samesite' => 'lax',                           // CSRF protection ('strict', 'lax', or 'none')

    // Note: For 'samesite' => 'none', 'secure' must be true
];
