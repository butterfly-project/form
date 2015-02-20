<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\IsFalse;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class IsFalseTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestCheck()
    {
        return array(
            array(true, false),
            array(false, true),
        );
    }

    /**
     * @dataProvider getDataForTestCheck
     *
     * @param mixed $value
     * @param bool $expectedResult
     */
    public function testCheck($value, $expectedResult)
    {
        $validator = new IsFalse();

        $this->assertEquals($expectedResult, $validator->check($value));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCheckIfIncorrectValue()
    {
        $validator = new IsFalse();

        $validator->check(123);
    }
}
