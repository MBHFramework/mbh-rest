<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Mbh/Settings/config.php';
require __DIR__ . '/Mbh/App.php';

use \Mbh\App;
use \Mbh\Router;

App::registerAutoload();

$app = new App();

$router = new Router();

/**
  * Specific routes should be defined here
  */

$router->get('/', function() {
    echo "Router is working!";
});

$router->get('/:controller', function($controller) {
    echo $controller;
});

$app->run();
