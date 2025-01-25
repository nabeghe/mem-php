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

    public function add($key, $value)
    {
        $this->data[$key] = $value;
        $this->count++;
    }

    public function del($key)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        }
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