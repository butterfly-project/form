<?php

namespace Butterfly\Component\Form\Tests\Transform;

use Butterfly\Component\Form\Transform\RegReplace;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class RegSearchTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $transformer = new RegReplace('[^a-zA-Z]', '');

        $this->assertEquals('Hello', $transformer->transform('Строка. Hello. 1234'));
    }

    public function testTransformWithOptions()
    {
        $transformer = new RegReplace('[^a-z]', '', 'i');

        $this->assertEquals('Hello', $transformer->transform('Строка. Hello. 1234'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTransformIfIncorrectType()
    {
        $transformer = new RegReplace('', '');
        $transformer->transform(true);
    }
}
