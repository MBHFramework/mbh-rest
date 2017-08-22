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

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
class Route implements RouteInterface
{
    /** Regular expression used to find named parameters in routes */
    const REGEX_PATH_PARAMS = '/(?<=\/):([^\/]+)(?=\/|$)/';
    /** Regular expression used to match a single segment of a path */
    const REGEX_PATH_SEGMENT = '([^\/]+)';
    /** Delimiter used in regular expressions */
    const REGEX_DELIMITER = '/';

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

    /**
     * Attempts to match the route of the current request against the specified route
     *
     * @param string $pattern the route to match against
     * @return array|null the list of matched parameters or `null` if the route didn't match
     */
    private function matchRoute()
    {
        $params = [];

        // create the regex that matches paths against the route
        $patternRegex = $this->createRouteRegex($this->pattern, $params);

        // if the route regex matches the current request path
        if (preg_match($patternRegex, $this->route, $matches)) {
            if (count($matches) > 1) {
                // remove the first match (which is the full route match)
                array_shift($matches);

                // use the extracted parameters as the arguments' keys and the matches as the arguments' values
                return array_combine($params, $matches);
            } else {
                return [];
            }
        }
        // if the route regex does not match the current request path returns null
    }


    public function checkIfMatch()
    {
        // According to RFC methods are defined in uppercase (See RFC 7231)
        $this->setMethods(array_map("strtoupper", $this->methods));

        if (in_array($this->requestMethod, $this->methods, true)) {
            $matches = $this->matchRoute();
            // if the route matches the current request
            if ($matches !== null) {
                // the route matches the current request
                return true;
            }
        }
        // the route does not match the current request
        return false;
    }

    public function execute()
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
        $matches = $this->matchRoute($this->pattern);

        // execute the callback
        return $callable(...$this->inject, ...array_values($matches));
    }

    /**
     * Creates a regular expression that can be used to match the specified route
     *
     * @param string $pattern the route to create a regular expression for
     * @param array $params the array that should receive the matched parameters
     * @return string the composed regular expression
     */
    private function createRouteRegex($pattern, &$params)
    {
        // extract the parameters from the route (if any) and make the route a regex
        self::processUriParams($pattern, $params);

        // escape the base path for regex and prepend it to the route
        return static::REGEX_DELIMITER . '^' . static::regexEscape($this->rootPath) . $pattern . '$' . static::REGEX_DELIMITER;
    }

    /**
     * Extracts parameters from a path
     *
     * @param string $path the path to extract the parameters from
     * @param array $params the array that should receive the matched parameters
     */
    private static function processUriParams(&$path, &$params)
    {
        // if the route path contains parameters like `:key`
        if (preg_match_all(static::REGEX_PATH_PARAMS, $path, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE)) {
            $previousMatchEnd = 0;
            $regexParts = [];

            // extract all parameter names and create a regex that matches URIs and captures the parameters' values
            foreach ($matches as $match) {
                // remember the boundaries of the full match (e.g. `:key`) in the subject
                $matchStart = $match[0][1];
                $matchEnd = $matchStart + strlen($match[0][0]);

                // keep the part between this one and the previous match and escape it for regex
                $regexParts[] = static::regexEscape(substr($path, $previousMatchEnd, $matchStart - $previousMatchEnd));

                // save the current parameter's name
                $params[] = $match[1][0];

                // insert an expression that will match the parameter's value
                $regexParts[] = static::REGEX_PATH_SEGMENT;

                // remember the end index of the current match
                $previousMatchEnd = $matchEnd;
            }

            // keep the part after the last match and escape it for regex
            $regexParts[] = static::regexEscape(substr($path, $previousMatchEnd));

            // replace the parameterized URI with a regex that matches the parameters' values
            $path = implode('', $regexParts);
        }
        // if the route path is not parameterized
        else {
            // just escape the path for literal usage in regex
            $path = static::regexEscape($path);
        }
    }

    /**
     * Escapes the supplied string for use in a regular expression
     *
     * @param string $str the string to escape
     * @return string the escaped string
     */
    private static function regexEscape($str)
    {
        return preg_quote($str, static::REGEX_DELIMITER);
    }
}
