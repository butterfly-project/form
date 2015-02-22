<?php

namespace Butterfly\Component\Form\Transform;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class Composite implements ITransformer
{
    /**
     * @var ITransformer[]
     */
    protected $transformers = array();

    /**
     * @param ITransformer[] $transformers
     */
    public function __construct(array $transformers = array())
    {
        foreach ($transformers as $transformer) {
            $this->addTransformer($transformer);
        }
    }

    /**
     * @param ITransformer $transformer
     * @return $this
     */
    public function addTransformer(ITransformer $transformer)
    {
        $this->transformers[] = $transformer;

        return $this;
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function transform($value)
    {
        foreach ($this->transformers as $transformer) {
            $value = $transformer->transform($value);
        }

        return $value;
    }
}
