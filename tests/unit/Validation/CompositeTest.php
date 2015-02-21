<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\Composite;
use Butterfly\Component\Form\Validation\IValidator;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class CompositeTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestCheck()
    {
        $trueV  = $this->getValidatorWithReturn(true);
        $falseV = $this->getValidatorWithReturn(false);

        return array(
            // empty array
            array(Composite::TYPE_AND, array(), true, 'check composite type "and" for empty array - success'),
            array(Composite::TYPE_OR, array(), true, 'check composite type "or" for empty array - success'),
            array(Composite::TYPE_XOR, array(), true, 'check composite type "xor" for empty array - success'),


            // one validator
            array(Composite::TYPE_AND, array($trueV), true, 'check composite type "and" for "true" - success'),
            array(Composite::TYPE_AND, array($falseV), false, 'check composite type "and" for "false" - fail'),

            array(Composite::TYPE_OR, array($trueV), true, 'check composite type "or" for "true" - success'),
            array(Composite::TYPE_OR, array($falseV), false, 'check composite type "or" for "false" - fail'),

            array(Composite::TYPE_XOR, array($trueV), true, 'check composite type "xor" for "true" - success'),
            array(Composite::TYPE_XOR, array($falseV), false, 'check composite type "xor" for "false" - fail'),


            // two validators
            array(Composite::TYPE_AND, array($trueV, $trueV), true, 'check composite type "and" for "true-true" - success'),
            array(Composite::TYPE_AND, array($trueV, $falseV), false, 'check composite type "and" for "true-false" - fail'),
            array(Composite::TYPE_AND, array($falseV, $falseV), false, 'check composite type "and" for "false-false" - fail'),

            array(Composite::TYPE_OR, array($trueV, $trueV), true, 'check composite type "or" for "true-true" - success'),
            array(Composite::TYPE_OR, array($trueV, $falseV), true, 'check composite type "or" for "true-false" - success'),
            array(Composite::TYPE_OR, array($falseV, $falseV), false, 'check composite type "or" for "false-false" - fail'),

            array(Composite::TYPE_XOR, array($trueV, $trueV), false, 'check composite type "xor" for "true-true" - fail'),
            array(Composite::TYPE_XOR, array($trueV, $falseV), true, 'check composite type "xor" for "true-false" - success'),
            array(Composite::TYPE_XOR, array($falseV, $falseV), false, 'check composite type "xor" for "false-false" - fail'),


            // three validators
            array(Composite::TYPE_AND, array($trueV, $trueV, $trueV), true, 'check composite type "and" for "true-true-true" - success'),
            array(Composite::TYPE_AND, array($trueV, $trueV, $falseV), false, 'check composite type "and" for "true-true-false" - fail'),
            array(Composite::TYPE_AND, array($trueV, $falseV, $falseV), false, 'check composite type "and" for "true-false-false" - fail'),
            array(Composite::TYPE_AND, array($falseV, $falseV, $falseV), false, 'check composite type "and" for "false-false-false" - fail'),

            array(Composite::TYPE_OR, array($trueV, $trueV, $trueV), true, 'check composite type "or" for "true-true-true" - success'),
            array(Composite::TYPE_OR, array($trueV, $trueV, $falseV), true, 'check composite type "or" for "true-true-false" - success'),
            array(Composite::TYPE_OR, array($trueV, $falseV, $falseV), true, 'check composite type "or" for "true-false-false" - success'),
            array(Composite::TYPE_OR, array($falseV, $falseV, $falseV), false, 'check composite type "or" for "false-false-false" - fail'),

            array(Composite::TYPE_XOR, array($trueV, $trueV, $trueV), true, 'check composite type "xor" for "true-true-true" - success'),
            array(Composite::TYPE_XOR, array($trueV, $trueV, $falseV), false, 'check composite type "xor" for "true-true-false" - fail'),
            array(Composite::TYPE_XOR, array($trueV, $falseV, $falseV), true, 'check composite type "xor" for "true-false-false" - success'),
            array(Composite::TYPE_XOR, array($falseV, $falseV, $falseV), false, 'check composite type "xor" for "false-false-false" - fail'),
        );
    }

    /**
     * @dataProvider getDataForTestCheck
     *
     * @param string $type
     * @param array $validators
     * @param bool $expectedResult
     * @param string $caseMessage
     */
    public function testCheck($type, array $validators, $expectedResult, $caseMessage)
    {
        $value = 1;

        $validator = new Composite($type, $validators);

        $this->assertEquals($expectedResult, $validator->check($value), $caseMessage);
    }

    /**
     * @param bool $returnValue
     * @return IValidator
     */
    protected function getValidatorWithReturn($returnValue)
    {
        $validator = $this->getValidator();

        $validator
            ->expects($this->any())
            ->method('check')
            ->withAnyParameters()
            ->willReturn($returnValue);

        return $validator;
    }

    public function testAddValidator()
    {
        $validator1 = $this->getValidator();
        $validator1
            ->expects($this->once())
            ->method('check')
            ->withAnyParameters()
            ->willReturn(true);

        $validator2 = $this->getValidator();
        $validator2
            ->expects($this->once())
            ->method('check')
            ->withAnyParameters()
            ->willReturn(true);

        $validator = new Composite(Composite::TYPE_AND);
        $validator
            ->addValidator($validator1)
            ->addValidator($validator2);

        $validator->check(1);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|IValidator
     */
    protected function getValidator()
    {
        return $this->getMock('\Butterfly\Component\Form\Validation\IValidator');
    }
}
