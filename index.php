<?php

define('INDEX_DIR', true);

require 'vendor/autoload.php';
require 'Mbh/config/config.php';
require 'Mbh/App.php';


\Mbh\App::registerAutoload();

$app = new \Mbh\App();



$app->run();
