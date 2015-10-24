<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

abstract class Registry
{
    /**
     * Store the registry entries.
     *
     * @var array
     */
    private static $registry = array();

    /**
     * Set key to registry.
     *
     * @param  string    $key
     * @param  mixed     $value
     * @return boolean
     * @throws Exception
     */
    public static function set($key, $value)
    {
        if (!self::has($key)) {
            self::$registry[$key] = $value;

            return true;
        } else {
            throw new \Exception('Unable to set variable `' . $key . '`. It was already set.');
        }
    }

    /**
     * Check if key exists.
     *
     * @param  string  $key
     * @return boolean
     */
    public static function has($key)
    {
        if (isset(self::$registry[$key])) {
            return true;
        }

        return false;
    }

    /**
     * Get registry key.
     *
     * @param  string     $key
     * @return mixed|null
     */
    public static function get($key)
    {
        if (self::has($key)) {
            return self::$registry[$key];
        }

        return null;
    }

    /**
     * Get all registry keys.
     *
     * @return array
     */
    public static function getAll()
    {
        return self::$registry;
    }

    /**
     * Remove registry key.
     *
     * @param  string  $key
     * @return boolean
     */
    public static function remove($key)
    {
        if (self::has($key)) {
            unset(self::$registry[$key]);

            return true;
        }

        return false;
    }

    /**
     * Remove all registry keys.
     */
    public static function removeAll()
    {
        self::$registry = array();

        return;
    }
}
