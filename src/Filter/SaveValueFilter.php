<?php

namespace Butterfly\Component\Form\Filter;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
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
