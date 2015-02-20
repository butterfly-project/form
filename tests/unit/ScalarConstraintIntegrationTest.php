<?php

namespace Butterfly\Component\Form\Tests;

use Butterfly\Component\Form\ArrayConstraint;
use Butterfly\Component\Form\IConstraint;
use Butterfly\Component\Form\ScalarConstraint;
use Butterfly\Component\Form\Transform\String\StringMaxLength;
use Butterfly\Component\Form\Transform\String\StringTrim;
use Butterfly\Component\Form\Validation\Compare;
use Butterfly\Component\Form\Validation\IsNull;
use Butterfly\Component\Form\Validation\StringLength;

class ScalarConstraintIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testTransformer()
    {
        $constraint = ScalarConstraint::create()
            ->addTransformer(new StringTrim());

        $constraint->filter(' abc ');

        $this->assertEquals(' abc ', $constraint->getOldValue());
        $this->assertEquals(' abc ', $constraint->getValue(IConstraint::VALUE_BEFORE));

        $this->assertEquals('abc', $constraint->getValue());
        $this->assertEquals('abc', $constraint->getValue(IConstraint::VALUE_AFTER));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetValueIfIncorrectLabel()
    {
        $constraint = ScalarConstraint::create()
            ->addTransformer(new StringTrim());


        $constraint->filter(' abc ');

        $constraint->getValue('undefined');
    }

    public function testCallableTransformer()
    {
        $constraint = ScalarConstraint::create()
            ->addCallableTransformer(function($value) {
                return strlen($value);
            });

        $constraint->filter('abc');

        $this->assertEquals(3, $constraint->getValue());
    }

    public function testValidator()
    {
        $constraint = ScalarConstraint::create()
            ->addValidator(new IsNull(), 'incorrect value');

        $constraint->filter(null);

        $this->assertTrue($constraint->isValid());
        $this->assertNull($constraint->getFirstErrorMessage());
    }

    public function testValidatorIfNegative()
    {
        $constraint = ScalarConstraint::create()
            ->addValidator(new IsNull(), 'incorrect value', true);

        $constraint->filter(null);

        $this->assertFalse($constraint->isValid());
        $this->assertEquals('incorrect value', $constraint->getFirstErrorMessage());
    }

    public function testMoreValidators()
    {
        $constraint = ScalarConstraint::create()
            ->addValidator(new StringLength(3, StringLength::GREATER), 'incorrect value')
            ->addValidator(new StringLength(5, StringLength::GREATER), 'incorrect value');

        $constraint->filter('ab');

        $this->assertFalse($constraint->isValid());
        $this->assertCount(2, $constraint->getErrorMessages());
    }

    public function testFatalValidators()
    {
        $constraint = ScalarConstraint::create()
            ->addValidator(new StringLength(3, StringLength::GREATER), 'incorrect value', false, true)
            ->addValidator(new StringLength(5, StringLength::GREATER), 'incorrect value');

        $constraint->filter('ab');

        $this->assertFalse($constraint->isValid());
        $this->assertCount(1, $constraint->getErrorMessages());
    }

    public function testCallableValidator()
    {
        $constraint = ScalarConstraint::create()
            ->addCallableValidator(function($value) {
                return 'abc' == $value;
            }, 'incorrect value');

        $constraint->filter('abc');

        $this->assertTrue($constraint->isValid());
        $this->assertNull($constraint->getFirstErrorMessage());
    }

    public function testParent()
    {
        $parent = $this->getArrayConstraint();

        $constraint = ScalarConstraint::create()->setParent($parent);

        $this->assertEquals($parent, $constraint->end());
        $this->assertEquals($parent, $constraint->getParent());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ArrayConstraint
     */
    protected function getArrayConstraint()
    {
        return $this->getMock('\Butterfly\Component\Form\ArrayConstraint');
    }

    public function testOrder()
    {
        $constraint = ScalarConstraint::create()
            ->addTransformer(new StringTrim())
            ->addValidator(new StringLength(3, StringLength::LESS_OR_EQUAL))
            ->addValidator(new StringLength(3, StringLength::GREATER_OR_EQUAL))
            ->addTransformer(new StringMaxLength(2))
            ->addValidator(new StringLength(2, StringLength::LESS_OR_EQUAL))
            ->addValidator(new StringLength(2, StringLength::GREATER_OR_EQUAL))
        ;

        $constraint->filter(' abc ');

        $this->assertTrue($constraint->isValid());
    }

    public function testSaveValue()
    {
        $constraint = ScalarConstraint::create()
            ->addTransformer(new StringTrim())
            ->saveValue('label1')
            ->addTransformer(new StringMaxLength(2))
            ->saveValue('label2')
            ->addTransformer(new StringMaxLength(1))
            ->saveValue('label3')
        ;

        $constraint->filter(' abc ');

        $this->assertEquals(' abc ', $constraint->getValue(IConstraint::VALUE_BEFORE));
        $this->assertEquals('a', $constraint->getValue(IConstraint::VALUE_AFTER));
        $this->assertEquals('a', $constraint->getValue());

        $this->assertEquals('abc', $constraint->getValue('label1'));
        $this->assertEquals('ab', $constraint->getValue('label2'));
        $this->assertEquals('a', $constraint->getValue('label3'));
    }

    public function testRestoreValue()
    {
        $constraint = ScalarConstraint::create()
            ->addTransformer(new StringTrim())
            ->addValidator(new Compare('abc'))
            ->saveValue('label1')

            ->addTransformer(new StringMaxLength(1))
            ->addValidator(new Compare('a'))
            ->saveValue('label2')
            ->restoreValue('label1')
        ;

        $constraint->filter(' abc ');

        $this->assertTrue($constraint->isValid());

        $this->assertEquals(' abc ', $constraint->getValue(IConstraint::VALUE_BEFORE));
        $this->assertEquals('abc', $constraint->getValue(IConstraint::VALUE_AFTER));
        $this->assertEquals('abc', $constraint->getValue());

        $this->assertEquals('abc', $constraint->getValue('label1'));
        $this->assertEquals('a', $constraint->getValue('label2'));
    }
}
