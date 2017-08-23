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
use \Mbh\Interfaces\RouterInterface;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
class App
{
    protected $router;

    protected $startime;

    protected $storage = [];

    protected $settings = [
      'httpVersion' => '1.1',
      'responseChunkSize' => 4096,
      'displayErrorDetails' => false,
      'routerCacheFile' => false,
      'debug' => false,
      'firewall' => false,
      'api' => true,
      'session' => [
        'use_strict_mode' => true,
        'use_cookies' => true,
        'cookie_lifetime' => 18000, # Life time for session cookies -> 5 hs = 18000 seconds.
        'cookie_httponly' => true # Avoid access to the cookie using scripting languages (such as javascript)
      ]
    ];

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

    public function __construct(array $settings = [])
    {
        try {
            if (version_compare(phpversion(), '7.0.0', '<'))
                throw new Exception(true);
        } catch (Exception $e) {
            die('Current <b>PHP</b> version is <b>' . phpversion() . '</b> and a version greater than or equal to <b>7.0.0</b> is required');
        }

        $this->addSettings($settings);

        !$this->settings['firewall'] ?: new Firewall;
        $this->startime = !$this->settings['debug'] ?: Debug::startime();
    }

    public function getRouter(): RouterInterface
    {
        if (! $this->router instanceof RouterInterface) {
          $router = new Router;
          $routerCacheFile = $this->getSetting('routerCacheFile', false);
          $router->setCacheFile($routerCacheFile);

          $this->router = $router;
        }

        return $this->router;
    }

    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Settings management
     */

    /**
     * Does app have a setting with given key?
     *
     * @param string $key
     * @return bool
     */
    public function hasSetting($key)
    {
        return isset($this->settings[$key]);
    }

    /**
     * Get app settings
     *
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Get app setting with given key
     *
     * @param string $key
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getSetting($key, $defaultValue = null)
    {
        return $this->hasSetting($key) ? $this->settings[$key] : $defaultValue;
    }

    /**
     * Merge a key-value array with existing app settings
     *
     * @param array $settings
     */
    public function addSettings(array $settings)
    {
        $this->settings = array_merge($this->settings, $settings);
    }

    /**
     * Add single app setting
     *
     * @param string $key
     * @param mixed $value
     */
    public function addSetting($key, $value)
    {
        $this->settings[$key] = $value;
    }

    /**
     * Router wrapper
     */
     /**
      * Adds a new route for the HTTP request method `GET`
      *
      * @param string $route the route to match, e.g. `/users/jane`
      * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
      * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
      * @return bool whether the route matched the current request
      */
     public function get($pattern, $callback = null, $inject = null)
     {
         return $this->getRouter()->map([ 'GET' ], $pattern, $callback, $inject);
     }

     /**
      * Adds a new route for the HTTP request method `POST`
      *
      * @param string $pattern the route to match, e.g. `/users/jane`
      * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
      * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
      * @return bool whether the route matched the current request
      */
     public function post($pattern, $callback = null, $inject = null)
     {
         return $this->getRouter()->map([ 'POST' ], $pattern, $callback, $inject);
     }

     /**
      * Adds a new route for the HTTP request method `PUT`
      *
      * @param string $pattern the route to match, e.g. `/users/jane`
      * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
      * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
      * @return bool whether the route matched the current request
      */
     public function put($pattern, $callback = null, $inject = null)
     {
         return $this->getRouter()->map([ 'PUT' ], $pattern, $callback, $inject);
     }

     /**
      * Adds a new route for the HTTP request method `PATCH`
      *
      * @param string $pattern the route to match, e.g. `/users/jane`
      * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
      * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
      * @return bool whether the route matched the current request
      */
     public function patch($pattern, $callback = null, $inject = null)
     {
         return $this->getRouter()->map([ 'PATCH' ], $pattern, $callback, $inject);
     }

     /**
      * Adds a new route for the HTTP request method `DELETE`
      *
      * @param string $pattern the route to match, e.g. `/users/jane`
      * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
      * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
      * @return bool whether the route matched the current request
      */
     public function delete($pattern, $callback = null, $inject = null)
     {
         return $this->getRouter()->map([ 'DELETE' ], $pattern, $callback, $inject);
     }

     /**
      * Adds a new route for the HTTP request method `HEAD`
      *
      * @param string $pattern the route to match, e.g. `/users/jane`
      * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
      * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
      * @return bool whether the route matched the current request
      */
     public function head($pattern, $callback = null, $inject = null)
     {
         return $this->getRouter()->map([ 'HEAD' ], $pattern, $callback, $inject);
     }

     /**
      * Adds a new route for the HTTP request method `TRACE`
      *
      * @param string $pattern the route to match, e.g. `/users/jane`
      * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
      * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
      * @return bool whether the route matched the current request
      */
     public function trace($pattern, $callback = null, $inject = null)
     {
         return $this->getRouter()->map([ 'TRACE' ], $pattern, $callback, $inject);
     }

     /**
      * Adds a new route for the HTTP request method `OPTIONS`
      *
      * @param string $pattern the route to match, e.g. `/users/jane`
      * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
      * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
      * @return bool whether the route matched the current request
      */
     public function options($pattern, $callback = null, $inject = null)
     {
         return $this->getRouter()->map([ 'OPTIONS' ], $pattern, $callback, $inject);
     }

     /**
      * Adds a new route for the HTTP request method `CONNECT`
      *
      * @param string $pattern the route to match, e.g. `/users/jane`
      * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
      * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
      * @return bool whether the route matched the current request
      */
     public function connect($pattern, $callback = null, $inject = null)
     {
         return $this->getRouter()->map([ 'CONNECT' ], $pattern, $callback, $inject);
     }

     /**
      * Adds a new route for all of the specified HTTP request methods
      *
      * @param string $pattern the route to match, e.g. `/users/jane`
      * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
      * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
      * @return bool whether the route matched the current request
      */
     public function any($pattern, $callback = null, $inject = null)
     {
         return $this->getRouter()->map([
           'GET',
           'POST',
           'PUT',
           'PATCH',
           'DELETE',
           'HEAD',
           'TRACE',
           'OPTIONS',
           'CONNECT'
         ], $pattern, $callback, $inject);
     }

    public function run()
    {
        $this->getRouter()->run();
        !$this->settings['debug'] ?: new Debug($this->settings, $this->startime);
    }
}
