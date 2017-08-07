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

    /**
      * @param string $requestUri
      *
      */
    final public function __construct(string $requestUri = $_SERVER['REQUEST_URI'])
    {
        $this->setRequestUri($requestUri);
        $this->routes = [];
    }

    /**
      * @param string $requestUri
      *
      */
    final public function setRequestUri(string $requestUri = $_SERVER['REQUEST_URI'])
    {
        if (strpos($requestUri, self::GET_PARAMS_DELIMITER)) {
            $requestUri = strstr($requestUri, self::GET_PARAMS_DELIMITER, true);
        }
        $this->requestUri = $requestUri;
    }

    /**
      * @return string requestUri
      *
      */
    final public function getRequestUri(): string
    {
        return $this->requestUri;
    }

    /**
      * Loads a route generated from a url pattern and a closure to the route collection.
      *
      * @param string $uri, URI pattern
      * @param $closure, instanceof Closure or function identifier
      *
      */
    final public function add(string $uri, $closure)
    {
        array_push($this->routes, new Route($uri, $closure));
    }

    /**
      * Sends response.
      *
      * @param $response
      *
      */
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
        })[0];

        $response = $route->execute();
        $this->sendResponse($response);
    }
}
