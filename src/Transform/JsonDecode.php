<?php

namespace Butterfly\Component\Form\Transform;

use Butterfly\Component\Form\Transform\ITransformer;

class JsonDecode implements ITransformer
{
    /**
     * @var bool
     */
    protected $assoc;

    /**
     * @var
     */
    protected $depth;

    /**
     * @var
     */
    protected $options;

    /**
     * @param bool $assoc
     * @param int $depth
     * @param int $options
     */
    public function __construct($assoc = true, $depth = 512, $options = 0)
    {
        $this->assoc   = $assoc;
        $this->depth   = $depth;
        $this->options = $options;
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function transform($value)
    {
        return json_decode($value, $this->assoc, $this->depth, $this->options);
    }
}
