<?php

namespace Butterfly\Component\Form\Tests\Transform;

use Butterfly\Component\Form\Transform\ToDateTime;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ToDateTimeTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestTransform()
    {
        return array(
            array('Y-m-d H:i:s', '2015-01-01 10:00:00', \DateTime::createFromFormat('Y-m-d  H:i:s', '2015-01-01 10:00:00'), 'transform to date - success'),
            array('Y-m-d', '1234', null, 'transform to date - fail'),
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
        $transformer = new ToDateTime($type);

        $result = $transformer->transform($value);

        $this->assertEquals($expectedResult, $result, $caseMessage);
    }
}
