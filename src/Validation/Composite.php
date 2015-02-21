<?php

namespace Butterfly\Component\Form\Validation;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class Composite implements IValidator
{
    const TYPE_AND = 'and';
    const TYPE_OR  = 'or';
    const TYPE_XOR = 'xor';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var IValidator[]
     */
    protected $validators = array();

    /**
     * @param string $type
     * @param IValidator[] $validators
     */
    public function __construct($type, array $validators = array())
    {
        $this->type = $type;

        foreach ($validators as $validator) {
            $this->addValidator($validator);
        }
    }

    /**
     * @param IValidator $validator
     * @return $this
     */
    public function addValidator(IValidator $validator)
    {
        $this->validators[] = $validator;

        return $this;
    }

    /**
     * @param mixed $value
     * @return bool
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function check($value)
    {
        if (empty($this->validators)) {
            return true;
        }

        $result = $this->validators[0]->check($value);

        for ($i = 1; array_key_exists($i, $this->validators); $i++) {
            $result = $this->runOperation($result, $this->validators[$i]->check($value));
        }

        return $result;
    }

    /**
     * @param bool $a
     * @param bool $b
     * @return bool
     * @throws \InvalidArgumentException if type is not found
     */
    protected function runOperation($a, $b)
    {
        switch ($this->type) {
            case static::TYPE_AND:
                return $a and $b;
            case static::TYPE_OR:
                return $a or $b;
            case static::TYPE_XOR:
                return $a xor $b;
            default:
                throw new \InvalidArgumentException(sprintf('Type %s is not found', $this->type));
        }
    }
}
