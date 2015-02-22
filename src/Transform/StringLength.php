<?php

namespace Butterfly\Component\Form\Transform;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class StringLength implements ITransformer
{
    /**
     * @var int
     */
    protected $maxLength;

    /**
     * @var string|null
     */
    protected $encoding;

    /**
     * @param int $maxLength
     * @param string|null $encoding
     */
    public function __construct($maxLength, $encoding = null)
    {
        $this->maxLength = $maxLength;
        $this->encoding  = null === $encoding ? mb_internal_encoding() : $encoding;
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

        return mb_substr($value, 0, $this->maxLength, $this->encoding);
    }
}
