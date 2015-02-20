<?php

namespace Butterfly\Component\Form\Transform;

interface ITransformer
{
    /**
     * @param mixed $value
     * @return mixed
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function transform($value);
}
