<?php

namespace Butterfly\Component\Form;

use Butterfly\Component\Form\Filter\BreakIfFilter;
use Butterfly\Component\Form\Filter\CallableTransformerAdapter;
use Butterfly\Component\Form\Filter\CallableValidatorAdapter;
use Butterfly\Component\Form\Filter\RestoreValueFilter;
use Butterfly\Component\Form\Filter\SaveValueFilter;
use Butterfly\Component\Form\Filter\ValidatorChainAdapter;
use Butterfly\Component\Form\Transform\ITransformer;
use Butterfly\Component\Form\Validation\IValidator;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ScalarConstraint implements IConstraint
{
    /**
     * @var array
     */
    protected $values = array();

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
     * @param IConstraint|null $parent
     * @return ScalarConstraint
     */
    public function setParent(IConstraint $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return ArrayConstraint|ListConstraint|null
     */
    public function end()
    {
        return $this->parent;
    }

    /**
     * @return ArrayConstraint|ListConstraint|null
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
     * @param IValidator $validator
     * @param mixed $source
     * @return $this
     */
    public function breakIf(IValidator $validator, $source = BreakIfFilter::SOURCE_VALUE)
    {
        $this->filters[] = new BreakIfFilter($validator, $source);

        return $this;
    }

    /**
     * @param mixed $label
     * @return ScalarConstraint
     */
    public function saveValue($label)
    {
        $this->filters[] = new SaveValueFilter($label);

        return $this;
    }

    /**
     * @param mixed $label
     * @return $this
     */
    public function restoreValue($label)
    {
        $this->filters[] = new RestoreValueFilter($label);

        return $this;
    }

    /**
     * @param mixed $value
     * @return ScalarConstraint
     */
    public function filter($value)
    {
        $this->setValue(IConstraint::VALUE_BEFORE, $value);

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
            } elseif ($filter instanceof SaveValueFilter) {
                $this->values[$filter->getLabel()] = $value;
            } elseif ($filter instanceof RestoreValueFilter) {
                $value = $this->getValue($filter->getLabel());
            } elseif ($filter instanceof BreakIfFilter) {
                $source = $filter->getSource();
                if ($source == BreakIfFilter::SOURCE_VALUE) {
                    $result = $filter->check($value);
                } elseif ($source == BreakIfFilter::SOURCE_CONSTRAINT) {
                    $result = $filter->check($this);
                } else {
                    $result = $filter->check($source);
                }

                if ($result) {
                    break;
                }
            }
        }

        $this->setValue(IConstraint::VALUE_AFTER, $value);

        return $this;
    }

    /**
     * @param mixed $label
     * @param mixed $value
     */
    protected function setValue($label, $value)
    {
        $this->values[$label] = $value;
    }

    /**
     * @return mixed
     */
    public function getOldValue()
    {
        return $this->getValue(IConstraint::VALUE_BEFORE);
    }

    /**
     * @param mixed $label
     * @return mixed
     */
    public function getValue($label = IConstraint::VALUE_AFTER)
    {
        if (!array_key_exists($label, $this->values)) {
            throw new \InvalidArgumentException(sprintf('Label %s is not found', $label));
        }

        return $this->values[$label];
    }

    /**
     * @param string $label
     * @return bool
     */
    public function hasValue($label)
    {
        return array_key_exists($label, $this->values);
    }

    /**
     * @return bool
     */
    public function isFiltered()
    {
        return $this->hasValue(IConstraint::VALUE_BEFORE);
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
    public function getStructuredErrorMessages()
    {
        return $this->errorMessages;
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

    /**
     * @return void
     */
    public function clean()
    {
        $this->values        = array();
        $this->errorMessages = array();
    }

    public function __clone()
    {
        $this->clean();
    }
}
