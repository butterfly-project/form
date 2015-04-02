<?php

namespace Butterfly\Component\Form\Validation;

class Email implements IValidator
{
    /**
     * @param mixed $value
     * @return bool
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function check($value)
    {
        if (!is_string($value)) {
            return false;
        }

        return false !== filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
