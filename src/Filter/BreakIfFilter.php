<?php

namespace Butterfly\Component\Form\Filter;

use Butterfly\Component\Form\Validation\IValidator;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class BreakIfFilter
{
    const SOURCE_VALUE = '__value';
    const SOURCE_CONSTRAINT = '__constraint';

    /**
     * @var IValidator
     */
    protected $validator;

    /**
     * @var mixed
     */
    protected $source;

    /**
     * @param IValidator $validator
     * @param mixed $source
     */
    public function __construct(IValidator $validator, $source)
    {
        $this->validator = $validator;
        $this->source    = $source;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function check($value)
    {
        return $this->validator->check($value);
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }
}
