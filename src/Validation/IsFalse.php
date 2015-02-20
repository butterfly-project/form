<?php

namespace Butterfly\Component\Form\Validation;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class IsFalse implements IValidator
{
    /**
     * @param mixed $value
     * @return bool
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function check($value)
    {
        if (!is_bool($value)) {
            throw new \InvalidArgumentException('Value type is not boolean');
        }

        return false === $value;
    }
}
