<?php

namespace Butterfly\Component\Form\Validation;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ArrayHasKey implements IValidator
{
    /**
     * @var mixed
     */
    protected $key;

    /**
     * @param mixed $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @param mixed $value
     * @return bool
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function check($value)
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException(sprintf("Expected array, given: %s", var_export($value, true)));
        }

        return array_key_exists($this->key, $value);
    }
}
