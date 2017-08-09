<?php

require 'vendor/autoload.php';
require 'Mbh/Settings/config.php';
require 'Mbh/App.php';

use \Mbh\App;

App::registerAutoload();

$app = new App();

/**
  * Specific routes should be defined here
  */

$app->run();
