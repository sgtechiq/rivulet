<?php

use App\Controllers\UsersController;

// Welcome route
route('GET', '/', function () {
    return ['message' => 'Welcome to Rivulet API'];
});

// User prefix
prefix('user', function () {
    route('GET', '/', UsersController::class, 'list');
    route('GET', '/{id}', UsersController::class, 'show');
    route('POST', '/', UsersController::class, 'store');
    route('PUT', '/{id}', UsersController::class, 'modify');
    route('DELETE', '/{id}', UsersController::class, 'delete');
    route('DELETE', '/{id}/force', UsersController::class, 'destroy');

    // Protected with middleware (e.g., login, logout, profile - placeholders)
    middleware('auth', function () {
        route('POST', '/login', function () { /* Login logic */ });
        route('POST', '/logout', function () { /* Logout logic */ });
        route('GET', '/profile', function () { /* Profile logic */ });
    });
});