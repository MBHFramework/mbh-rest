<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh\Interfaces;

/**
 * created by Federico Ramón Gasquez
 */

interface RouterInterface
{
  /**
   * Adds a new route for the HTTP request method `GET` and executes the specified callback if the route matches
   *
   * @param string $route the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function get($route, $callback = null, $injectArgs = null);

  /**
   * Adds a new route for the HTTP request method `POST` and executes the specified callback if the route matches
   *
   * @param string $route the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function post($route, $callback = null, $injectArgs = null);

  /**
   * Adds a new route for the HTTP request method `PUT` and executes the specified callback if the route matches
   *
   * @param string $route the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function put($route, $callback = null, $injectArgs = null);

  /**
   * Adds a new route for the HTTP request method `PATCH` and executes the specified callback if the route matches
   *
   * @param string $route the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function patch($route, $callback = null, $injectArgs = null);

  /**
   * Adds a new route for the HTTP request method `DELETE` and executes the specified callback if the route matches
   *
   * @param string $route the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function delete($route, $callback = null, $injectArgs = null);

  /**
   * Adds a new route for the HTTP request method `HEAD` and executes the specified callback if the route matches
   *
   * @param string $route the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function head($route, $callback = null, $injectArgs = null);

  /**
   * Adds a new route for the HTTP request method `TRACE` and executes the specified callback if the route matches
   *
   * @param string $route the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function trace($route, $callback = null, $injectArgs = null);

  /**
   * Adds a new route for the HTTP request method `OPTIONS` and executes the specified callback if the route matches
   *
   * @param string $route the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function options($route, $callback = null, $injectArgs = null);

  /**
   * Adds a new route for the HTTP request method `CONNECT` and executes the specified callback if the route matches
   *
   * @param string $route the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function connect($route, $callback = null, $injectArgs = null);

  /**
   * Adds a new route for all of the specified HTTP request methods and executes the specified callback if the route matches
   *
   * @param string[] $requestMethods the request methods, one of which to match
   * @param string $route the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function any(array $requestMethods, $route, $callback = null, $injectArgs = null);

  /**
   * Checks the specified request method and route against the current request to see whether it matches
   *
   * @param string[] $expectedRequestMethods the request methods, one of which must be detected in order to have a match
   * @param string $expectedRoute the route that must be found in order to have a match
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function map(array $expectedRequestMethods, $expectedRoute, $callback = null, $injectArgs = null);

}
