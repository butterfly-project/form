<?php

namespace Butterfly\Component\Form\Filter;

use Butterfly\Component\Form\Validation\IValidator;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ValidatorChainAdapter
{
    /**
     * @var IValidator
     */
    protected $validator;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var bool
     */
    protected $isNegative;

    /**
     * @var bool
     */
    protected $isFatal;

    /**
     * @param IValidator $validator
     * @param string $message
     * @param bool $isNegative
     * @param bool $isFatal
     */
    public function __construct(IValidator $validator, $message, $isNegative, $isFatal)
    {
        $this->validator  = $validator;
        $this->message    = $message;
        $this->isNegative = $isNegative;
        $this->isFatal    = $isFatal;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function check($value)
    {
        $result = $this->validator->check($value);

        return $this->isNegative ? !$result : $result;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return boolean
     */
    public function isFatal()
    {
        return $this->isFatal;
    }
}
