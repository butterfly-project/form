<?php

namespace Butterfly\Component\Form\Transform\Type;

use Butterfly\Component\Form\Transform\ITransformer;

class ToInt implements ITransformer
{
    /**
     * @param mixed $value
     * @return int
     */
    public function transform($value)
    {
        return (int)$value;
    }
}
