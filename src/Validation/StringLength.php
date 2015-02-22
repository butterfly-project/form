<?php

namespace Butterfly\Component\Form\Validation;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class StringLength implements IValidator
{
    const EQUAL                 = '==';
    const NOT_EQUAL             = '!=';
    const LESS                  = '<';
    const GREATER               = '>';
    const LESS_OR_EQUAL         = '<=';
    const GREATER_OR_EQUAL      = '>=';

    /**
     * @var int
     */
    protected $length;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @var string|null
     */
    protected $encoding;

    /**
     * @param int $length
     * @param string $operator
     * @param string|null $encoding
     */
    public function __construct($length, $operator, $encoding = null)
    {
        $this->length   = $length;
        $this->operator = $operator;
        $this->encoding = null === $encoding ? mb_internal_encoding() : $encoding;
    }


    /**
     * @param mixed $value
     * @return bool
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function check($value)
    {
        $valueLength = $this->getValueLength($value);

        switch ($this->operator) {
            case static::EQUAL:
                return $valueLength == $this->length;
            case static::NOT_EQUAL:
                return $valueLength != $this->length;
            case static::LESS:
                return $valueLength < $this->length;
            case static::LESS_OR_EQUAL:
                return $valueLength <= $this->length;
            case static::GREATER:
                return $valueLength > $this->length;
            case static::GREATER_OR_EQUAL:
                return $valueLength >= $this->length;
            default:
                throw new \InvalidArgumentException(sprintf('Operator %s is not found', $this->operator));
        }
    }

    /**
     * @param string $value
     * @return int
     * @throws \InvalidArgumentException if incorrect value type
     */
    protected function getValueLength($value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException('Value type is not string');
        }

        return mb_strlen($value, $this->encoding);
    }
}
