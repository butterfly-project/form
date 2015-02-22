<?php

namespace Butterfly\Component\Form\Tests\Transform;

use Butterfly\Component\Form\Transform\ToType;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ToTypeTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestTransform()
    {
        return array(
            array(ToType::TYPE_BOOL, 1, true, 'transform to bool: 1'),
            array(ToType::TYPE_BOOL, 0, false, 'transform to bool: 0'),

            array(ToType::TYPE_INT, '1', 1, 'transform to int: "1"'),
            array(ToType::TYPE_INT, '0', 0, 'transform to int: "0"'),

            array(ToType::TYPE_FLOAT, 1, 1.0, 'transform to float: 1'),
            array(ToType::TYPE_FLOAT, '1.1', 1.1, 'transform to float: "1.1"'),

            array(ToType::TYPE_STRING, 1, '1', 'transform to string: 1'),
            array(ToType::TYPE_STRING, true, '1', 'transform to string: true'),

            array(ToType::TYPE_ARRAY, 1, array(1), 'transform to array: 1'),
            array(ToType::TYPE_ARRAY, true, array(true), 'transform to array: true'),
        );
    }

    /**
     * @dataProvider getDataForTestTransform
     *
     * @param string $type
     * @param mixed $value
     * @param mixed $expectedResult
     * @param string $caseMessage
     */
    public function testTransform($type, $value, $expectedResult, $caseMessage)
    {
        $transformer = new ToType($type);

        $result = $transformer->transform($value);

        $this->assertTrue($expectedResult === $result, $caseMessage);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTransformIfUndefinedType()
    {
        $transformer = new ToType('undefined');

        $transformer->transform(1);
    }
}
