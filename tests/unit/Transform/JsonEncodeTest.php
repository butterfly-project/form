<?php

namespace Butterfly\Component\Form\Tests\Transform;

use Butterfly\Component\Form\Transform\JsonEncode;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class JsonEncodeTest extends \PHPUnit_Framework_TestCase
{
    public function testTransformLetterCase()
    {
        $transformer = new JsonEncode(true);

        $this->assertEquals('{"foo":123,"bar":456}', $transformer->transform(array('foo' => 123, 'bar' => 456)));
    }
}
