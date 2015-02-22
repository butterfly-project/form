<?php

namespace Butterfly\Component\Form\Validation;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class Type implements IValidator
{
    const TYPE_NULL     = 'null';
    const TYPE_BOOL     = 'bool';
    const TYPE_INT      = 'int';
    const TYPE_FLOAT    = 'float';
    const TYPE_STRING   = 'string';
    const TYPE_ARRAY    = 'array';
    const TYPE_OBJECT   = 'object';
    const TYPE_RESOURCE = 'resource';
    const TYPE_CALLABLE = 'callable';

    const SUBTYPE_NUMERIC = 'numeric';
    const SUBTYPE_SCALAR  = 'scalar';

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
     * @return bool
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function check($value)
    {
        switch ($this->type) {
            case static::TYPE_NULL:
                return is_null($value);
            case static::TYPE_BOOL:
                return is_bool($value);
            case static::TYPE_INT:
                return is_int($value);
            case static::TYPE_FLOAT:
                return is_float($value);
            case static::TYPE_STRING:
                return is_string($value);
            case static::TYPE_ARRAY:
                return is_array($value);
            case static::TYPE_RESOURCE:
                return is_resource($value);
            case static::TYPE_OBJECT:
                return is_object($value);
            case static::TYPE_CALLABLE:
                return is_callable($value);
            case static::SUBTYPE_NUMERIC:
                return is_numeric($value);
            case static::SUBTYPE_SCALAR:
                return is_scalar($value);
            default:
                throw new \InvalidArgumentException(sprintf('Type %s is not found', $this->type));
        }
    }
}
