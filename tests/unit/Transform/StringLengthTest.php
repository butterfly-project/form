<?php

namespace Butterfly\Component\Form\Tests\Transform;

use Butterfly\Component\Form\Transform\StringLength;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class StringLengthTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $transformer = new StringLength(3);

        $this->assertEquals('abc', $transformer->transform('abcdef'));
    }

    public function testTransformIfSmallSymbols()
    {
        $transformer = new StringLength(50);

        $this->assertEquals('abcdef', $transformer->transform('abcdef'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTransformIfIncorrectArgument()
    {
        $transformer = new StringLength(5);

        $transformer->transform(123);
    }
}
