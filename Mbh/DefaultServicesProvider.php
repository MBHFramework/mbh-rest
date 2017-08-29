<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;

use Exception;
use Mbh\Firewall;
use Mbh\Router;
use Mbh\Storage\Session;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
class DefaultServicesProvider
{
    /**
     * Register Mbh's default services.
     *
     * @param Container $container A DI container implementing ArrayAccess and container-interop.
     */
    public function register($container)
    {
        if (! isset($container['firewall'])) {
            $container['firewall'] = function ($container) {
                if (isset($container->get('settings')['firewall'])) {
                    return new Firewall;
                }
            };
        }

        if (! isset($container['session'])) {
            $container['session'] = function ($container) {
                return new Session;
            };
        }

        if (! isset($container['router'])) {
            /**
             * This service MUST return a SHARED instance
             * of Mbh\Interfaces\RouterInterface.
             *
             * @param Container $container
             *
             * @return RouterInterface
             */
            $container['router'] = function ($container) {
                $routerCacheFile = false;
                if (isset($container->get('settings')['routerCacheFile'])) {
                    $routerCacheFile = $container->get('settings')['routerCacheFile'];
                }

                $router = (new Router)->setCacheFile($routerCacheFile);
                if (method_exists($router, 'setContainer')) {
                    $router->setContainer($container);
                }

                if (method_exists($router, 'setCallableResolver')) {
                    $router->setCallableResolver($container['callableResolver']);
                }

                return $router;
            };
        }
    }
}
