<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\Compare;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class CompareTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestCheck()
    {
        return array(
            // equal
            array(1, 1, Compare::EQUAL, true),
            array(0, 1, Compare::EQUAL, false),
            array(true, 'asdf', Compare::EQUAL, true),

            // identical
            array(1, 1, Compare::IDENTICALLY, true),
            array(0, 1, Compare::IDENTICALLY, false),
            array(true, 'asdf', Compare::IDENTICALLY, false),

            // not equal
            array(1, 1, Compare::NOT_EQUAL, false),
            array(0, 1, Compare::NOT_EQUAL, true),
            array(true, 'asdf', Compare::NOT_EQUAL, false),

            // not equal alternative
            array(1, 1, Compare::NOT_EQUAL_ALTERNATIVE, false),
            array(0, 1, Compare::NOT_EQUAL_ALTERNATIVE, true),
            array(true, 'asdf', Compare::NOT_EQUAL_ALTERNATIVE, false),

            // not identical
            array(1, 1, Compare::NOT_IDENTICALLY, false),
            array(0, 1, Compare::NOT_IDENTICALLY, true),
            array(true, 'asdf', Compare::NOT_IDENTICALLY, true),

            // less
            array(1, 0, Compare::LESS, true),
            array(1, 5, Compare::LESS, false),

            // greater
            array(1, 5, Compare::GREATER, true),
            array(1, 0, Compare::GREATER, false),

            // less or equal
            array(1, 0, Compare::LESS_OR_EQUAL, true),
            array(1, 1, Compare::LESS_OR_EQUAL, true),
            array(1, 5, Compare::LESS_OR_EQUAL, false),

            // less or equal
            array(1, 5, Compare::GREATER_OR_EQUAL, true),
            array(1, 1, Compare::GREATER_OR_EQUAL, true),
            array(1, 0, Compare::GREATER_OR_EQUAL, false),
        );
    }

    /**
     * @dataProvider getDataForTestCheck
     *
     * @param mixed $parameter
     * @param mixed $value
     * @param mixed $operator
     * @param bool $expectedResult
     */
    public function testCheck($parameter, $value, $operator, $expectedResult)
    {
        $validator = new Compare($parameter, $operator);

        $this->assertEquals($expectedResult, $validator->check($value));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCheckIfIncorrectOperator()
    {
        $validator = new Compare(1, '+');

        $validator->check(1);
    }
}
