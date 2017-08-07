<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;

use \Mbh\Route as Route;

# Security
defined('INDEX_DIR') or exit(APP_NAME . 'software says .i.');

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
final class Router
{
    protected $request_uri;
    protected $routes;

    const GET_PARAMS_DELIMITER = '?';

    final public function __construct(string $request_uri = $_SERVER['REQUEST_URI'])
    {
        $this->setRequestUri($request_uri);
        $this->routes = [];
    }

    final public function setRequestUri(string $request_uri = $_SERVER['REQUEST_URI'])
    {
        if (strpos($request_uri, self::GET_PARAMS_DELIMITER)) {
            $request_uri = strstr($request_uri, self::GET_PARAMS_DELIMITER, true);
        }
        $this->requestUri = $request_uri;
    }

    final public function getRequestUri()
    {
        return $this->requestUri;
    }

    final public function add($uri, $closure)
    {
        array_push($this->routes, new Route($uri, $closure));
    }
}
