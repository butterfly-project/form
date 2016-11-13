<?php

namespace Butterfly\Component\Form\Tests\Transform;

use Butterfly\Component\Form\Transform\StrReplace;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class StrReplaceTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $transformer = new StrReplace(array(
            'foo' => 'bar',
            'aaa' => 'bbb',
        ));

        $this->assertEquals('Hello, bar! bbb!', $transformer->transform('Hello, foo! aaa!'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTransformIfIncorrectType()
    {
        $transformer = new StrReplace(array());
        $transformer->transform(true);
    }
}
