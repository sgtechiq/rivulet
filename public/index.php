<?php

// Bootstrap the application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Create the request from globals
$request = \Rivulet\Http\Request::capture();

// Process the request through the kernel
$kernel = new \Rivulet\Http\Kernel($app);
$response = $kernel->handle($request);

// Send the response
$response->send();