<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;

use InvalidArgumentException;
use \Mbh\Helpers\Path;
use \Mbh\Helpers\Uri;
use \Mbh\Interfaces\RouteInterface;
use \Mbh\Interfaces\RouteParserInterface;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
class Route implements RouteInterface
{
    /** @var string the root path that this instance is working under */
    private $rootPath;
    /** @var string the route of the current request */
    private $route;
    /** @var string the request method of the current request */
    private $requestMethod;

    private $methods;

    private $pattern;

    private $callable;

    private $inject;

    public function __construct(array $methods, string $pattern, $callable = null, $inject = null)
    {
        $path = __ROOT__ . (IS_API ? "api" : "");
        $this->rootPath = (string) (new Path($path))->normalize()->removeTrailingSlashes();
        $this->route = urldecode((string) (new Uri($_SERVER['REQUEST_URI']))->removeQuery());
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];

        $this->setMethods($methods);
        $this->setPattern($pattern);
        $this->setCallable($callable);
        $this->setInject($inject);
    }

    public function getMethods()
    {
        return $this->methods;
    }

    public function setMethods(array $methods)
    {
        $this->methods = $methods;
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function setPattern(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function getCallable()
    {
        return $this->callable;
    }

    public function setCallable($callable)
    {
        $this->callable = $callable;
    }

    public function getInject()
    {
        return $this->inject;
    }

    public function setInject($inject)
    {
        $this->inject = $inject;
    }

    ### Resolution strategies

    /**
     * Returns the root path that this instance is working under
     *
     * @return string the path
     */
    public function getRootPath()
    {
        return $this->rootPath;
    }

    public function setRootPath(string $path)
    {
        $this->rootPath = $path;
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

    public function setRoute(string $route)
    {
        $this->route = $route;
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

    public function setRequestMethod(string $method)
    {
        $this->requestMethod = $method;
    }

    public function checkIfMatch(RouteParserInterface $routeParser)
    {
        // According to RFC methods are defined in uppercase (See RFC 7231)
        $this->setMethods(array_map("strtoupper", $this->methods));

        if (in_array($this->requestMethod, $this->methods, true)) {
            $matches = $routeParser->handle($this);
            // if the route matches the current request
            if ($matches !== null) {
                // the route matches the current request
                return true;
            }
        }
        // the route does not match the current request
        return false;
    }

    public function execute(RouteParserInterface $routeParser)
    {
        // if a callback has been set
        if (!isset($this->callable)) {
            return "";
        }

        // if the callback is invalid
        if (!is_callable($this->callable)) {
            throw new InvalidArgumentException('Invalid callback for methods `' . implode('|', $this->methods) . '` at route `' . $this->pattern . '`');
        }

        // if the callback can be executed
        // use an empty array as the default value for the arguments to be injected
        $this->inject = $this->inject ?? [];

        $callable = $this->getCallable();
        $matches = $routeParser->handle($this);

        // execute the callback
        return $callable(...$this->inject, ...array_values($matches));
    }
}
