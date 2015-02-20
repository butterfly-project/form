<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\IsNotNull;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class IsNotNullTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestCheck()
    {
        return array(
            array('asdf', true),
            array(123, true),
            array(null, false),
        );
    }

    /**
     * @dataProvider getDataForTestCheck
     *
     * @param mixed $value
     * @param bool $expectedResult
     */
    public function testCheck($value, $expectedResult)
    {
        $validator = new IsNotNull();

        $this->assertEquals($expectedResult, $validator->check($value));
    }
}
