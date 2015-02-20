<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\IsNotEmpty;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class IsNotEmptyTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestCheck()
    {
        return array(
            array(null, false, 'check "null" value'),

            array(false, false, 'check "false" value'),
            array(true, true, 'check "true" value'),

            array(0, false, 'check empty integer value'),
            array(1, true, 'check not empty integer value'),

            array(0.00, false, 'check empty float value'),
            array(0.01, true, 'check not empty float value'),

            array('', false, 'check empty string value'),
            array('a', true, 'check not empty string value'),

            array(array(), false, 'check empty string'),
            array(array(1), true, 'check not empty string'),
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
        $validator = new IsNotEmpty();

        $this->assertEquals($expectedResult, $validator->check($value), $caseMessage);
    }
}
