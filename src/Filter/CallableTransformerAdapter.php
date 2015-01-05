<?php

namespace Butterfly\Component\Form\Filter;

use Butterfly\Component\Form\IConstraint;
use Butterfly\Component\Transform\ITransformer;

class CallableTransformerAdapter implements ITransformer
{
    /**
     * @var callable
     */
    protected $transformer;

    /**
     * @var IConstraint
     */
    protected $constraint;

    /**
     * @param callable $transformer
     * @param IConstraint $constraint
     */
    public function __construct($transformer, IConstraint $constraint)
    {
        $this->transformer = $transformer;
        $this->constraint  = $constraint;
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function transform($value)
    {
        return call_user_func($this->transformer, $value, $this->constraint);
    }
}
