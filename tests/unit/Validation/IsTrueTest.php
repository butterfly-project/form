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
            array(true, true, 'check is "true" - success'),
            array(false, false, 'check is "true" - fail'),
        );
    }

    /**
     * @dataProvider getDataForTestCheck
     *
     * @param mixed $value
     * @param bool $expectedResult
     * @param string $caseMessage
     */
    public function testCheck($value, $expectedResult, $caseMessage)
    {
        $validator = new IsTrue();

        $this->assertEquals($expectedResult, $validator->check($value), $caseMessage);
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
