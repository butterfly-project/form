<?php

namespace Butterfly\Component\Form\Transform;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ToDateTime implements ITransformer
{
    /**
     * @var string
     */
    protected $format;

    public function __construct($format)
    {
        $this->format = $format;
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function transform($value)
    {
        return \DateTime::createFromFormat($this->format, $value);
    }
}
