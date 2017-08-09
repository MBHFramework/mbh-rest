<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;

use PDOStatement;
use Mbh\Connection;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
class Model
{
    protected $db;
    protected $id;

    protected $table;

    protected function __construct()
    {
        $this->db = Connection::start();
        $this->$table = [];
    }

    protected function __destruct()
    {
        $this->db = null;
    }
}
