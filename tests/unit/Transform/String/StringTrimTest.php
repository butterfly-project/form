<?php

namespace Butterfly\Component\Form\Tests\Transform\String;

use Butterfly\Component\Form\Transform\String\StringTrim;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class StringTrimTest extends \PHPUnit_Framework_TestCase
{
    public function testTransformIfAllTarget()
    {
        $transformer = new StringTrim(StringTrim::TRIM_ALL);

        $this->assertEquals('abc', $transformer->transform(' abc '));
    }

    public function testTransformIfLeftTarget()
    {
        $transformer = new StringTrim(StringTrim::TRIM_LEFT);

        $this->assertEquals('abc ', $transformer->transform(' abc '));
    }

    public function testTransformIfRightTarget()
    {
        $transformer = new StringTrim(StringTrim::TRIM_RIGTH);

        $this->assertEquals(' abc', $transformer->transform(' abc '));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTransformIfIncorrectArgument()
    {
        $transformer = new StringTrim();

        $transformer->transform(123);
    }
}
