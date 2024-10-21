<?php namespace Nabeghe\Mem;

/**
 * Static real-time caching system.
 */
class Mem implements MemInterface
{
    /**
     * Everything is stored here
     *
     * @var array
     */
    protected static array $storage = [];

    public static function has($key, $group = 'default')
    {
        return static::hasGroup($group) && isset(static::$storage[$group][$key]);
    }

    public static function hasGroup($group)
    {
        return isset(static::$storage[$group]);
    }

    public static function get($key, $group = 'default', $default = null)
    {
        return static::has($key, $group) ? static::$storage[$group][$key] : $default;
    }

    public static function set($key, $value, $group = 'default')
    {
        if (!static::hasGroup($group)) {
            static::$storage[$group] = [];
        }
        static::$storage[$group][$key] = $value;
    }

    public static function del($key, $group = 'default')
    {
        if (static::has($key, $group)) {
            unset(static::$storage[$group][$key]);
            return true;
        }

        return false;
    }

    public static function all()
    {
        return static::$storage;
    }

    public static function group($group = 'default')
    {
        return static::hasGroup($group) ? static::$storage[$group] : null;
    }

    public static function groupsCount()
    {
        return count(array_keys(static::$storage));
    }

    public static function drop($group = 'default')
    {
        if (static::hasGroup($group)) {
            unset(static::$storage[$group]);
            return true;
        }

        return false;
    }

    public static function reset()
    {
        if (static::groupsCount()) {
            static::$storage = [];
            return true;
        }

        return false;
    }
}