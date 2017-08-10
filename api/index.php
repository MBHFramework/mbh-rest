<?php

$root = __DIR__ . '/..';

require "${root}/vendor/autoload.php";
require "${root}/Mbh/Settings/config.php";
require "${root}/Mbh/App.php";

use \Mbh\App;

App::registerAutoload();

$app = new App();

$app->any('/api', function() {
    return "MBHFramework working!";
});

$app->any('/api/teapot', function() {
    return [
      "header" => "HTTP/1.1 418",
      "message" => "I'm a teapot!"
    ];
});

$app->run();
