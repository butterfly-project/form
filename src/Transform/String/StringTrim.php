<?php

namespace Butterfly\Component\Form\Transform\String;

use Butterfly\Component\Form\Transform\ITransformer;

class StringTrim implements ITransformer
{
    const TRIM_ALL      = 'all';
    const TRIM_LEFT     = 'left';
    const TRIM_RIGTH    = 'right';

    /**
     * @var string
     */
    protected $target;

    /**
     * @var string
     */
    protected $charlist;

    /**
     * @param string $target
     * @param string $charlist
     */
    public function __construct($target = self::TRIM_ALL, $charlist = " \t\n\r\0\x0B")
    {
        $this->target   = $target;
        $this->charlist = $charlist;
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

        switch ($this->target) {
            case self::TRIM_LEFT:
                return ltrim($value, $this->charlist);
            case self::TRIM_RIGTH:
                return rtrim($value, $this->charlist);
            default:
                return trim($value, $this->charlist);
        }
    }
}
