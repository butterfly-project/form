<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\ArrayContains;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ArrayContainsTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestCheck()
    {
        return array(
            array(array(1), 1, true, 'check exists value'),
            array(array(), 1, false, 'check not existing value'),
            array(array(null), null, true, 'check existing "null" value'),
            array(array('a' => 'abc'), 'abc', true, 'check existing value in associated array'),
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
        $validator = new ArrayContains($key);

        $this->assertEquals($expectedResult, $validator->check($value), $caseMessage);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCheckIfInvalidArgument()
    {
        $validator = new ArrayContains(1);

        $validator->check('abc');
    }
}
