<?php namespace Nabeghe\Mem;

/**
 * Static real-time caching system.
 */
class Mem implements MemInterface
{
    public const DEFAULT_CONFIG = [
        'length_limit' => -1,
    ];

    /**
     * @var Storage[]
     */
    protected static array $storages = [];

    protected static array $configs = [];

    public static function config($group = 'default', $config = false)
    {
        if ($config === false) {
            return isset(static::$configs[$group]) ? static::$configs[$group] : null;
        }

        if ($config === null) {
            unset(static::$configs[$group]);
        } else {
            static::$configs[$group] = $config;
        }

        return null;
    }

    public static function configProp($name, $group = 'default')
    {
        return isset(static::$configs[$group][$name])
            ? static::$configs[$group][$name]
            : (isset(static::DEFAULT_CONFIG[$name]) ? static::DEFAULT_CONFIG[$name] : null);
    }

    public static function has($key, $group = 'default')
    {
        return static::hasGroup($group) && isset(static::$storages[$group][$key]);
    }

    public static function match($regex, $group = 'default')
    {
        if (!static::hasGroup($group)) {
            return null;
        }

        return static::$storages[$group]->match($regex);
    }

    public static function matches($regex, $group = 'default')
    {
        if (!static::hasGroup($group)) {
            return null;
        }

        return static::$storages[$group]->matches($regex);
    }

    public static function hasGroup($group = 'default')
    {
        return isset(static::$storages[$group]);
    }

    public static function get($key, $group = 'default', $default = null)
    {
        return static::has($key, $group) ? static::$storages[$group][$key] : $default;
    }

    public static function set($key, $value, $group = 'default')
    {
        if (!static::hasGroup($group)) {
            static::$storages[$group] = new Storage();
        }

        static::$storages[$group][$key] = $value;

        $length_limit = static::configProp('length_limit', $group);
        if ($length_limit > 0 && static::$storages[$group]->count() > $length_limit) {
            static::$storages[$group]->del(static::$storages[$group]->firstKey());
        }
    }

    public static function del($key, $group = 'default')
    {
        if (static::has($key, $group)) {
            unset(static::$storages[$group][$key]);
            return true;
        }

        return false;
    }

    public static function delMatches($regex, $group = 'default')
    {
        return static::hasGroup($group) && static::$storages[$group]->delMatches($regex);
    }

    public static function all()
    {
        return static::$storages;
    }

    public static function group($group = 'default')
    {
        return static::hasGroup($group) ? static::$storages[$group] : null;
    }

    public static function groupsCount()
    {
        return count(array_keys(static::$storages));
    }

    public static function drop($group = 'default')
    {
        if (static::hasGroup($group)) {
            unset(static::$storages[$group]);
            return true;
        }

        return false;
    }

    public static function reset()
    {
        if (static::groupsCount()) {
            static::$storages = [];
            return true;
        }

        return false;
    }
}