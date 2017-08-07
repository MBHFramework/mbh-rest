<?php

# Version alert
try {
  if (version_compare(phpversion(), '7.0.0', '<'))
    throw new Exception(true);
} catch (Exception $e) {
  die('Current <b>PHP</b> version is <b>' . phpversion() . '</b> and a version greater than or equal to <b>7.0.0</b> is required');
}

require('src/config.php');
require('vendor/autoload.php');
require('src/helpers/functions/autoload_functions.php');

if (DEBUG) {
  # code...
}

!FIREWALL ?: new Firewall;
