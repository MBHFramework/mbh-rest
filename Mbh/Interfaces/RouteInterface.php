<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh\Interfaces;

use \Mbh\Interfaces\RouteParserInterface;

/**
 * created by Federico Ramón Gasquez
 */
interface RouteInterface
{
    public function checkIfMatch($routeParser);

    public function run($routeParser);
}
