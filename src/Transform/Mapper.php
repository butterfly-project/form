<?php

namespace Butterfly\Component\Form\Transform;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class Mapper implements ITransformer
{
    /**
     * @var array
     */
    protected $map = array();

    /**
     * @var mixed
     */
    protected $default;

    /**
     * @param array $map
     * @param mixed $default
     */
    public function __construct(array $map, $default = null)
    {
        $this->map     = $map;
        $this->default = $default;
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function transform($value)
    {
        return array_key_exists($value, $this->map)
            ? $this->map[$value]
            : $this->default;
    }
}
