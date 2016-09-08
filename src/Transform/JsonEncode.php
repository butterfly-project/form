<?php

namespace Butterfly\Component\Form\Transform;

use Butterfly\Component\Form\Transform\ITransformer;

class JsonEncode implements ITransformer
{
    /**
     * @var bool
     */
    protected $assoc;

    /**
     * @var int
     */
    protected $options;

    /**
     * @var int
     */
    protected $depth;

    /**
     * @param bool $assoc
     * @param int $options
     * @param int $depth
     */
    public function __construct($assoc = true, $options = 0, $depth = 512)
    {
        $this->assoc   = $assoc;
        $this->options = $options;
        $this->depth   = $depth;
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function transform($value)
    {
        return json_encode($value, $this->options, $this->depth);
    }
}
