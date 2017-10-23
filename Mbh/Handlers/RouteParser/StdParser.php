<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh\Handlers\RouteParser;

use \Mbh\Helpers\Path;
use \Mbh\Helpers\Uri;
use \Mbh\Interfaces\RouteInterface;
use \Mbh\Interfaces\RouteParserInterface;

/**
 * @author Ulises Jeremias Cornejo Fandos
 */
class StdParser implements RouteParserInterface
{
    /** Regular expression used to find named parameters in routes */
    const REGEX_PATH_PARAMS = '/(?<=\/):([^\/]+)(?=\/|$)/';
    /** Regular expression used to match a single segment of a path */
    const REGEX_PATH_SEGMENT = '([^\/]+)';
    /** Delimiter used in regular expressions */
    const REGEX_DELIMITER = '/';

    /**
     * Attempts to match the route of the current request against the specified route
     *
     * @return array|null the list of matched parameters or `null` if the route didn't match
     */
    public function handle(RouteInterface $route)
    {
        $params = [];

        // create the regex that matches paths against the route
        $patternRegex = $this->createRouteRegex($route, $route->getPattern(), $params);

        // if the route regex matches the current request path
        if (preg_match($patternRegex, $route->getRoute(), $matches)) {
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

    /**
     * Creates a regular expression that can be used to match the specified route
     *
     * @param string $pattern the route to create a regular expression for
     * @param array $params the array that should receive the matched parameters
     * @return string the composed regular expression
     */
    private function createRouteRegex($route, $pattern, &$params)
    {
        // extract the parameters from the route (if any) and make the route a regex
        self::processUriParams($pattern, $params);

        // escape the base path for regex and prepend it to the route
        return static::REGEX_DELIMITER . '^' . static::regexEscape($route->getRootPath()) . $pattern . '$' . static::REGEX_DELIMITER;
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
