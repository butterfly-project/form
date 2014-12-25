<?php

namespace Butterfly\Component\Form\Adapter;

use Butterfly\Component\Transform\ITransformer;

class CallableTransformerAdapter implements ITransformer
{
    /**
     * @var callable
     */
    protected $transformer;

    /**
     * @param callable $transformer
     */
    public function __construct($transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function transform($value)
    {
        return call_user_func($this->transformer, $value);
    }
}
