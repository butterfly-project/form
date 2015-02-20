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
            array(1, array(1), true, 'check exists value'),
            array(1, array(), false, 'check not existing value'),
            array(null, array(null), true, 'check existing "null" value'),
            array('abc', array('a' => 'abc'), true, 'check existing value in associated array'),
        );
    }

    /**
     * @dataProvider getDataForTestCheck
     *
     * @param mixed $value
     * @param string $key
     * @param bool $expectedResult
     * @param string $caseMessage
     */
    public function testCheck($value, $key, $expectedResult, $caseMessage)
    {
        $validator = new InArray($key);

        $this->assertEquals($expectedResult, $validator->check($value), $caseMessage);
    }
}
