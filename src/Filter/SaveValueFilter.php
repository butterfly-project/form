<?php

namespace Butterfly\Component\Form\Filter;

class SaveValueFilter
{
    /**
     * @var mixed
     */
    protected $label;

    /**
     * @param mixed $label
     */
    public function __construct($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }
}
