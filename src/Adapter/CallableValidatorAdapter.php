<?php

namespace Butterfly\Component\Form\Adapter;

use Butterfly\Component\Validation\IValidator;

class CallableValidatorAdapter implements IValidator
{
    /**
     * @var callable
     */
    protected $validator;

    /**
     * @param callable $validator
     */
    public function __construct($validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param mixed $value
     * @return bool
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function check($value)
    {
        return call_user_func($this->validator, $value);
    }
}
