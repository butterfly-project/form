<?php

namespace Butterfly\Component\Form\Validation;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ArrayCount implements IValidator
{
    const EQUAL = '=';
    const NOT_EQUAL = '!=';
    const LESS = '<';
    const GREATER = '>';
    const LESS_OR_EQUAL = '<=';
    const GREATER_OR_EQUAL = '>=';

    /**
     * @var int
     */
    protected $expected;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @param int $expected
     * @param string $operator
     */
    public function __construct($expected, $operator = self::EQUAL)
    {
        $this->expected = (int)$expected;
        $this->operator = $operator;
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

        $count = count($value);

        switch ($this->operator) {
            case static::EQUAL:
                return $count == $this->expected;
            case static::NOT_EQUAL:
                return $count != $this->expected;
            case static::LESS:
                return $count < $this->expected;
            case static::GREATER:
                return $count > $this->expected;
            case static::LESS_OR_EQUAL:
                return $count <= $this->expected;
            case static::GREATER_OR_EQUAL:
                return $count >= $this->expected;
            default:
                throw new \InvalidArgumentException(sprintf('Operator %s is not found', $this->operator));
        }
    }
}
