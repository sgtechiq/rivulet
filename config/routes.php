<?php

/**
 * Route Configuration
 *
 * Maps URI prefixes to their corresponding route definition files.
 * Routes are matched in the order they are defined.
 */
return [
    'handlers' => [
        // Default route handler (for root path '/')
        ''    => 'api.php',

        // API route handler (for paths beginning with '/api')
        'api' => 'api.php',
    ],
];
