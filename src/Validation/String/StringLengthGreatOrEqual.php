<?php

namespace Butterfly\Component\Form\Validation\String;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class StringLengthGreatOrEqual extends AbstractStringLengthValidator
{
    /**
     * @param int $value
     * @return bool
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function check($value)
    {
        return $this->getValueLength($value) >= $this->length;
    }
}
