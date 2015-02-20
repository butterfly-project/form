<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\String\StringLengthLess;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class StringLengthLessTest extends \PHPUnit_Framework_TestCase
{
    public function testCheck()
    {
        $validator = new StringLengthLess(5);

        $this->assertTrue($validator->check('abc'));
        $this->assertFalse($validator->check('abcde'));
        $this->assertFalse($validator->check('abcdef'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCheckIfIncorrectArgument()
    {
        $validator = new StringLengthLess(5);

        $this->assertFalse($validator->check(123));
    }
}
