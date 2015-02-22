<?php

namespace Butterfly\Component\Form\Transform;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class Trim implements ITransformer
{
    const TRIM_ALL      = 'all';
    const TRIM_LEFT     = 'left';
    const TRIM_RIGTH    = 'right';

    /**
     * @var string
     */
    protected $mode;

    /**
     * @var string
     */
    protected $charlist;

    /**
     * @param string $mode
     * @param string $charlist
     */
    public function __construct($mode = self::TRIM_ALL, $charlist = " \t\n\r\0\x0B")
    {
        $this->mode     = $mode;
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

        switch ($this->mode) {
            case self::TRIM_LEFT:
                return ltrim($value, $this->charlist);
            case self::TRIM_RIGTH:
                return rtrim($value, $this->charlist);
            default:
                return trim($value, $this->charlist);
        }
    }
}
