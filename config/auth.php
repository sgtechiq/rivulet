<?php

/**
 * Authentication Configuration
 *
 * Defines token-based authentication settings for the application.
 * All values can be overridden by environment variables.
 */

return [
                                                      // ==================== TOKEN SETTINGS ====================
    'token_expiry' => env('AUTH_TOKEN_EXPIRY', 3600), // Token lifetime in seconds (default: 1 hour)

    // ==================== AUTHENTICATION GUARDS ====================
    'guards'       => [
        'api' => [
            'driver' => 'token', // Token-based authentication driver
            'hash'   => false,   // Whether tokens are stored hashed (recommended: true for production)
        ],
    ],

                                            // ==================== USER CONFIGURATION ====================
    'user_model'   => 'App\\Models\\Users', // Fully-qualified user model class
                                            // Set to null to disable user association

                            // ==================== TOKEN STORAGE ====================
    'store_token'  => true, // Whether to persist tokens in database
                            // Requires user_model to be set for token revocation
];
