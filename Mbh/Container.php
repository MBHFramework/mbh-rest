<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;

use Mbh\Collection;
use Mbh\Interfaces\ContainerInterface;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
class Container implements ContainerInterface
{
    private $values = [];

    private $keys = [];

    /**
     * @var Storage[]
     *
     */
    protected $storage = [];

    /**
     * @var Setting[]
     *
     */
    protected $defaultSettings = [
      'httpVersion' => '1.1',
      'responseChunkSize' => 4096,
      'displayErrorDetails' => false,
      'routerCacheFile' => false,
      'firewall' => false,
      'debug' => false,
    ];

    /**
     * Create new container
     *
     * @param array $values The parameters or objects.
     */
    public function __construct(array $values = [])
    {
        foreach ($values as $key => $value) {
          $this[$key] = $value;
        }

        $userSettings = isset($values['settings']) ? $values['settings'] : [];
        $this->registerDefaultServices($userSettings);
    }

    /**
     * This function registers the default services that Mbh needs to work.
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
         * instance of ArrayAccess.
         *
         * @return array|ArrayAccess
         */
        $this['settings'] = function () use ($userSettings, $defaultSettings) {
            return new Collection(array_merge($defaultSettings, $userSettings));
        };

        $defaultProvider = new DefaultServicesProvider();
        $defaultProvider->register($this);
    }

    /**
     * Sets a parameter or an object.
     *
     * Objects must be defined as Closures.
     *
     * Allowing any PHP callable leads to difficult to debug problems
     * as function names (strings) are callable (creating a function with
     * the same name as an existing parameter would break your container).
     *
     * @param string $key    The unique identifier for the parameter or object
     * @param mixed  $value The value of the parameter or a closure to define an object
     *
     * @throws FrozenService\Exception Prevent override of a frozen service
     */
    public function offsetSet($key, $value)
    {
        $this->values[$key] = $value;
        $this->keys[$key] = true;
    }

    /**
     * Gets a parameter or an object.
     *
     * @param string $key The unique identifier for the parameter or object
     *
     * @return mixed The value of the parameter or an object
     *
     * @throws \Exception If the identifier is not defined
     */
    public function offsetGet($key)
    {
        if (!isset($this->keys[$key])) {
            throw new \Exception($key);
        }

        if (
          !is_object($this->values[$key])
          || !method_exists($this->values[$key], '__invoke')
        ) {
            return $this->values[$key];
        }

        $value = $this->values[$key]($this);
        $this->values[$key] = $value;
        return  $value;
    }
    /**
     * Checks if a parameter or an object is set.
     *
     * @param string $key The unique identifier for the parameter or object
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return isset($this->keys[$key]);
    }

    /**
     * Unsets a parameter or an object.
     *
     * @param string $key The unique identifier for the parameter or object
     */
    public function offsetUnset($key)
    {
        if (isset($this->keys[$key])) {
            unset($this->values[$key], $this->keys[$key]);
        }
    }

    /**
     * Methods to satisfy ContainerInterface
     */

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $key Identifier of the entry to look for.
     *
     *
     * @return mixed Entry.
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw new \Exception(sprintf('Identifier "%s" is not defined.', $key));
        }
        try {
            return $this->offsetGet($key);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $key Identifier of the entry to look for.
     *
     * @return boolean
     */
    public function has($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Magic methods for convenience
     */

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __isset($name)
    {
        return $this->has($name);
    }
}
