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
 * Describes the interface of a container that exposes methods to read its entries.
 *
 * created by Ulises Jeremias Cornejo Fandos
 */
interface ContainerInterface extends \ArrayAccess
{
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param $key Identifier of the entry to look for.
     *
     * @return mixed Entry.
     */
    public function get($key);
    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param $key Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($key);
}
