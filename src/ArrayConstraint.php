<?php

namespace Butterfly\Component\Form;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ArrayConstraint implements IConstraint, \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @var IConstraint[]
     */
    protected $constraints = array();

    /**
     * @var VariableIterator
     */
    protected $iterator;

    /**
     * @var ArrayConstraint|ListConstraint|null
     */
    protected $parent;

    /**
     * @var bool
     */
    protected $isFiltered = false;

    /**
     * @return ArrayConstraint
     */
    public static function create()
    {
        return new static();
    }

    public function __construct()
    {
        $this->iterator = new VariableIterator();
    }

    /**
     * @param IConstraint|null $parent
     * @return ArrayConstraint
     */
    public function setParent(IConstraint $parent)
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
     * @return ListConstraint
     */
    public function addListConstraint($key)
    {
        $constraint = new ListConstraint();

        $this->addConstraint($key, $constraint);

        return $constraint;
    }

    /**
     * @param string $key
     * @return SyntheticConstraint
     */
    public function addSyntheticConstraint($key)
    {
        $constraint = new SyntheticConstraint();

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

        $this->iterator->removeValue($key);
        $this->iterator->addValue($key);

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

        $this->iterator->removeValue($key);

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
     * @param mixed $value
     * @return $this
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function filter($value)
    {
        foreach ($this->iterator as $key) {
            $constraint = $this->constraints[$key];

            if ($constraint instanceof SyntheticConstraint) {
                $fieldValue = $this;
            } else {
                $fieldValue = $this->getKeyValue($value, $key);

                if ($constraint instanceof ListConstraint && null === $fieldValue) {
                    $fieldValue = array();
                }
            }

            $constraint->filter($fieldValue);
        }

        $this->isFiltered = true;

        return $this;
    }

    /**
     * @param mixed $obj
     * @param string $key
     * @return mixed|null
     */
    protected function getKeyValue($obj, $key)
    {
        if (is_array($obj)) {
            return $this->getArrayValue($obj, $key);
        }

        if ($obj instanceof \ArrayAccess) {
            return $obj->offsetExists($key) ? $obj->offsetGet($key) : null;
        }

        if (is_object($obj)) {
            return $this->getObjectValue($obj, $key);
        }

        throw new \InvalidArgumentException(sprintf(
            "Incrorrect value type. Expected array or object value. Given: %s",
            var_export($obj, true)
        ));
    }

    /**
     * @param array $value
     * @param string|int $key
     * @return mixed|null
     */
    protected function getArrayValue($value, $key)
    {
        return array_key_exists($key, $value) ? $value[$key] : null;
    }

    /**
     * @param object $value
     * @param string $key
     * @return mixed|null
     */
    protected function getObjectValue($value, $key)
    {
        $reflectionObject = new \ReflectionClass($value);

        if ($reflectionObject->hasProperty($key)) {
            $reflectionProperty = $reflectionObject->getProperty($key);

            if ($reflectionProperty->isPublic()) {
                return $reflectionProperty->getValue($value);
            }
        }

        $getterMethodName = 'get' . ucfirst($key);

        if ($reflectionObject->hasMethod($getterMethodName)) {
            $reflectionMethod = $reflectionObject->getMethod($getterMethodName);

            if ($reflectionMethod->isPublic()) {
                return call_user_func(array($value, $getterMethodName));
            }
        }

        if ($reflectionObject->hasMethod('__get')) {
            $hasMagicIsset = $reflectionObject->hasMethod('__isset');

            if (!$hasMagicIsset || ($hasMagicIsset && isset($value->$key))) {
                return call_user_func(array($value, '__get'), $key);
            }
        }

        return null;
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
     * @return bool
     */
    public function isFiltered()
    {
        return $this->isFiltered;
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
        $messages = array();

        foreach ($this->constraints as $key => $constraint) {
            $messages[$key] = $constraint->getStructuredErrorMessages();
        }

        return $messages;
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
     * @return void
     */
    public function clean()
    {
        $this->isFiltered = false;

        foreach ($this->constraints as $constraint) {
            $constraint->clean();
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return \Traversable An instance of an object implementing <b>Iterator</b> or
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

    public function __clone()
    {
        $constraints = array();

        foreach ($this->constraints as $key => $constraint) {
            $constraints[$key] = clone $constraint;
        }

        $this->constraints = $constraints;
        $this->isFiltered  = false;

        $this->iterator = clone $this->iterator;
    }
}
