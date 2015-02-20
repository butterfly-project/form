<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\String\StringLengthEqual;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class StringLengthEqualTest extends \PHPUnit_Framework_TestCase
{
    public function testCheck()
    {
        $validator = new StringLengthEqual(5);

        $this->assertFalse($validator->check('abc'));
        $this->assertTrue($validator->check('abcde'));
        $this->assertFalse($validator->check('abcdef'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCheckIfIncorrectArgument()
    {
        $validator = new StringLengthEqual(5);

        $this->assertFalse($validator->check(123));
    }
}
