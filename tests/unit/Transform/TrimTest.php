<?php

namespace Butterfly\Component\Form\Tests\Transform;

use Butterfly\Component\Form\Transform\Trim;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class TrimTest extends \PHPUnit_Framework_TestCase
{
    public function testTransformIfAllTarget()
    {
        $transformer = new Trim(Trim::TRIM_ALL);

        $this->assertEquals('abc', $transformer->transform(' abc '));
    }

    public function testTransformIfLeftTarget()
    {
        $transformer = new Trim(Trim::TRIM_LEFT);

        $this->assertEquals('abc ', $transformer->transform(' abc '));
    }

    public function testTransformIfRightTarget()
    {
        $transformer = new Trim(Trim::TRIM_RIGTH);

        $this->assertEquals(' abc', $transformer->transform(' abc '));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTransformIfIncorrectArgument()
    {
        $transformer = new Trim();

        $transformer->transform(123);
    }
}
