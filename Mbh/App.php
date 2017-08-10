<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;

use \Mbh\Debug;
use \Mbh\Firewall;
use \Mbh\Router;
use \Mbh\Storage\Session;
use \Mbh\Interfaces\RouterInterface;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
final class App
{
    private $router;
    private $startime;
    private $storage;

    final public static function autoload($class)
    {
        $prefix = __NAMESPACE__ . '\\';
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
                throw new \Exception(true);
        } catch (\Exception $e) {
            die('Current <b>PHP</b> version is <b>' . phpversion() . '</b> and a version greater than or equal to <b>7.0.0</b> is required');
        }

        !FIREWALL ?: new Firewall;

        $this->startime = !DEBUG ?: Debug::startime();
        $this->storage['session'] = new Session();

        $this->setRouter(new Router());
    }

    final public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    final public function setRouter(RouterInterface $router = null)
    {
        $this->router = $router;
    }

    final public function get()
    {
        return $this->getRouter()->get(...func_get_args());
    }

    final public function post()
    {
        return $this->getRouter()->post(...func_get_args());
    }

    final public function put()
    {
        return $this->getRouter()->put(...func_get_args());
    }

    final public function patch()
    {
        return $this->getRouter()->patch(...func_get_args());
    }

    final public function delete()
    {
        return $this->getRouter()->delete(...func_get_args());
    }

    final public function head()
    {
        return $this->getRouter()->head(...func_get_args());
    }

    final public function trace()
    {
        return $this->getRouter()->trace(...func_get_args());
    }

    final public function options()
    {
        return $this->getRouter()->options();
    }

    final public function connect()
    {
        return $this->getRouter()->connect(...func_get_args());
    }

    final public function any()
    {
        return $this->getRouter()->any(...func_get_args());
    }

    final public function map()
    {
        return $this->getRouter()->map(...func_get_args());
    }

    final public function run()
    {
        $this->debug = !DEBUG ?: new Debug($this->startime);
    }
}
