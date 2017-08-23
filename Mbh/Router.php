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
use RuntimeException;
use InvalidArgumentException;

use \Mbh\Route;
use \Mbh\RouteCollection;
use \Mbh\Helpers\Path;
use \Mbh\Helpers\Uri;
use \Mbh\Interfaces\RouterInterface;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
class Router implements RouterInterface
{
    /**
     * Parser
     *
     * @var \FastRoute\RouteParser
     */
    protected $routeParser;

    /**
     * @var \Slim\Interfaces\InvocationStrategyInterface
     */
    protected $routeInvocationStrategy;

    /**
     * Base path used in pathFor()
     *
     * @var string
     */
    protected $basePath = '';

    /**
     * Path to fast route cache file. Set to false to disable route caching
     *
     * @var string|False
     */
    protected $cacheFile = false;

    /**
     * Routes
     *
     * @var Route[]
     */
    protected $routes = [];

    /**
     * Create new router
     *
     * @param RouteParser   $parser
     */
    public function __construct(RouteParser $parser = null)
    {
        // $this->routeParser = $parser ?: new RouteParser;
    }

    /**
     * Set path to fast route cache file. If this is false then route caching is disabled.
     *
     * @param string|false $cacheFile
     *
     * @return self
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function setCacheFile($cacheFile)
    {
        if (!is_string($cacheFile) && $cacheFile !== false) {
            throw new InvalidArgumentException('Router cacheFile must be a string or false');
        }
        $this->cacheFile = $cacheFile;
        if ($cacheFile !== false && !is_writable(dirname($cacheFile))) {
            throw new RuntimeException('Router cacheFile directory must be writable');
        }
        return $this;
    }

    public function setRoutes(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    public function getRoutes()
    {
        if (! $this->routes instanceof RouteCollection) {
            $this->routes = new RouteCollection();
        }
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
        $this->getRoutes()->attachRoute($route);
    }

    public function run()
    {
        $routes = $this->getRoutes()->getThatMatch();
        $route = !$routes ?: $routes[0];
        $response = !$route ?: $route->execute();
        $this->sendResponse($response);
    }

    private function sendResponse($response)
    {
        if (is_string($response)) {
            echo $response;
        } elseif (is_array($response)) {
            echo json_encode($response);
        } elseif ($response instanceof Response) {
            $response->execute();
        } else {
            header("HTTP/1.1 404 Not Found");
            exit('404');
        }
    }
}
