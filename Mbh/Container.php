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
use Mbh\Interfaces\ContainerInterface;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
class Container extends SplObjectStorage implements ContainerInterface
{
    /**
     * @var Storage[]
     *
     */
    protected $storage = [];

    /**
     * @var Setting[]
     *
     */
    protected $settings = [
      'httpVersion' => '1.1',
      'responseChunkSize' => 4096,
      'displayErrorDetails' => false,
      'routerCacheFile' => false,
      'debug' => false,
      'firewall' => false
    ];

    /**
     * Create new container
     *
     * @param array $values The parameters or objects.
     */
    public function __construct(array $values = [])
    {
        parent::__construct($values);
        $userSettings = isset($values['settings']) ? $values['settings'] : [];
        $this->registerDefaultServices($userSettings);
    }

    /**
     * This function registers the default services that Slim needs to work.
     *
     * All services are shared - that is, they are registered such that the
     * same instance is returned on subsequent calls.
     *
     * @param array $userSettings Associative array of application settings
     *
     * @return void
     */
    private function registerDefaultServices($userSettings)
    {
        $defaultSettings = $this->defaultSettings;
        /**
         * This service MUST return an array or an
         * instance of \ArrayAccess.
         *
         * @return array|\ArrayAccess
         */
        $this['settings'] = function () use ($userSettings, $defaultSettings) {
            return new Collection(array_merge($defaultSettings, $userSettings));
        };
        $defaultProvider = new DefaultServicesProvider();
        $defaultProvider->register($this);
    }
}
