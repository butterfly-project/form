<?php

namespace Butterfly\Component\Form\Transform\String;

use Butterfly\Component\Form\Transform\ITransformer;

class StringMaxLength implements ITransformer
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
     * @return string
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function transform($value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException(sprintf('Value type is not string'));
        }

        return mb_substr($value, 0, $this->length, $this->encoding);
    }
}
