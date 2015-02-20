<?php

namespace Butterfly\Component\Form\Tests\Validation\Tests;

use Butterfly\Component\Form\Validation\String\StringLengthGreat;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class StringLengthGreatTest extends \PHPUnit_Framework_TestCase
{
    public function testCheck()
    {
        $validator = new StringLengthGreat(5);

        $this->assertFalse($validator->check('abc'));
        $this->assertFalse($validator->check('abcde'));
        $this->assertTrue($validator->check('abcdef'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCheckIfIncorrectArgument()
    {
        $validator = new StringLengthGreat(5);

        $this->assertFalse($validator->check(123));
    }
}
