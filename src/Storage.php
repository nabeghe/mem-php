<?php namespace Nabeghe\Mem;

class Storage implements \ArrayAccess
{
    protected $count = 0;

    public function __construct(protected array $data = [])
    {
    }

    public function firstKey()
    {
        //reset($this->data);
        return key($this->data);
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }

    public function match($regex)
    {
        foreach ($this->data as $key => $value) {
            if (preg_match($regex, $key)) {
                return $key;
            }
        }

        return null;
    }

    public function matches($regex)
    {
        $matches = array_filter($this->data, function ($key) use ($regex) {
            return preg_match($regex, $key);
        }, ARRAY_FILTER_USE_KEY);

        return $matches ?: null;
    }

    public function add($key, $value)
    {
        $this->data[$key] = $value;
        $this->count++;
    }

    public function del($key, $isRegex = false)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
            return true;
        }

        return false;
    }

    public function delMatches($regex)
    {
        $success = false;

        foreach ($this->data as $key => $value) {
            if (preg_match($regex, $key)) {
                unset($this->data[$key]);
                $success = true;
            }
        }

        return $success;
    }

    public function delValue($value)
    {
        if (($key = array_search($value, $this->data)) !== false) {
            unset($this->data[$key]);
            $this->count--;
        }
    }

    public function count()
    {
        return $this->count;
    }

    public function length()
    {
        return $this->count;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
        $this->count = count($data);
    }

    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
        $this->count++;
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
        $this->count--;
    }
}