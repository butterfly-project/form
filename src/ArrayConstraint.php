<?php

namespace Butterfly\Component\Form;

use Traversable;

class ArrayConstraint implements IConstraint, \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @var IConstraint[]
     */
    protected $constraints = array();

    /**
     * @var array
     */
    protected $order = array();

    /**
     * @var ArrayConstraint|null
     */
    protected $parent;

    /**
     * @return ArrayConstraint
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param ArrayConstraint|null $parent
     * @return ArrayConstraint
     */
    public function setParent(ArrayConstraint $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return ArrayConstraint|null
     */
    public function end()
    {
        return $this->parent;
    }

    /**
     * @return ArrayConstraint|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $key
     * @return ScalarConstraint
     */
    public function addScalarConstraint($key)
    {
        $constraint = new ScalarConstraint();

        $this->addConstraint($key, $constraint);

        return $constraint;
    }

    /**
     * @param string $key
     * @return ArrayConstraint
     */
    public function addArrayConstraint($key)
    {
        $constraint = new ArrayConstraint();

        $this->addConstraint($key, $constraint);

        return $constraint;
    }

    /**
     * @param string $key
     * @param IConstraint $constraint
     * @return IConstraint
     */
    public function addConstraint($key, IConstraint $constraint)
    {
        $this->constraints[$key] = $constraint;

        $this->removeOrderKey($key);
        $this->addOrderKey($key);

        $constraint->setParent($this);

        return $constraint;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function removeConstraint($key)
    {
        unset($this->constraints[$key]);

        $this->removeOrderKey($key);

        return $this;
    }

    /**
     * @param string $key
     * @return ScalarConstraint|ArrayConstraint|null
     */
    public function get($key)
    {
        return isset($this->constraints[$key]) ? $this->constraints[$key] : null;
    }

    /**
     * @param string $key
     */
    protected function addOrderKey($key)
    {
        $this->order[] = $key;
    }

    /**
     * @param string $key
     */
    protected function removeOrderKey($key)
    {
        $index = array_search($key, $this->order);

        if (false !== $index) {
            unset($this->order[$index]);
        }
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function filter($value)
    {
        foreach ($this->order as $key) {
            $fieldValue = isset($value[$key]) ? $value[$key] : null;

            $this->constraints[$key]->filter($fieldValue);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getOldValue()
    {
        $values = array();

        foreach ($this->constraints as $key => $constraint) {
            $values[$key] = $constraint->getOldValue();
        }

        return $values;
    }

    /**
     * @return array
     */
    public function getValue()
    {
        $values = array();

        foreach ($this->constraints as $key => $constraint) {
            $values[$key] = $constraint->getValue();
        }

        return $values;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        foreach ($this->constraints as $constraint) {
            if (!$constraint->isValid()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function getErrorMessages()
    {
        $messages = array();

        foreach ($this->constraints as $constraint) {
            $messages = array_merge($messages, $constraint->getErrorMessages());
        }

        return $messages;
    }

    /**
     * @return string|null
     */
    public function getFirstErrorMessage()
    {
        foreach ($this->constraints as $constraint) {
            $message = $constraint->getFirstErrorMessage();

            if (null !== $message) {
                return $message;
            }
        }

        return null;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->constraints);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->constraints);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->constraints[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('Can not be set');
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw new \RuntimeException('Can not be unset');
    }
}
