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
   * @param string $pattern the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function get($pattern, $callback = null, $inject = null);

  /**
   * Adds a new route for the HTTP request method `POST` and executes the specified callback if the route matches
   *
   * @param string $pattern the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function post($pattern, $callback = null, $inject = null);

  /**
   * Adds a new route for the HTTP request method `PUT` and executes the specified callback if the route matches
   *
   * @param string $pattern the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function put($pattern, $callback = null, $inject = null);

  /**
   * Adds a new route for the HTTP request method `PATCH` and executes the specified callback if the route matches
   *
   * @param string $pattern the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function patch($pattern, $callback = null, $inject = null);

  /**
   * Adds a new route for the HTTP request method `DELETE` and executes the specified callback if the route matches
   *
   * @param string $pattern the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function delete($pattern, $callback = null, $inject = null);

  /**
   * Adds a new route for the HTTP request method `HEAD` and executes the specified callback if the route matches
   *
   * @param string $pattern the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function head($pattern, $callback = null, $inject = null);

  /**
   * Adds a new route for the HTTP request method `TRACE` and executes the specified callback if the route matches
   *
   * @param string $pattern the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function trace($pattern, $callback = null, $inject = null);

  /**
   * Adds a new route for the HTTP request method `OPTIONS` and executes the specified callback if the route matches
   *
   * @param string $pattern the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function options($pattern, $callback = null, $inject = null);

  /**
   * Adds a new route for the HTTP request method `CONNECT` and executes the specified callback if the route matches
   *
   * @param string $pattern the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function connect($pattern, $callback = null, $inject = null);

  /**
   * Adds a new route for all of the specified HTTP request methods and executes the specified callback if the route matches
   *
   * @param string[] $requestMethods the request methods, one of which to match
   * @param string $pattern the route to match, e.g. `/users/jane`
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function any($pattern, $callback = null, $inject = null);

  /**
   * Checks the specified request method and route against the current request to see whether it matches
   *
   * @param string[] $expectedRequestMethods the request methods, one of which must be detected in order to have a match
   * @param string $expectedRoute the route that must be found in order to have a match
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function map(array $expectedRequestMethods, $expectedRoute, $callback = null, $inject = null);

}
