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
            array(null, true, 'check "null" value - success'),

            array(false, true, 'check "false" value - success'),
            array(true, false, 'check "true" value - fail'),

            array(0, true, 'check empty integer value - success'),
            array(1, false, 'check not empty integer value - fail'),

            array(0.00, true, 'check empty float value - success'),
            array(0.01, false, 'check not empty float value - fail'),

            array('', true, 'check empty string value - success'),
            array('a', false, 'check not empty string value - fail'),

            array(array(), true, 'check empty string - success'),
            array(array(1), false, 'check not empty string - fail'),
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
