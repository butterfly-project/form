<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\String\StringLengthGreatOrEqual;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class StringLengthGreatOrEqualTest extends \PHPUnit_Framework_TestCase
{
    public function testCheck()
    {
        $validator = new StringLengthGreatOrEqual(5);

        $this->assertFalse($validator->check('abc'));
        $this->assertTrue($validator->check('abcde'));
        $this->assertTrue($validator->check('abcdef'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCheckIfIncorrectArgument()
    {
        $validator = new StringLengthGreatOrEqual(5);

        $this->assertFalse($validator->check(123));
    }
}
