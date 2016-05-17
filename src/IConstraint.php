<?php

namespace Butterfly\Component\Form;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
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
    public function isFiltered();

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
     * @param IConstraint $parent
     * @return void
     */
    public function setParent(IConstraint $parent);

    /**
     * @return ArrayConstraint
     */
    public function getParent();

    /**
     * @param mixed $label
     * @return mixed
     */
    public function getValue($label = IConstraint::VALUE_AFTER);

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
