<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;

# Security
defined('INDEX_DIR') or exit(APP_NAME . 'software says .i.');

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
final class Router
{
    protected $request_uri;
    protected $routes;

    final public function __construct(string $request_uri = $_SERVER['REQUEST_URI'])
    {
          $this->setRequestUri($request_uri);
          $this->routes = [];
    }

    public function setRequestUri($request_uri)
  	{
          if (strpos($request_uri, self::GET_PARAMS_DELIMITER))
          {
          	$request_uri = strstr($request_uri, self::GET_PARAMS_DELIMITER, true);
          }
          $this->requestUri = $request_uri;
  	}

  	public function getRequestUri()
  	{
          return $this->requestUri;
  	}
}
