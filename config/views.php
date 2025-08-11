<?php

/**
 * View Configuration
 *
 * Controls template system settings including:
 * - View file locations
 * - File extensions
 * - Compiled template cache
 */
return [
    // Paths to search for view templates (in order of priority)
    'paths'     => [
        dirname(__DIR__) . '/resources/views', // Primary views directory
    ],

                            // Default file extension for view templates
    'extension' => '.html', // Recognizes files like 'welcome.html'

                                                              // Directory for compiled/cached templates
    'compiled'  => dirname(__DIR__) . '/storage/cache/views', // Improves performance
];
