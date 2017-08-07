<?php

# Version alert
try {
  if (version_compare(phpversion(), '7.0.0', '<'))
    throw new Exception(true);
} catch (Exception $e) {
  die('Current <b>PHP</b> version is <b>' . phpversion() . '</b> and a version greater than or equal to <b>7.0.0</b> is required');
}

require 'MBHF/config.php';
require 'vendor/autoload.php';
require 'MBHF/helpers/functions/autoload_functions.php';

require 'MBHF/kernel/Debug.php';
