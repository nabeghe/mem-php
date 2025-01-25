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
     * Everything is stored here
     * @var Storage[]
     */
    protected static array $storage = [];

    /**
     * @var array
     */
    protected static array $configs = [];

    /**
     * @param  string  $group
     * @param  array|null|false  $config
     * @return mixed|void|null
     */
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
    }

    public static function configProp($name, $group = 'default')
    {
        return isset(static::$configs[$group][$name])
            ? static::$configs[$group][$name]
            : (isset(static::DEFAULT_CONFIG[$name]) ? static::DEFAULT_CONFIG[$name] : null);
    }


    /**
     * @param  string  $key
     * @param  string  $group
     * @return bool
     */
    public static function has($key, $group = 'default')
    {
        return static::hasGroup($group) && isset(static::$storage[$group][$key]);
    }

    /**
     * @param  string  $group
     * @return bool
     */
    public static function hasGroup($group = 'default')
    {
        return isset(static::$storage[$group]);
    }

    /**
     * @param  string  $key
     * @param  string  $group
     * @param  mixed  $default
     * @return mixed|null
     */
    public static function get($key, $group = 'default', $default = null)
    {
        return static::has($key, $group) ? static::$storage[$group][$key] : $default;
    }

    /**
     * @param  string  $key
     * @param  mixed  $value
     * @param  string  $group
     * @return void
     */
    public static function set($key, $value, $group = 'default')
    {
        if (!static::hasGroup($group)) {
            static::$storage[$group] = new Storage();
        }

        static::$storage[$group][$key] = $value;

        $length_limit = static::configProp('length_limit', $group);
        if ($length_limit > 0 && static::$storage[$group]->count() > $length_limit) {
            static::$storage[$group]->del(static::$storage[$group]->firstKey());
        }
    }

    /**
     * @param  string  $key
     * @param  string  $group
     * @return bool
     */
    public static function del($key, $group = 'default')
    {
        if (static::has($key, $group)) {
            unset(static::$storage[$group][$key]);
            return true;
        }

        return false;
    }

    /**
     * @return Storage[]
     */
    public static function all()
    {
        return static::$storage;
    }

    /**
     * @param  string  $group
     * @return Storage|null
     */
    public static function group($group = 'default')
    {
        return static::hasGroup($group) ? static::$storage[$group] : null;
    }

    /**
     * @return int
     */
    public static function groupsCount()
    {
        return count(array_keys(static::$storage));
    }

    /**
     * @param $group
     * @return bool
     */
    public static function drop($group = 'default')
    {
        if (static::hasGroup($group)) {
            unset(static::$storage[$group]);
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public static function reset()
    {
        if (static::groupsCount()) {
            static::$storage = [];
            return true;
        }

        return false;
    }
}