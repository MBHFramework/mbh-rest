<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;

use Mbh\Connection as Connection;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
class Model
{
    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = new Connection();
        $this->$table = [];
    }

    public function __destruct()
    {
        $this->db = null;
    }
}
