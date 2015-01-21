<?php

namespace Butterfly\Component\Form;

class ConstraintModifier implements IFilter
{
    /**
     * @var callable
     */
    protected $modifier;

    /**
     * @param callable $modifier
     */
    public function __construct($modifier)
    {
        $this->modifier = $modifier;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function filter($value)
    {
        call_user_func($this->modifier, $value);
    }
}
