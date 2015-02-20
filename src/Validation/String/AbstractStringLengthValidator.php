<?php

namespace Butterfly\Component\Form\Validation\String;

use Butterfly\Component\Form\Validation\IValidator;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
abstract class AbstractStringLengthValidator implements IValidator
{
    /**
     * @var int
     */
    protected $length;

    /**
     * @var string|null
     */
    protected $encoding;

    /**
     * @param int $length
     * @param string|null $encoding
     */
    public function __construct($length, $encoding = null)
    {
        $this->length   = $length;
        $this->encoding = null === $encoding ? mb_internal_encoding() : $encoding;
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
