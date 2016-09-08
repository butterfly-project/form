<?php

namespace Butterfly\Component\Form\Tests;

use Butterfly\Component\Form\ArrayConstraint;
use Butterfly\Component\Form\IConstraint;
use Butterfly\Component\Form\ScalarConstraint;
use Butterfly\Component\Form\Transform\StringLength as StringLengthTransformer;
use Butterfly\Component\Form\Transform\Trim;
use Butterfly\Component\Form\Validation\Compare;
use Butterfly\Component\Form\Validation\IsNotEmpty;
use Butterfly\Component\Form\Validation\IsNull;
use Butterfly\Component\Form\Validation\StringLength as StringLengthValidator;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ScalarConstraintIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testTransformer()
    {
        $constraint = ScalarConstraint::create()
            ->addTransformer(new Trim());

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
            ->addTransformer(new Trim());

        $constraint->filter(' abc ');

        $constraint->getValue('undefined');
    }

    public function testHasValue()
    {
        $constraint = ScalarConstraint::create()
            ->addTransformer(new Trim());

        $constraint->filter(' abc ');

        $this->assertTrue($constraint->hasValue(IConstraint::VALUE_BEFORE));
        $this->assertTrue($constraint->hasValue(IConstraint::VALUE_AFTER));
    }

    public function testHasValueIfUndefinedLabel()
    {
        $constraint = ScalarConstraint::create()
            ->addTransformer(new Trim());

        $constraint->filter(' abc ');

        $this->assertFalse($constraint->hasValue('undefined'));
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

    public function testGetStructuredErrorMessages()
    {
        $constraint = ScalarConstraint::create()
            ->addValidator(new StringLengthValidator(3, StringLengthValidator::GREATER), 'incorrect value 1')
            ->addValidator(new StringLengthValidator(5, StringLengthValidator::GREATER), 'incorrect value 2');

        $constraint->filter('ab');

        $expectedErrorMessages = array(
            'incorrect value 1',
            'incorrect value 2',
        );
        $this->assertEquals($expectedErrorMessages, $constraint->getStructuredErrorMessages());
    }

    public function testMoreValidators()
    {
        $constraint = ScalarConstraint::create()
            ->addValidator(new StringLengthValidator(3, StringLengthValidator::GREATER), 'incorrect value')
            ->addValidator(new StringLengthValidator(5, StringLengthValidator::GREATER), 'incorrect value');

        $constraint->filter('ab');

        $this->assertFalse($constraint->isValid());
        $this->assertCount(2, $constraint->getErrorMessages());
    }

    public function testFatalValidators()
    {
        $constraint = ScalarConstraint::create()
            ->addValidator(new StringLengthValidator(3, StringLengthValidator::GREATER), 'incorrect value', false, true)
            ->addValidator(new StringLengthValidator(5, StringLengthValidator::GREATER), 'incorrect value');

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
        return $this->createMock('\Butterfly\Component\Form\ArrayConstraint');
    }

    public function testOrder()
    {
        $constraint = ScalarConstraint::create()
            ->addTransformer(new Trim())
            ->addValidator(new StringLengthValidator(3, StringLengthValidator::LESS_OR_EQUAL))
            ->addValidator(new StringLengthValidator(3, StringLengthValidator::GREATER_OR_EQUAL))
            ->addTransformer(new StringLengthTransformer(2))
            ->addValidator(new StringLengthValidator(2, StringLengthValidator::LESS_OR_EQUAL))
            ->addValidator(new StringLengthValidator(2, StringLengthValidator::GREATER_OR_EQUAL))
        ;

        $constraint->filter(' abc ');

        $this->assertTrue($constraint->isValid());
    }

    public function testSaveValue()
    {
        $constraint = ScalarConstraint::create()
            ->addTransformer(new Trim())
            ->saveValue('label1')
            ->addTransformer(new StringLengthTransformer(2))
            ->saveValue('label2')
            ->addTransformer(new StringLengthTransformer(1))
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
            ->addTransformer(new Trim())
            ->addValidator(new Compare('abc'))
            ->saveValue('label1')

            ->addTransformer(new StringLengthTransformer(1))
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

    public function testClean()
    {
        $constraint = ScalarConstraint::create()
            ->addTransformer(new Trim())
            ->addValidator(new IsNotEmpty(), 'Value can not be empty');

        $constraint->filter(' ');

        $this->assertFalse($constraint->isValid());
        $this->assertCount(1, $constraint->getErrorMessages());
        $this->assertTrue($constraint->hasValue(IConstraint::VALUE_BEFORE));
        $this->assertTrue($constraint->hasValue(IConstraint::VALUE_AFTER));

        $constraint->clean();

        $this->assertTrue($constraint->isValid());
        $this->assertCount(0, $constraint->getErrorMessages());
        $this->assertFalse($constraint->hasValue(IConstraint::VALUE_BEFORE));
        $this->assertFalse($constraint->hasValue(IConstraint::VALUE_AFTER));
    }

    public function testIsFiltered()
    {
        $constraint = ScalarConstraint::create();

        $this->assertFalse($constraint->isFiltered());

        $constraint->filter('abc');

        $this->assertTrue($constraint->isFiltered());

        $constraint->clean();

        $this->assertFalse($constraint->isFiltered());
    }
}
