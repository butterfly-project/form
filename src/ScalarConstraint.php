<?php

namespace Butterfly\Component\Form;

use Butterfly\Component\Form\Adapter\CallableTransformerAdapter;
use Butterfly\Component\Form\Adapter\CallableValidatorAdapter;
use Butterfly\Component\Form\Adapter\ValidatorChainAdapter;
use Butterfly\Component\Transform\ITransformer;
use Butterfly\Component\Validation\IValidator;

class ScalarConstraint implements IConstraint
{
    /**
     * @var mixed
     */
    protected $oldValue;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var array
     */
    protected $filters = array();

    /**
     * @var array
     */
    protected $errorMessages = array();

    /**
     * @var ArrayConstraint|null
     */
    protected $parent;

    /**
     * @return ScalarConstraint
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param ArrayConstraint|null $parent
     * @return ScalarConstraint
     */
    public function setParent(ArrayConstraint $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return ArrayConstraint|null
     */
    public function end()
    {
        return $this->parent;
    }

    /**
     * @return ArrayConstraint|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param callable $validator
     * @param string $message
     * @param bool $isNegative
     * @param bool $isFatal
     * @return ScalarConstraint
     */
    public function addCallableValidator($validator, $message = '', $isNegative = false, $isFatal = false)
    {
        return $this->addValidator(new CallableValidatorAdapter($validator, $this), $message, $isNegative, $isFatal);
    }

    /**
     * @param callable $transformer
     * @return ScalarConstraint
     */
    public function addCallableTransformer($transformer)
    {
        return $this->addTransformer(new CallableTransformerAdapter($transformer, $this));
    }

    /**
     * @param IValidator $validator
     * @param string $message
     * @param bool $isNegative
     * @param bool $isFatal
     * @return ScalarConstraint
     */
    public function addValidator(IValidator $validator, $message = '', $isNegative = false, $isFatal = false)
    {
        $this->filters[] = new ValidatorChainAdapter($validator, $message, $isNegative, $isFatal);

        return $this;
    }

    /**
     * @param ITransformer $transformer
     * @return ScalarConstraint
     */
    public function addTransformer(ITransformer $transformer)
    {
        $this->filters[] = $transformer;

        return $this;
    }

    /**
     * @param mixed $value
     * @return ScalarConstraint
     */
    public function filter($value)
    {
        $this->setOldValue($value);

        foreach ($this->filters as $filter) {
            if ($filter instanceof ITransformer) {
                $value = $filter->transform($value);

            } elseif ($filter instanceof ValidatorChainAdapter) {
                if ($filter->check($value, $this)) {
                    continue;
                }

                $this->errorMessages[] = $filter->getMessage();

                if ($filter->isFatal()) {
                    break;
                }
            }
        }

        $this->setValue($value);

        return $this;
    }

    /**
     * @param mixed $oldValue
     */
    protected function setOldValue($oldValue)
    {
        $this->oldValue = $oldValue;
    }

    /**
     * @param mixed $value
     */
    protected function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getOldValue()
    {
        return $this->oldValue;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return empty($this->errorMessages);
    }

    /**
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * @return string|null
     */
    public function getFirstErrorMessage()
    {
        $message = reset($this->errorMessages);

        return (false === $message) ? null : $message;
    }
}
