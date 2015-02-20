<?php

namespace Butterfly\Component\Form\Transform\Type;

use Butterfly\Component\Form\Transform\ITransformer;

class ToString implements ITransformer
{
    /**
     * @param mixed $value
     * @return string
     */
    public function transform($value)
    {
        return (string)$value;
    }
}
