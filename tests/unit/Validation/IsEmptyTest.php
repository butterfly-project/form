<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\IsEmpty;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class IsEmptyTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestCheck()
    {
        return array(
            array(null, true, 'check "null" value'),

            array(false, true, 'check "false" value'),
            array(true, false, 'check "true" value'),

            array(0, true, 'check empty integer value'),
            array(1, false, 'check not empty integer value'),

            array(0.00, true, 'check empty float value'),
            array(0.01, false, 'check not empty float value'),

            array('', true, 'check empty string value'),
            array('a', false, 'check not empty string value'),

            array(array(), true, 'check empty string'),
            array(array(1), false, 'check not empty string'),
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
        $validator = new IsEmpty();

        $this->assertEquals($expectedResult, $validator->check($value), $caseMessage);
    }
}
