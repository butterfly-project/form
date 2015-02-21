<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\Instance;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class InstanceTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestCheck()
    {
        return array(
            array('\Butterfly\Component\Form\Validation\Instance', new Instance(''), true, 'check instance of class - success'),
            array('\Butterfly\Component\Form\Validation\Instance', new \stdClass(), false, 'check instance of class - fail'),

            array('\Butterfly\Component\Form\Validation\IValidator', new Instance(''), true, 'check instance of interface - success'),
            array('\Butterfly\Component\Form\Validation\IValidator', new \stdClass(), false, 'check instance of interface - fail'),
        );
    }

    /**
     * @dataProvider getDataForTestCheck
     *
     * @param mixed $value
     * @param string $name
     * @param bool $expectedResult
     * @param string $caseMessage
     */
    public function testCheck($name, $value, $expectedResult, $caseMessage)
    {
        $validator = new Instance($name);

        $this->assertEquals($expectedResult, $validator->check($value), $caseMessage);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCheckIfIncorrectValue()
    {
        $validator = new Instance('\stdClass');

        $validator->check(1);
    }
}
