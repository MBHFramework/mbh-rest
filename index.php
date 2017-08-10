<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Mbh/Settings/config.php';
require __DIR__ . '/Mbh/App.php';

use \Mbh\App;

App::registerAutoload();

$app = new App();

/**
  * Specific routes should be defined here
  */

$app->any('/', function() {
    return "Router is working!";
});

$app->map(['GET', 'POST'], '/:controller', function($controller) {
    return $controller;
});

$app->get('/users/:username', function($username) {
    return "Hello " . ucwords($username) . "!";
});

$app->run();
