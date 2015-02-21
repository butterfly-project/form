<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\IsNull;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class IsNullTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestCheck()
    {
        return array(
            array(null, true, 'check is null - success'),
            array('asdf', false, 'check is null - fail'),
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
        $validator = new IsNull();

        $this->assertEquals($expectedResult, $validator->check($value), $caseMessage);
    }
}
