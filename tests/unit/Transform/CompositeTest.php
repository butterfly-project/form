<?php

namespace Butterfly\Component\Form\Tests\Transform;

use Butterfly\Component\Form\Transform\Composite;
use Butterfly\Component\Form\Transform\ITransformer;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class CompositeTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $transformer = new Composite(array(
            $this->getTransformer(),
            $this->getTransformer()
        ));

        $transformer
            ->addTransformer($this->getTransformer())
            ->addTransformer($this->getTransformer());

        $transformer->transform(1);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ITransformer
     */
    protected function getTransformer()
    {
        $object = $this->createMock('\Butterfly\Component\Form\Transform\ITransformer');

        $object
            ->expects($this->once())
            ->method('transform')
            ->with(1)
            ->will($this->returnValue(1));

        return $object;
    }
}
