<?php

namespace Butterfly\Component\Form;

interface IConstraint
{
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
     * @return mixed
     */
    public function getValue();

    /**
     * @return mixed
     */
    public function getOldValue();
}
