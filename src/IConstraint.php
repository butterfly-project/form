<?php

namespace Butterfly\Component\Form;

interface IConstraint
{
    const VALUE_BEFORE  = 'before';
    const VALUE_AFTER   = 'after';

    /**
     * @param mixed $value
     * @return mixed
     */
    public function filter($value);

    /**
     * @return bool
     */
    public function isValid();

    /**
     * @return array
     */
    public function getErrorMessages();

    /**
     * @return string|null
     */
    public function getFirstErrorMessage();

    /**
     * @param ArrayConstraint $parent
     * @return void
     */
    public function setParent(ArrayConstraint $parent);

    /**
     * @param mixed $label
     * @return mixed
     */
    public function getValue($label);

    /**
     * @return mixed
     */
    public function getOldValue();

    /**
     * @param mixed $label
     * @return bool
     */
    public function hasValue($label);

    /**
     * @return void
     */
    public function clean();
}
