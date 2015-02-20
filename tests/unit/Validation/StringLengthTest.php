<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\StringLength;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class StringLengthTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestCheck()
    {
        return array(
            array(3, StringLength::EQUAL, 'abc', true, 'string length equal expected - success'),
            array(3, StringLength::EQUAL, 'ab', false, 'string length equal expected - fail'),

            array(3, StringLength::NOT_EQUAL, 'ab', true, 'string length not equal expected - success'),
            array(3, StringLength::NOT_EQUAL, 'abc', false, 'string length not equal expected - fail'),

            array(3, StringLength::LESS, 'ab', true, 'string length less expected - success'),
            array(3, StringLength::LESS, 'abc', false, 'string length less expected - fail'),

            array(3, StringLength::LESS_OR_EQUAL, 'ab', true, 'string length less or equal expected - success'),
            array(3, StringLength::LESS_OR_EQUAL, 'abc', true, 'string length less or equal expected - success'),
            array(3, StringLength::LESS_OR_EQUAL, 'abcd', false, 'string length less or equal expected - fail'),

            array(3, StringLength::GREATER, 'abcd', true, 'string length greater expected - success'),
            array(3, StringLength::GREATER, 'abc', false, 'string length greater expected - fail'),

            array(3, StringLength::GREATER_OR_EQUAL, 'abcd', true, 'string length greater or equal expected - success'),
            array(3, StringLength::GREATER_OR_EQUAL, 'abc', true, 'string length greater or equal expected - success'),
            array(3, StringLength::GREATER_OR_EQUAL, 'ab', false, 'string length greater or equal expected - fail'),
        );
    }

    /**
     * @dataProvider getDataForTestCheck
     *
     * @param mixed $value
     * @param int $length
     * @param string $operator
     * @param bool $expectedResult
     * @param string $caseMessage
     */
    public function testCheck($length, $operator, $value, $expectedResult, $caseMessage)
    {
        $validator = new StringLength($length, $operator);

        $this->assertEquals($expectedResult, $validator->check($value), $caseMessage);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCheckIfIncorrectOperator()
    {
        $validator = new StringLength(3, 'undefined');

        $validator->check('abc');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCheckIfIncorrectValue()
    {
        $validator = new StringLength(3, StringLength::EQUAL);

        $validator->check(array());
    }
}
