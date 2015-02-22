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
            array(null, false, 'check "null" value - fail'),

            array(true, true, 'check "true" value - success'),
            array(false, false, 'check "false" value - fail'),

            array(1, true, 'check not empty integer value - success'),
            array(0, false, 'check empty integer value - fail'),

            array(0.01, true, 'check not empty float value - success'),
            array(0.00, false, 'check empty float value - fail'),

            array('a', true, 'check not empty string value - success'),
            array('', false, 'check empty string value - fail'),

            array(array(1), true, 'check not empty string - success'),
            array(array(), false, 'check empty string - fail'),
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
