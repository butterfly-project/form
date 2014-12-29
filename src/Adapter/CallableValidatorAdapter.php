<?php

namespace Butterfly\Component\Form\Adapter;

use Butterfly\Component\Form\IConstraint;
use Butterfly\Component\Validation\IValidator;

class CallableValidatorAdapter implements IValidator
{
    /**
     * @var callable
     */
    protected $validator;

    /**
     * @var IConstraint
     */
    protected $constraint;

    /**
     * @param callable $validator
     * @param IConstraint $constraint
     */
    public function __construct($validator, IConstraint $constraint)
    {
        $this->validator  = $validator;
        $this->constraint = $constraint;
    }

    /**
     * @param mixed $value
     * @return bool
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function check($value)
    {
        return call_user_func($this->validator, $value, $this->constraint);
    }
}
