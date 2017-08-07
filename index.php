<?php

define('INDEX_DIR', true);

require 'vendor/autoload.php';
require 'config/config.php';
require 'MBHF/App.php';


\MBHF\App::registerAutoload();

$app = new \MBHF\App();



$app->run();
