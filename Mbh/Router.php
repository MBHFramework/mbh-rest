<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;

use Response;
use \Mbh\Route;
use \Mbh\RouteCollection;
use \Mbh\Helpers\Path;
use \Mbh\Helpers\Uri;
use \Mbh\Interfaces\RouterInterface;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
final class Router implements RouterInterface
{
    /** Router for PHP. Simple, lightweight and convenient. */

    private $routes = [];

    public function __construct()
    {
        $this->setRoutes(new RouteCollection());
    }

    public function setRoutes(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Returns the root path that this instance is working under
     *
     * @return string the path
     */
    public function getRootPath()
    {
        return $this->rootPath;
    }

    /**
     * Returns the route of the current request
     *
     * @return string the route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Returns the request method of the current request
     *
     * @return string the method name
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

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
        return $this->map([ 'GET' ], $pattern, $callback, $inject);
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
        return $this->map([ 'POST' ], $pattern, $callback, $inject);
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
        return $this->map([ 'PUT' ], $pattern, $callback, $inject);
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
        return $this->map([ 'PATCH' ], $pattern, $callback, $inject);
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
        return $this->map([ 'DELETE' ], $pattern, $callback, $inject);
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
        return $this->map([ 'HEAD' ], $pattern, $callback, $inject);
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
        return $this->map([ 'TRACE' ], $pattern, $callback, $inject);
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
        return $this->map([ 'OPTIONS' ], $pattern, $callback, $inject);
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
        return $this->map([ 'CONNECT' ], $pattern, $callback, $inject);
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
        return $this->map([
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

    /**
     * Adds a new route for all of the specified HTTP request methods
     *
     * @param string[] $methods the request methods, one of which must be detected in order to have a match
     * @param string $pattern the route that must be found in order to have a match
     * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
     * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
     * @return bool whether the route matched the current request
     */
    public function map(array $methods, $pattern, $callback = null, $inject = null)
    {
        if (! is_string($pattern)) {
          throw new Exception("Uri pattern should be a string variable", 1);
        }

        // According to RFC methods are defined in uppercase (See RFC 7231)
        $methods = array_map("strtoupper", $methods);

        $this->addRoute($methods, $pattern, $callback, $inject);
    }

    private function addRoute(array $methods, $pattern, $callback = null, $inject = null)
    {
      $route = new Route($methods, $pattern, $callback, $inject);
      $this->routes->attachRoute($route);
    }

    public function run()
  	{
    		$response = null;
    		foreach ($this->routes->all() as $route)
    		{
      			if ($route->checkIfMatch()) {
        				$response = $route->execute();
        				break;
      			}
    		}
    		$this->sendResponse($response);
  	}

    private function sendResponse($response)
  	{
    		if (is_string($response))
    		{
    			   echo $response;
    		}
    		else if (is_array($response))
    		{
    			   echo json_encode($response);
    		}
    		else if ($response instanceof Response)
    		{
    			   $response->execute();
    		}
    		else
    		{
      			header("HTTP/1.1 404 Not Found");
      			exit('404');
    		}
  	}
}
