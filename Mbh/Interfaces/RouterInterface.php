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
   * Checks the specified request method and route against the current request to see whether it matches
   *
   * @param string[] $methods the request methods, one of which must be detected in order to have a match
   * @param string $pattern the route that must be found in order to have a match
   * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
   * @param array|null $inject (optional) any arguments that should be prepended to those matched in the route
   * @return bool whether the route matched the current request
   */
  public function map(array $methods, $pattern, $callback = null, $inject = null);

  public function run();
}
