<?php

namespace Butterfly\Component\Form\Tests\Transform\Type;

use Butterfly\Component\Form\Transform\Type\ToString;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ToStringTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $transformer = new ToString();

        $this->assertTrue(is_string($transformer->transform(123)));
        $this->assertTrue('123' === $transformer->transform(123));

        $this->assertTrue(is_string($transformer->transform(true)));
        $this->assertTrue('1' === $transformer->transform(true));

        $this->assertTrue(is_string($transformer->transform(null)));
        $this->assertTrue('' === $transformer->transform(null));
    }
}
