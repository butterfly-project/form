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
            array(Compare::EQUAL, 1, 1, true, 'check equal - success'),
            array(Compare::EQUAL, true, 'asdf', true, 'check equal with juggling - success'),
            array(Compare::EQUAL, 0, 1, false, 'check equal - fail'),

            array(Compare::IDENTICALLY, 1, 1, true, 'check identically - success'),
            array(Compare::IDENTICALLY, 0, 1, false, 'check identically - fail'),
            array(Compare::IDENTICALLY, true, 'asdf', false, 'check identically with juggling - fail'),

            array(Compare::NOT_EQUAL, 0, 1, true, 'check not equal - success'),
            array(Compare::NOT_EQUAL, 1, 1, false, 'check not equal - fail'),
            array(Compare::NOT_EQUAL, true, 'asdf', false, 'check not equal with juggling - fail'),

            array(Compare::NOT_EQUAL_ALTERNATIVE, 0, 1, true, 'check not equal alternative - success'),
            array(Compare::NOT_EQUAL_ALTERNATIVE, 1, 1, false, 'check not equal alternative - fail'),
            array(Compare::NOT_EQUAL_ALTERNATIVE, true, 'asdf', false, 'check not equal alternative with juggling - fail'),

            array(Compare::NOT_IDENTICALLY, 0, 1, true, 'check not identically - success'),
            array(Compare::NOT_IDENTICALLY, true, 'asdf', true, 'check not identically with juggling - success'),
            array(Compare::NOT_IDENTICALLY, 1, 1, false, 'check not identically - fail'),

            array(Compare::LESS, 1, 0, true, 'check less - success'),
            array(Compare::LESS, 1, 5, false, 'check less - fail'),

            array(Compare::GREATER, 1, 5, true, 'check greater - success'),
            array(Compare::GREATER, 1, 0, false, 'check greater - fail'),

            array(Compare::LESS_OR_EQUAL, 1, 0, true, 'check less or equal - success'),
            array(Compare::LESS_OR_EQUAL, 1, 1, true, 'check less or equal - success'),
            array(Compare::LESS_OR_EQUAL, 1, 5, false, 'check less or equal - fail'),

            array(Compare::GREATER_OR_EQUAL, 1, 5, true, 'check greater or equal - success'),
            array(Compare::GREATER_OR_EQUAL, 1, 1, true, 'check greater or equal - success'),
            array(Compare::GREATER_OR_EQUAL, 1, 0, false, 'check greater or equal - fail'),
        );
    }

    /**
     * @dataProvider getDataForTestCheck
     *
     * @param mixed $operator
     * @param mixed $parameter
     * @param mixed $value
     * @param bool $expectedResult
     * @param string $caseMessage
     */
    public function testCheck($operator, $parameter, $value, $expectedResult, $caseMessage)
    {
        $validator = new Compare($parameter, $operator);

        $this->assertEquals($expectedResult, $validator->check($value), $caseMessage);
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
