<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;

use Mbh\App;
use Mbh\Storage\Session;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
class Controller
{
    protected $template;
    protected $sessions = null;
    protected $app;
    private static $instance;

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct(App $app = null, bool $LOGGED = false, bool $UNLOGGED = false)
    {

    }
}
