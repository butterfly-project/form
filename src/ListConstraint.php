<?php

namespace Butterfly\Component\Form;

use Traversable;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ListConstraint implements IConstraint, \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @var IConstraint
     */
    protected $constraintPrototype;

    /**
     * @var IConstraint[]
     */
    protected $constraints = array();

    /**
     * @var ArrayConstraint|ListConstraint|null
     */
    protected $parent;

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @return ScalarConstraint
     */
    public function declareAsScalar()
    {
        return $this->declareConstraint(new ScalarConstraint());
    }

    /**
     * @return ArrayConstraint
     */
    public function declareAsArray()
    {
        return $this->declareConstraint(new ArrayConstraint());
    }

    /**
     * @param IConstraint $constraint
     * @return IConstraint
     */
    public function declareConstraint(IConstraint $constraint)
    {
        $constraint->setParent($this);

        $this->constraintPrototype = $constraint;

        return $constraint;
    }

    /**
     * @param mixed $value
     * @return ListConstraint
     */
    public function filter($value)
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException(sprintf("Incorrect value type. Expected array. Given: %s", var_export($value, true)));
        }

        foreach ($value as $key => $item) {
            $this->constraints[$key] = clone $this->constraintPrototype;

            $this->constraints[$key]->filter($item);
        }

        return $this;
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
    public function getStructuredErrorMessages()
    {
        return array();
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
     * @param IConstraint $parent
     * @return void
     */
    public function setParent(IConstraint $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return ArrayConstraint|ListConstraint|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return ArrayConstraint|ListConstraint|null
     */
    public function end()
    {
        return $this->parent;
    }

    /**
     * @return array
     */
    public function getOldValue()
    {
        return $this->getValue(IConstraint::VALUE_BEFORE);
    }

    /**
     * @param mixed $label
     * @return array
     */
    public function getValue($label = IConstraint::VALUE_AFTER)
    {
        $values = array();

        foreach ($this->constraints as $key => $constraint) {
            $values[$key] = $constraint->getValue($label);
        }

        return $values;
    }

    /**
     * @param mixed $label
     * @return bool
     */
    public function hasValue($label)
    {
        foreach ($this->constraints as $constraint) {
            if (!$constraint->hasValue($label)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return void
     */
    public function clean()
    {
        $this->constraints = array();
    }

    /**
     * @param string|int $key
     * @return IConstraint|null
     */
    public function get($key)
    {
        return isset($this->constraints[$key]) ? $this->constraints[$key] : null;
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
        return isset($this->constraints[$offset]) ? $this->constraints[$offset] : null;
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

    public function __clone()
    {
        $this->constraints         = array();
        $this->constraintPrototype = clone $this->constraintPrototype;
    }
}
