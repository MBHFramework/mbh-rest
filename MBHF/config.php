<?php

# Strict types for PHP 7
declare(strict_types=1);

# Security
defined('INDEX_DIR') OR exit('MDHFramework software says .i.');

# Timezone DOC http://php.net/manual/es/timezones.php
date_default_timezone_set('America/Argentina/Buenos_Aires');

/**
  * Settings for DB connection.
  * @param host 'Server for connection to the database -> local/remote hosting'
  * @param user 'Database user'
  * @param pass 'Password of the database user'
  * @param name 'Database name'
  * @param port 'Database port (not required on most engines)'
  * @param protocol 'Connection protocol (not required on most engines)'
  * @param motor 'Default connection engine'
  * MOTORS VALUES:
  *        mysql
  *        sqlite
  *        oracle
  *        postgresql
  *        cubrid
  *        firebird
  *        odbc
  */
define('DATABASE', array(
  'host' => 'localhost',
  'user' => 'root',
  'pass' => '',
  'name' => '',
  'port' => 1521,
  'protocol' => 'TCP',
  'motor' => 'mysql'
));

/**
 * Defines the directory in which the framework is installed
 * @example "/" If to access the framework we place http://url.com in the URL, or http://localhost
 * @example "/mbh-framework/" if to access the framework we place http://url.com/mbh-framework, or http://localhost/mbh-framework/
*/
define('__ROOT__', '/mbh-framework/');

# App constants
define('URL', 'http://localhost/mbh-framework/');
define('APP', 'MDHFramework');
define('APP_COPY','Copyright &copy; ' . date('Y',time()) . APP);

# Session control
define('DB_SESSION', false);
define('SESSION_TIME', 18000); # life time for session cookies -> 5 hs = 18000 seconds.
define('SESS_APP_ID', '_sess_app_id_');
define('KEY', '__\$mbh\$__');
session_start([
  'use_strict_mode' => true,
  'use_cookies' => true,
  'cookie_lifetime' => SESSION_TIME,
  'cookie_httponly' => true # Avoid access to the cookie using scripting languages (such as javascript)
]);

# Firewall
define('FIREWALL', true);

# DEBUG mode
define('DEBUG', false);

# Check which is the current template engine (TWIG: true or PLATESPHP: false)
define('TWIG_TEMPLATE_ENGINE', false);

define('E_ERNO','69');

# Current version of the framework
define('VERSION', '1.0');
