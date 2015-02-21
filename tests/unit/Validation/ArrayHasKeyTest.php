<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\ArrayHasKey;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ArrayHasKeyTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestCheck()
    {
        return array(
            array(array('foo' => 1), 'foo', true, 'check exists key - success'),
            array(array('foo' => null), 'foo', true, 'check existing key with "null" value - success'),
            array(array('foo' => 1), 'bar', false, 'check not existing key - fail'),
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
        $validator = new ArrayHasKey($key);

        $this->assertEquals($expectedResult, $validator->check($value), $caseMessage);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCheckIfInvalidArgument()
    {
        $validator = new ArrayHasKey(1);

        $validator->check('abc');
    }
}
