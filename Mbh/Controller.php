<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;

use Twig_Enviroment;
use Twig_Loader_Filesystem;
use League\Plates\Engine;

use Mbh\App;
use Mbh\Storage\Session;
use Mbh\Helpers\Functions;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
class Controller
{
    protected $app;

    protected $template;

    protected $sessions = null;

    private static $instance;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct(App $app = null, bool $LOGGED = false, bool $UNLOGGED = false)
    {
        $this->app = $app;

        if (TWIG_TEMPLATE_ENGINE) {
            $this->template = new Twig_Enviroment(new Twig_Loader_Filesystem('./www/templates/twig'), [
                'cache' => './www/templates/twig/.cache',
                'auto_reload' => true,
                'debug' => DEBUG
            ]);

            $this->template->addGlobal('session', new Session());
            $this->template->addGlobal('get', $_GET);
            $this->template->addGlobal('post', $_POST);

            $this->template->addExtension(new Functions());
        } else {
            $this->template = new Engine('./www/templates/plates', 'phtml');
        }
    }

    protected function render($template)
    {
        return $this->template->render($template);
    }
}
