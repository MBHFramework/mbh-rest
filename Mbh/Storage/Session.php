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
class Session
{
    const SESSION_TIME = 18000;

    public function __construct()
    {
        if (!headers_sent()) {
            session_start([
              'use_strict_mode' => true,
              'use_cookies' => true,
              'cookie_lifetime' => static::SESSION_TIME, # Life time for session cookies -> 5 hs = 18000 seconds.
              'cookie_httponly' => true # Avoid access to the cookie using scripting languages (such as javascript)
            ]);
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
        return $this;
    }

    public function setFlash($identifier, $message)
    {
        $_SESSION[$identifier] = $message;
        return $this;
    }

    public function getFlash($identifier)
    {
        if (array_key_exists($identifier, $_SESSION)) {
            $keep = $_SESSION[$identifier];
            $this->delete($identifier);
            return $keep;
        }
    }

    public function get($key)
    {
        return !$this->has($key) ?: $_SESSION[$key];
    }

    public function has($key)
    {
        return (array_key_exists($key, $_SESSION));
    }

    public function delete($key)
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
        return $this;
    }

    public function destroy()
    {
        session_destroy();
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }

    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function offsetUnset($offset)
    {
        return $this->delete($offset);
    }
}
