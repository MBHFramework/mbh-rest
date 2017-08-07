<?php

# Version alert
try {
  if (version_compare(phpversion(), '7.0.0', '<'))
    throw new Exception(true);
} catch (Exception $e) {
  die('Current <b>PHP</b> version is <b>' . phpversion() . '</b> and a version greater than or equal to <b>7.0.0</b> is required');
}

require 'config/config.php';
require 'vendor/autoload.php';

require 'MBHF/helpers/classes/Functions.php';
require 'MBHF/Firewall.php';
require 'MBHF/Debug.php';
