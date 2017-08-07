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
        require_once 'MBHF/core.php';
    }

    final public static function registerAutoload()
    {
        spl_autoload_register(__NAMESPACE__ . "\\App::autoload");
    }

    final public function __construct()
    {
        $this->firewall = !FIREWALL ?: new Firewall;
        $this->startime = !DEBUG ?: Debug::startime();
    }

    final public function run()
    {
        $this->debug = !DEBUG ?: new Debug($this->startime);
    }
}
