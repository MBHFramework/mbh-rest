<?php

define('INDEX_DIR', true);

require 'MBHF/core.php';
require 'MBHF/kernel/App.php';
use MBHF\App;

App::registerAutoload();

$app = new App();



$app->run();
