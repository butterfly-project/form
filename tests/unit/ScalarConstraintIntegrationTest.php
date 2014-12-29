<?php

namespace Butterfly\Component\Form\Tests;

use Butterfly\Component\Form\ArrayConstraint;
use Butterfly\Component\Form\ScalarConstraint;
use Butterfly\Component\Transform\String\StringMaxLength;
use Butterfly\Component\Transform\String\StringTrim;
use Butterfly\Component\Validation\IsNull;
use Butterfly\Component\Validation\String\StringLengthGreat;
use Butterfly\Component\Validation\String\StringLengthGreatOrEqual;
use Butterfly\Component\Validation\String\StringLengthLessOrEqual;

class ScalarConstraintIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testTransformer()
    {
        $constraint = ScalarConstraint::create()
            ->addTransformer(new StringTrim())
            ->filter(' abc ');

        $this->assertEquals('abc', $constraint->getValue());
        $this->assertEquals(' abc ', $constraint->getOldValue());
    }

    public function testCallableTransformer()
    {
        $constraint = ScalarConstraint::create()
            ->addCallableTransformer(function($value) {
                return strlen($value);
            })
            ->filter('abc');

        $this->assertEquals(3, $constraint->getValue());
    }

    public function testValidator()
    {
        $constraint = ScalarConstraint::create()
            ->addValidator(new IsNull(), 'incorrect value')
            ->filter(null);

        $this->assertTrue($constraint->isValid());
        $this->assertNull($constraint->getFirstErrorMessage());
    }

    public function testValidatorIfNegative()
    {
        $constraint = ScalarConstraint::create()
            ->addValidator(new IsNull(), 'incorrect value', true)
            ->filter(null);

        $this->assertFalse($constraint->isValid());
        $this->assertEquals('incorrect value', $constraint->getFirstErrorMessage());
    }

    public function testMoreValidators()
    {
        $constraint = ScalarConstraint::create()
            ->addValidator(new StringLengthGreat(3), 'incorrect value')
            ->addValidator(new StringLengthGreat(5), 'incorrect value')
            ->filter('ab');

        $this->assertFalse($constraint->isValid());
        $this->assertCount(2, $constraint->getErrorMessages());
    }

    public function testFatalValidators()
    {
        $constraint = ScalarConstraint::create()
            ->addValidator(new StringLengthGreat(3), 'incorrect value', false, true)
            ->addValidator(new StringLengthGreat(5), 'incorrect value')
            ->filter('ab');

        $this->assertFalse($constraint->isValid());
        $this->assertCount(1, $constraint->getErrorMessages());
    }

    public function testCallableValidator()
    {
        $constraint = ScalarConstraint::create()
            ->addCallableValidator(function($value) {
                return 'abc' == $value;
            }, 'incorrect value')
            ->filter('abc');

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
            ->addValidator(new StringLengthLessOrEqual(3))
            ->addValidator(new StringLengthGreatOrEqual(3))
            ->addTransformer(new StringMaxLength(2))
            ->addValidator(new StringLengthLessOrEqual(2))
            ->addValidator(new StringLengthGreatOrEqual(2))
        ;

        $constraint->filter(' abc ');

        $this->assertTrue($constraint->isValid());
    }

    public function getDataForTestGetFormInValidator()
    {
        return array(
            array(array('username' => 'a', 'password' => 'b'), false, 3),
            array(array('username' => 'user1', 'password' => 'pass'), false, 1),
            array(array('username' => 'admin', 'password' => 'admin'), true, 0),
        );
    }

    /**
     * @dataProvider getDataForTestGetFormInValidator
     *
     * @param array $value
     * @param bool $expectedResult
     * @param int $countErrors
     */
    public function testGetFormInValidator(array $value, $expectedResult, $countErrors)
    {
        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('username')
                ->addValidator(new StringLengthGreat(2))
            ->end()
            ->addScalarConstraint('password')
                ->addValidator(new StringLengthGreat(2))
                ->addCallableValidator(function($value, ScalarConstraint $constraint) {
                    return $value == $constraint->getParent()->get('username')->getValue();
                })
            ->end()
        ;

        $constraint->filter($value);

        $this->assertEquals($expectedResult, $constraint->isValid());
        $this->assertCount($countErrors, $constraint->getErrorMessages());
    }
}
