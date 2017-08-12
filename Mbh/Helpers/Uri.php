<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh\Helpers;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
final class Uri
{
    /** Uniform Resource Identifier (URI) */

    private $str;

    /**
      * Constructor
      *
      * @param string $str the URI as a string
      */
    public function __construct($str)
    {
        $this->str = $str;
    }

    /**
      * Removes the query component from this string
      *
      * @return static this instance for chaining
      */
    public function removeQuery()
    {
        $this->str = strtok($this->str, '?');

        return $this;
    }

    public function __toString()
    {
        return $this->str;
    }
}
