<?php

namespace Butterfly\Component\Form\Tests\Transform\Type;

use Butterfly\Component\Form\Transform\Type\ToInt;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ToIntTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $transformer = new ToInt();

        $this->assertTrue(is_int($transformer->transform('123')));
        $this->assertEquals(123, $transformer->transform('123'));

        $this->assertTrue(is_int($transformer->transform('123abc')));
        $this->assertEquals(123, $transformer->transform('123abc'));

        $this->assertTrue(is_int($transformer->transform(true)));
        $this->assertEquals(1, $transformer->transform(true));

        $this->assertTrue(is_int($transformer->transform(null)));
        $this->assertEquals(0, $transformer->transform(null));
    }
}
