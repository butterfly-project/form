<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\IsNotNull;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class IsNotNullTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestCheck()
    {
        return array(
            array('asdf', true, 'check is not null - success'),
            array(null, false, 'check is not null - fail'),
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
        $validator = new IsNotNull();

        $this->assertEquals($expectedResult, $validator->check($value), $caseMessage);
    }
}
