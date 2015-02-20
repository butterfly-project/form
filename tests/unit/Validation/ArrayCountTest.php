<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\ArrayCount;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ArrayCountTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestCheck()
    {
        return array(
            array(array(), 0, true, 'check empty array'),
            array(array(1, 2), 2, true, 'check not empty array'),
            array(array(1), 2, false, 'check not empty array'),
        );
    }

    /**
     * @dataProvider getDataForTestCheck
     *
     * @param mixed $value
     * @param int $count
     * @param bool $expectedResult
     * @param string $caseMessage
     */
    public function testCheck($value, $count, $expectedResult, $caseMessage)
    {
        $validator = new ArrayCount($count);

        $this->assertEquals($expectedResult, $validator->check($value), $caseMessage);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCheckIfInvalidArgument()
    {
        $validator = new ArrayCount(1);

        $validator->check('abc');
    }
}
