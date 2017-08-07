<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Federico R.  Gasquez
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;
/**
*
*/

final class Route
{
  protected $uri;
  protected $closure

  const PARAMETER_PATTERN = '/:([^\/]+)/';
  const PARAMETER_REPLACEMENT = '(?<\1>[^/]+)';

  protected $parameters;

  /**
  *Construnct function
  *
  *@param $uri = URL pattern
  *@param $closure = anon function
  *
  *@return void
  */

  public function __construct($uri, $closure)
  {
    $this->uri = $uri;
    $this->closure = $closure;
  }


  /**
  *Generates the regular expression of the url
  *
  *@return string
  */

  public function getUriPattern()
  {
    $uriPattern = preg_replace(self::PARAMETER_PATTERN, self::PARAMETER_REPLACEMENT, $this->uri);
    $uriPattern = str_replace('/', '\/', $uriPattern);
    $uriPattern = '/^' . $uriPattern . '\/*$/s';
    return $uriPattern;
  }


  /**
  *Return an array with names of parameters
  *
  *@return array
  */

  public function getParameterNames()
  {
    preg_match_all(self::PARAMETER_PATTERN, $this->uri, $parameterNames);
    return array_flip($parameterNames[1]);
  }

  /**
  *
  *
  *@param $matches =
  *
  *@return void
  */

  public function resolveParameters($matches)
  {
    $this->parameters = array_intersect_key($matches, $this->getParameterNames());
  }

  /**
  *Return an array whith parameters
  *
  *
  *@return array
  */

  public function getParameters()
  {
    return $this->parameters;
  }

  public function checkIfMatch($requestUri)
  {
    $uriPattern = $this->getUriPattern();
    if(preg_match($uriPattern, $requestUri, $matches)){
      $this->resolveParameters($matches);
      return true;
    }
    return false;
  }


  public function execute()
  {
    $closure = $this->closure;
    $parameters = $this->getParameters();
    return call_user_func_array($closure, $parameters);
  }
}
