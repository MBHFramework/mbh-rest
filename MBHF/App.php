<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace MBHF;

# Security
defined('INDEX_DIR') or exit(APP_NAME . 'software says .i.');

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
final class App
{
    final public static function autoload($className)
    {
        require_once __DIR__ . '/helpers/classes/Functions.php';
        require_once __DIR__ . '/Debug.php';
        require_once __DIR__ . '/Firewall.php';
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

        $this->firewall = !FIREWALL ?: new \MBHF\Firewall;
        $this->startime = !DEBUG ?: \MBHF\Debug::startime();
    }

    final public function run()
    {
        $this->debug = !DEBUG ?: new \MBHF\Debug($this->startime);
    }
}
