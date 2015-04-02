<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\Email;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class EmailTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestCheck()
    {
        return array(
            array('bob@example.com', true, 'check email - success'),
            array('@example.com', false, 'check incorrect email - fail'),
            array('', false, 'check empty string - fail'),
            array('bob@пример.com', false, 'check email in other encodings - fail'),
            array(new \stdClass(), false, 'check object - fail'),
        );
    }

    /**
     * @dataProvider getDataForTestCheck
     *
     * @param mixed $value
     * @param bool $expectedResult
     * @param string $caseMessage
     */
    public function testCheck($value, $expectedResult, $caseMessage)
    {
        $validator = new Email();

        $this->assertEquals($expectedResult, $validator->check($value), $caseMessage);
    }
}
