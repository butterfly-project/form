<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\InArray;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class InArrayTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestCheck()
    {
        return array(
            array(array(1), 1, true, 'check exists value - success'),
            array(array(null), null, true, 'check existing "null" value - success'),
            array(array('a' => 'abc'), 'abc', true, 'check existing value in associated array - success'),
            array(array(), 1, false, 'check not existing value - fail'),
        );
    }

    /**
     * @dataProvider getDataForTestCheck
     *
     * @param string $list
     * @param mixed $value
     * @param bool $expectedResult
     * @param string $caseMessage
     */
    public function testCheck($list, $value, $expectedResult, $caseMessage)
    {
        $validator = new InArray($list);

        $this->assertEquals($expectedResult, $validator->check($value), $caseMessage);
    }
}
