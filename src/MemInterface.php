<?php namespace Nabeghe\Mem;

interface MemInterface
{
    /**
     * Checks if a key exists in a cache group.
     *
     * @param  string  $key
     * @param  string  $group
     * @return bool
     */
    public static function has($key, $group = 'default');

    /**
     * Returns the first key of an item that matches the regex.
     *
     * @param  string  $regex
     * @param  string  $group
     * @return ?string
     */
    public static function match($regex, $group = 'default');

    /**
     * Returns items whose keys match the regex.
     *
     * @param  string  $regex
     * @param  string  $group
     * @return ?array
     */
    public static function matches($regex, $group = 'default');

    /**
     * Checks if a group exists.
     *
     * @param  string  $group
     * @return bool
     */
    public static function hasGroup($group);

    /**
     * Returns the value of a key from a group.
     *
     * @param  string  $key
     * @param  string  $group
     * @param  string  $default
     * @return mixed
     */
    public static function get($key, $group = 'default', $default = null);

    /**
     * Changes the value of a key in a group.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  string  $group
     * @return void
     */
    public static function set($key, $value, $group = 'default');

    /**
     * Deletes a key from a group.
     *
     * @param  string  $key
     * @param  string  $group
     * @return bool
     */
    public static function del($key, $group = 'default');

    /**
     * Deletes items based on key matching with regex.
     *
     * @param  string  $regex
     * @param  string  $group
     * @return bool
     */
    public static function delMatches($regex, $group = 'default');

    /**
     * Returns all storages (groups) and their keys.
     *
     * @return Storage[]
     */
    public static function all();

    /**
     * Returns all keys and values of a group.
     *
     * @param  string  $group
     * @return mixed|null
     */
    public static function group($group = 'default');

    /**
     * Returns the number of existing groups.
     *
     * @return int
     */
    public static function groupsCount();

    /**
     * Clears the entire group.
     *
     * @param $group
     * @return bool
     */
    public static function drop($group = 'default');

    /**
     * Clears the entire cache.
     *
     * @return bool
     */
    public static function reset();
}