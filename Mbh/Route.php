<?php

/**
 * MBHFramework
 *
 * @link    https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;

# Security
defined('INDEX_DIR') or exit(APP_NAME . 'software says .i.');

/**
 * created by Federico Ramón Gasquez
 */
final class Route
{
    protected $uri;
    protected $closure;

    const PARAMETER_PATTERN = '/:([\w-%]+)/';
    const PARAMETER_REPLACEMENT = '(?<\1>[^/]+)';

    protected $parameters;

    /**
      * Construnct function
      *
      * @param string $uri, URI pattern
      * @param $closure, instanceof Closure or function identifier
      *
      * @return void
      */
    public function __construct(string $uri, $closure)
    {
        $this->uri = $uri;
        $this->closure = $closure;
    }

    /**
      * Generates the regular expression of the uri
      *
      * @return string
      */
    public function getUriPattern(): string
    {
        $uriPattern = preg_replace(self::PARAMETER_PATTERN, self::PARAMETER_REPLACEMENT, $this->uri);
        $uriPattern = str_replace('/', '\/', $uriPattern);
        $uriPattern = '/^' . $uriPattern . '\/*$/';
        return $uriPattern;
    }

    /**
      * Return an array with names of parameters
      *
      * @return array
      */
    public function getParameterNames()
    {
        preg_match_all(self::PARAMETER_PATTERN, $this->uri, $parameterNames);
        return array_flip($parameterNames[1]);
    }

    public function resolveParameters($matches)
    {
        $this->parameters = array_intersect_key($matches, $this->getParameterNames());
    }

    /**
      * Return an array whith parameters
      *
      * @return array
      */
    public function getParameters()
    {
        return $this->parameters;
    }

    public function checkIfMatch($requestUri): bool
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
