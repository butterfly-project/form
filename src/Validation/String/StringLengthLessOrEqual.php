<?php

namespace Butterfly\Component\Form\Validation\String;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class StringLengthLessOrEqual extends StringLengthLess
{
    /**
     * @param int $value
     * @return bool
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function check($value)
    {
        return $this->getValueLength($value) <= $this->length;
    }
}
