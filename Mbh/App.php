<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;

# Security
defined('INDEX_DIR') or exit(APP_NAME . 'software says .i.');

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
final class App
{
    final public static function autoload($class)
    {
        $prefix = 'Mbh\\';
      	$length = strlen($prefix) - 1;
        $base_directory = __DIR__;

      	if(strncmp($prefix, $class, $length) !== 0) {
          return;
        }

        $class_end = substr($class, $length);
        $file = $base_directory . str_replace('\\', '/', $class_end) . '.php';

        if(is_readable($file)) {
          require $file;
        }
    }

    final public static function registerAutoload()
    {
        spl_autoload_register(__NAMESPACE__ . "\\App::autoload");
    }

    final public function __construct()
    {
        try {
            if (version_compare(phpversion(), '7.0.0', '<'))
                throw new Exception(true);
        } catch (Exception $e) {
            die('Current <b>PHP</b> version is <b>' . phpversion() . '</b> and a version greater than or equal to <b>7.0.0</b> is required');
        }

        $this->firewall = !FIREWALL ?: new \Mbh\Firewall;
        $this->startime = !DEBUG ?: \Mbh\Debug::startime();
    }

    final public function run()
    {
        $this->debug = !DEBUG ?: new \Mbh\Debug($this->startime);
    }
}
