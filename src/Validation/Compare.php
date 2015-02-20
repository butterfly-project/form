<?php

namespace Butterfly\Component\Form\Validation;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class Compare implements IValidator
{
    const EQUAL                 = '==';
    const IDENTICALLY           = '===';
    const NOT_EQUAL             = '!=';
    const NOT_EQUAL_ALTERNATIVE = '<>';
    const NOT_IDENTICALLY       = '!==';
    const LESS                  = '<';
    const GREATER               = '>';
    const LESS_OR_EQUAL         = '<=';
    const GREATER_OR_EQUAL      = '>=';

    /**
     * @var mixed
     */
    protected $expected;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @param string $operator
     * @param mixed $expected
     */
    public function __construct($expected, $operator = self::EQUAL)
    {
        $this->expected = $expected;
        $this->operator = $operator;
    }

    /**
     * @param mixed $value
     * @return bool
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function check($value)
    {
        switch ($this->operator) {
            case static::EQUAL:
                return $value == $this->expected;
            case static::IDENTICALLY:
                return $value === $this->expected;
            case static::NOT_EQUAL:
            case static::NOT_EQUAL_ALTERNATIVE:
                return $value != $this->expected;
            case static::NOT_IDENTICALLY:
                return $value !== $this->expected;
            case static::LESS:
                return $value < $this->expected;
            case static::GREATER:
                return $value > $this->expected;
            case static::LESS_OR_EQUAL:
                return $value <= $this->expected;
            case static::GREATER_OR_EQUAL:
                return $value >= $this->expected;
            default:
                throw new \InvalidArgumentException(sprintf('Operator %s is not found', $this->operator));
        }
    }
}
