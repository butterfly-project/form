<?php

namespace Butterfly\Component\Form\Tests\Transform\String;

use Butterfly\Component\Form\Transform\String\StringMaxLength;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class StringMaxLengthTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $transformer = new StringMaxLength(3);

        $this->assertEquals('abc', $transformer->transform('abcdef'));
    }

    public function testTransformIfSmallSymbols()
    {
        $transformer = new StringMaxLength(50);

        $this->assertEquals('abcdef', $transformer->transform('abcdef'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTransformIfIncorrectArgument()
    {
        $transformer = new StringMaxLength(5);

        $transformer->transform(123);
    }
}
