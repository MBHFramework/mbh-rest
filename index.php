<?php

require 'vendor/autoload.php';
require 'Mbh/config/config.php';
require 'Mbh/App.php';

\Mbh\App::registerAutoload();

$app = new \Mbh\App();

/**
  * Specific routes should be defined here
  */

$app->run();
