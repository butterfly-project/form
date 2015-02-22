<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\RegExp;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class RegExpTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestCheck()
    {
        return array(
            array('/^[0-9]+$/', 123, true, 'check pattern - success'),
            array('/^[0-9]+$/', 'abc', false, 'check pattern - fail'),
        );
    }

    /**
     * @dataProvider getDataForTestCheck
     *
     * @param mixed $value
     * @param string $pattern
     * @param bool $expectedResult
     * @param string $caseMessage
     */
    public function testCheck($pattern, $value, $expectedResult, $caseMessage)
    {
        $validator = new RegExp($pattern);

        $this->assertEquals($expectedResult, $validator->check($value), $caseMessage);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCheckIfIncorrectPattern()
    {
        $validator = new RegExp(1234);

        $validator->check(1);
    }
}
