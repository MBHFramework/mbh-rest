<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh\Storage;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
final class Session
{
    final public function __construct(array $settings = SESSION['CONF'])
    {
        if (!headers_sent()) {
            session_start($settings);
        }
    }

    final public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    final public function setFlash($identifier, $message)
    {
        $_SESSION[$identifier] = $message;
    }

    final public function getFlash($identifier)
    {
        if (array_key_exists($identifier, $_SESSION)) {
            $keep = $_SESSION[$identifier];
            $this->delete($identifier);
            return $keep;
        }
    }

    final public function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    final public function has($key)
    {
        return (array_key_exists($key, $_SESSION));
    }

    final public function delete($key)
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }

    final public function destroy()
    {
        session_destroy();
    }

    final public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    final public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    final public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    final public function offsetUnset($offset)
    {
        $this->delete($offset);
    }
}
