<?php

namespace Butterfly\Component\Form\Validation;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
interface IValidator
{
    /**
     * @param mixed $value
     * @return bool
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function check($value);
}
