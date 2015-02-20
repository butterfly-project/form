<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\String\StringLengthLessOrEqual;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class StringLengthLessOrEqualTest extends \PHPUnit_Framework_TestCase
{
    public function testCheck()
    {
        $validator = new StringLengthLessOrEqual(5);

        $this->assertTrue($validator->check('abc'));
        $this->assertTrue($validator->check('abcde'));
        $this->assertFalse($validator->check('abcdef'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCheckIfIncorrectArgument()
    {
        $validator = new StringLengthLessOrEqual(5);

        $this->assertFalse($validator->check(123));
    }
}
