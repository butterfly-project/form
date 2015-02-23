<?php

namespace Butterfly\Component\Form;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
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
