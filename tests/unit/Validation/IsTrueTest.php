<?php

namespace Butterfly\Component\Form\Tests\Validation\Tests;

use Butterfly\Component\Form\Validation\IsTrue;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class IsTrueTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestCheck()
    {
        return array(
            array(true, true),
            array(false, false),
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
        $validator = new IsTrue();

        $this->assertEquals($expectedResult, $validator->check($value));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCheckIfIncorrectValue()
    {
        $validator = new IsTrue();

        $validator->check(123);
    }
}
