<?php

namespace Butterfly\Component\Form\Transform;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ToType implements ITransformer
{
    const TYPE_BOOL   = 'bool';
    const TYPE_INT    = 'int';
    const TYPE_FLOAT  = 'float';
    const TYPE_STRING = 'string';
    const TYPE_ARRAY  = 'array';

    /**
     * @var string
     */
    protected $type;

    /**
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws \InvalidArgumentException if type is not found
     */
    public function transform($value)
    {
        switch ($this->type) {
            case static::TYPE_BOOL:
                return (bool)$value;
            case static::TYPE_INT:
                return (int)$value;
            case static::TYPE_FLOAT:
                return (float)$value;
            case static::TYPE_STRING:
                return (string)$value;
            case static::TYPE_ARRAY:
                return (array)$value;
            default:
                throw new \InvalidArgumentException(sprintf('Type %s is not found', $this->type));
        }
    }
}
