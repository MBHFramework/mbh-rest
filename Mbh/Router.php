<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;

use \Mbh\Route;

# Security
defined('INDEX_DIR') or exit(APP_NAME . 'software says .i.');

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
final class Router
{
    protected $requestUri;
    protected $routes;

    const GET_PARAMS_DELIMITER = '?';

    final public function __construct(string $requestUri = $_SERVER['REQUEST_URI'])
    {
        $this->setRequestUri($requestUri);
        $this->routes = [];
    }

    final public function setRequestUri(string $requestUri = $_SERVER['REQUEST_URI'])
    {
        if (strpos($requestUri, self::GET_PARAMS_DELIMITER)) {
            $requestUri = strstr($requestUri, self::GET_PARAMS_DELIMITER, true);
        }
        $this->requestUri = $requestUri;
    }

    final public function getRequestUri()
    {
        return $this->requestUri;
    }

    final public function add($uri, $closure)
    {
        array_push($this->routes, new Route($uri, $closure));
    }

    final public function sendResponse($response)
    {
        if (is_string($response)) {
            echo $response;
        } elseif (is_array($response)) {
            echo json_encode($response);
        } elseif ($response instanceof Response) {
            $response->execute();
        } else {
            header("HTTP/1.0 404 Not Found");
            exit('404');
        }
    }

    final public function run()
    {
        $response = null;
        $requestUri = $this->requestUri;

        $route = array_filter($this->routes, function($route) use($requestUri) {
            return $route->checkIfMatch($requestUri);
        })[0] ?? null;

        $response = $route->execute() ?? null;
        $this->sendResponse($response);
    }
}
