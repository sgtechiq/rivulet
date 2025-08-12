<?php

use App\Controllers\ArticlesController;
use App\Controllers\AuthorsController;
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
        route('POST', '/login', function () { /* Login logic */});
        route('POST', '/logout', function () { /* Logout logic */});
        route('GET', '/profile', function () { /* Profile logic */});
    });
});
// Authors routes
prefix('authors', function () {
    route('POST', '/', AuthorsController::class, 'addAuthor');
    route('POST', '/{id}', AuthorsController::class, 'editAuthor');
    route('GET', '/', AuthorsController::class, 'listAuthors');
    route('GET', '/{id}', AuthorsController::class, 'getAuthorInfo');
    route('DELETE', '/{id}', AuthorsController::class, 'deleteAuthor');
    route('POST', '/signup', AuthorsController::class, 'signupAuthor');
    route('POST', '/login', AuthorsController::class, 'loginAuthor');
});

// Articles routes
prefix('articles', function () {
    route('GET', '/', ArticlesController::class, 'listArticles');
    route('POST', '/by-author', ArticlesController::class, 'listByAuthor');
    route('POST', '/by-date', ArticlesController::class, 'listByDate');
    route('POST', '/by-date-author', ArticlesController::class, 'listByDateAndAuthor');
    route('GET', '/count', ArticlesController::class, 'countArticles');
    route('GET', '/count-by-author/{authorId}', ArticlesController::class, 'countByAuthor');
    route('POST', '/count-by-date', ArticlesController::class, 'countByDate');
    route('POST', '/count-by-date-author', ArticlesController::class, 'countByDateAndAuthor');
    route('POST', '/', ArticlesController::class, 'addArticle');
    middleware('auth', function () {
        route('DELETE', '/{id}', ArticlesController::class, 'deleteArticle');
        route('POST', '/{id}', ArticlesController::class, 'editArticle');
    });
});
