<?php

namespace Butterfly\Component\Form\Tests\Transform;

use Butterfly\Component\Form\Transform\Mapper;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class MapperTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestTransform()
    {
        return array(
            array(array('on' => true, 'off' => false), null, 'on', true, 'simple map - success'),

            array(array(1 => 'a', '1' => 'b'), null, 1, 'b', 'dublicate key map - success'),
            array(array(1 => 'a', '1' => 'b'), null, '1', 'b', 'dublicate key map - fail'),

            array(array('a', 'b', 'c'), null, 1, 'b', 'map of index'),

            array(array('a' => 1), 'default', 'b', 'default', 'default value of map'),
            array(array(), 'default', 0, 'default', 'default value of empty map'),
        );
    }

    /**
     * @dataProvider getDataForTestTransform
     *
     * @param array $map
     * @param mixed $default
     * @param mixed $value
     * @param mixed $expectedResult
     * @param string $caseMessage
     */
    public function testTransform(array $map, $default, $value, $expectedResult, $caseMessage)
    {
        $transformer = new Mapper($map, $default);

        $result = $transformer->transform($value);

        $this->assertEquals($expectedResult, $result, $caseMessage);
    }
}
