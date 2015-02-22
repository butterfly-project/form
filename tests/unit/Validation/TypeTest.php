<?php

namespace Butterfly\Component\Form\Tests\Validation;

use Butterfly\Component\Form\Validation\Type;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class TypeTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestCheck()
    {
        return array(
            // common types
            array(Type::TYPE_NULL, null, true, 'is type "null" - success'),
            array(Type::TYPE_NULL, 1, false, 'is type "null" - fail'),

            array(Type::TYPE_BOOL, true, true, 'is type "bool" - success'),
            array(Type::TYPE_BOOL, false, true, 'is type "bool" - success'),
            array(Type::TYPE_BOOL, 1, false, 'is type "bool" - fail'),

            array(Type::TYPE_INT, 1, true, 'is type "int" - success'),
            array(Type::TYPE_INT, 'abc', false, 'is type "int" - fail'),

            array(Type::TYPE_FLOAT, 1.1, true, 'is type "float" - success'),
            array(Type::TYPE_FLOAT, 1, false, 'is type "float" - fail'),

            array(Type::TYPE_STRING, 'abc', true, 'is type "string" - success'),
            array(Type::TYPE_STRING, 1, false, 'is type "string" - fail'),

            array(Type::TYPE_ARRAY, array(), true, 'is type "array" - success'),
            array(Type::TYPE_ARRAY, 1, false, 'is type "array" - fail'),

            array(Type::TYPE_OBJECT, new \stdClass(), true, 'is type "object" - success'),
            array(Type::TYPE_OBJECT, 1, false, 'is type "object" - fail'),

            array(Type::TYPE_RESOURCE, fopen(__FILE__, 'r'), true, 'is type "resource" - success'),
            array(Type::TYPE_RESOURCE, 1, false, 'is type "resource" - fail'),

            array(Type::TYPE_CALLABLE, function() {}, true, 'is type "callable" - success'),
            array(Type::TYPE_CALLABLE, array($this, 'getDataForTestCheck'), true, 'is type "callable" - success'),
            array(Type::TYPE_CALLABLE, 1, false, 'is type "callable" - fail'),

            // sub types
            array(Type::SUBTYPE_NUMERIC, 1, true, 'is sub type "numeric" for int - success'),
            array(Type::SUBTYPE_NUMERIC, '1', true, 'is sub type "numeric" for string-int - success'),
            array(Type::SUBTYPE_NUMERIC, 1.1, true, 'is sub type "numeric" for float - success'),
            array(Type::SUBTYPE_NUMERIC, '1.1', true, 'is sub type "numeric" for string-float - success'),
            array(Type::SUBTYPE_NUMERIC, 'abc', false, 'is sub type "numeric" for string - fail'),

            array(Type::SUBTYPE_SCALAR, 1, true, 'is sub type "scalar" for int - success'),
            array(Type::SUBTYPE_SCALAR, 1.1, true, 'is sub type "scalar" for float - success'),
            array(Type::SUBTYPE_SCALAR, 'a', true, 'is sub type "scalar" for string - success'),
            array(Type::SUBTYPE_SCALAR, true, true, 'is sub type "scalar" for bool - success'),
            array(Type::SUBTYPE_SCALAR, array(), false, 'is sub type "scalar" for array - fail'),
            array(Type::SUBTYPE_SCALAR, null, false, 'is sub type "scalar" for null - fail'),

        );
    }

    /**
     * @dataProvider getDataForTestCheck
     *
     * @param mixed $value
     * @param string $type
     * @param bool $expectedResult
     * @param string $caseMessage
     */
    public function testCheck($type, $value, $expectedResult, $caseMessage)
    {
        $validator = new Type($type);

        $this->assertEquals($expectedResult, $validator->check($value), $caseMessage);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCheckIfUndefinedType()
    {
        $validator = new Type('undefined');

        $validator->check(1);
    }
}
