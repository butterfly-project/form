<?php

namespace Butterfly\Component\Form\Validation;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ArrayCount implements IValidator
{
    /**
     * @var int
     */
    protected $count;

    /**
     * @param int $count
     */
    public function __construct($count)
    {
        $this->count = (int)$count;
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

        return count($value) == $this->count;
    }
}
