<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;

use SplObjectStorage;
use \Mbh\Interfaces\RouteInterface;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
class RouteCollection extends SplObjectStorage
{
    /**
     * Attach a Route to the collection.
     *
     * @param Route $attachObject
     */
    public function attachRoute(RouteInterface $attachObject)
    {
        parent::attach($attachObject, null);
    }

    /**
     * Fetch all routes stored on this collection of routes and return it.
     *
     * @return Route[]
     */
    public function all()
    {
        $temp = array();
        foreach ($this as $route) {
            $temp[] = $route;
        }
        return $temp;
    }

    public function getThatMatch()
    {
        return array_values(array_filter($this->all(), function($route) {
          return $route->checkIfMatch();
        }));
    }
}
