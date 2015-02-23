<?php

namespace Butterfly\Component\Form;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class VariableIterator implements \Iterator
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @var mixed
     */
    protected $currentKey;

    /**
     * @param array $items
     */
    public function __construct(array $items = array())
    {
        $this->items = $items;
    }

    /**
     * @param mixed $value
     */
    public function addValue($value)
    {
        $this->items[] = $value;
    }

    /**
     * @param mixed $value
     * @throws \RuntimeException if deleted current key
     */
    public function removeValue($value)
    {
        if ($value === $this->current()) {
            throw new \RuntimeException('You can not delete the current key');
        }

        $index = array_search($value, $this->items);

        if (false !== $index) {
            unset($this->items[$index]);
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return array_key_exists($this->currentKey, $this->items)
            ? $this->items[$this->currentKey]
            : null;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $keys  = array_keys($this->items);
        $index = array_search($this->currentKey, $keys);

        $index++;

        $this->currentKey = array_key_exists($index, $keys) ? $keys[$index] : null;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->currentKey;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return array_key_exists($this->currentKey, $this->items);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $keys = array_keys($this->items);
        $this->currentKey = reset($keys);
    }
}
