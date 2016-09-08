<?php

namespace Butterfly\Component\Form\Tests\Transform;

use Butterfly\Component\Form\Transform\JsonDecode;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class JsonDecodeTest extends \PHPUnit_Framework_TestCase
{
    public function testTransformLetterCase()
    {
        $transformer = new JsonDecode(true);

        $this->assertEquals(array('foo' => 123, 'bar' => 456), $transformer->transform('{"foo": 123, "bar": 456}'));
    }
}
