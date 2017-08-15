<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;

use Exception;
use \Mbh\Debug;
use \Mbh\Firewall;
use \Mbh\Router;
use \Mbh\Storage\Session;
use \Mbh\Interfaces\RouterInterface;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
class App
{
    protected $router;

    protected $startime;

    protected $storage;

    protected $settings;

    public static function autoload($class)
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

    public static function registerAutoload()
    {
        spl_autoload_register(__NAMESPACE__ . "\\App::autoload");
    }

    public function __construct()
    {
        try {
            if (version_compare(phpversion(), '7.0.0', '<'))
                throw new Exception(true);
        } catch (Exception $e) {
            die('Current <b>PHP</b> version is <b>' . phpversion() . '</b> and a version greater than or equal to <b>7.0.0</b> is required');
        }

        !FIREWALL ?: new Firewall;

        $this->startime = !DEBUG ?: Debug::startime();
        $this->storage['session'] = new Session();

        $this->setRouter(new Router());
    }

    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    public function setRouter(RouterInterface $router = null)
    {
        $this->router = $router;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function addSettings(array $settings)
    {
        $this->settings = array_merge($this->settings, $settings);
    }

    /**
     * Router methods
     */

    public function get()
    {
        $this->getRouter()->get(...func_get_args());
    }

    public function post()
    {
        $this->getRouter()->post(...func_get_args());
    }

    public function put()
    {
        $this->getRouter()->put(...func_get_args());
    }

    public function patch()
    {
        $this->getRouter()->patch(...func_get_args());
    }

    public function delete()
    {
        $this->getRouter()->delete(...func_get_args());
    }

    public function head()
    {
        $this->getRouter()->head(...func_get_args());
    }

    public function trace()
    {
        $this->getRouter()->trace(...func_get_args());
    }

    public function options()
    {
        $this->getRouter()->options();
    }

    public function connect()
    {
        $this->getRouter()->connect(...func_get_args());
    }

    public function any()
    {
        $this->getRouter()->any(...func_get_args());
    }

    public function map()
    {
        $this->getRouter()->map(...func_get_args());
    }

    public function run()
    {
        $this->getRouter()->run();
        !DEBUG ?: new Debug($this->startime);
    }
}
