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
            array(array(), 0, ArrayCount::EQUAL, true, 'check empty array - success'),
            array(array(1, 2), 2, ArrayCount::EQUAL, true, 'check not empty array - success'),
            array(array(1), 2, ArrayCount::EQUAL, false, 'check not empty array - fail'),

            array(range(0, 4), 2, ArrayCount::GREATER, true, 'check GREATER - success'),
            array(range(0, 4), 10, ArrayCount::GREATER, false, 'check GREATER - fail'),

            array(range(0, 4), 2, ArrayCount::GREATER_OR_EQUAL, true, 'check GREATER_OR_EQUAL - succes'),
            array(range(0, 4), 5, ArrayCount::GREATER_OR_EQUAL, true, 'check GREATER_OR_EQUAL - success'),
            array(range(0, 4), 10, ArrayCount::GREATER_OR_EQUAL, false, 'check GREATER_OR_EQUAL - fail'),

            array(range(0, 4), 10, ArrayCount::LESS, true, 'check LESS - success'),
            array(range(0, 4), 2, ArrayCount::LESS, false, 'check LESS - fail'),

            array(range(0, 4), 10, ArrayCount::LESS_OR_EQUAL, true, 'check LESS_OR_EQUAL - succes'),
            array(range(0, 4), 5, ArrayCount::LESS_OR_EQUAL, true, 'check LESS_OR_EQUAL - success'),
            array(range(0, 4), 2, ArrayCount::LESS_OR_EQUAL, false, 'check LESS_OR_EQUAL - fail'),

            array(range(0, 4), 10, ArrayCount::NOT_EQUAL, true, 'check NOT_EQUAL - succes'),
            array(range(0, 4), 5, ArrayCount::NOT_EQUAL, false, 'check NOT_EQUAL - fail'),
        );
    }

    /**
     * @dataProvider getDataForTestCheck
     *
     * @param mixed $value
     * @param int $count
     * @param string $operation
     * @param bool $expectedResult
     * @param string $caseMessage
     */
    public function testCheck($value, $count, $operation, $expectedResult, $caseMessage)
    {
        $validator = new ArrayCount($count, $operation);

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
