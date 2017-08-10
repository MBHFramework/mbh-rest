<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Mbh/Settings/config.php';
require __DIR__ . '/Mbh/App.php';

use \Mbh\App;

App::registerAutoload();

$app = new App();

$app->any('/', function() {
    return "Router is working!";
});

$app->run();
