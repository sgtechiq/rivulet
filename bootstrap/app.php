<?php

/**
 * Application Bootstrap File
 *
 * Initializes the application environment, dependencies, and configurations.
 * - Loads Composer dependencies.
 * - Sets up environment variables.
 * - Configures error reporting based on the environment.
 * - Bootstraps the Rivulet application instance.
 */

// Load Composer's autoloader for dependency management
require_once __DIR__ . '/../vendor/autoload.php';

// Initialize and load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Create and bootstrap the Rivulet application instance
$app = \Rivulet\Rivulet::getInstance();
$app->bootstrap();

// Configure error reporting based on the application environment
if (env('APP_DEBUG', true)) {
    // Development environment: Show all errors
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    // Production environment: Suppress errors
    error_reporting(0);
    ini_set('display_errors', 0);
}

return $app;
