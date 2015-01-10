<?php

namespace Butterfly\Component\Form;

class SyntheticConstraint extends ScalarConstraint
{
    /**
     * @param mixed $value
     * @return ScalarConstraint
     */
    public function filter($value)
    {
        parent::filter($value);

        $this->setValue(IConstraint::VALUE_BEFORE, null);

        return $this;
    }
}
