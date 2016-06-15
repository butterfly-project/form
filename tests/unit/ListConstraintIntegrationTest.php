<?php

namespace Butterfly\Component\Form\Tests;

use Butterfly\Component\Form\ArrayConstraint;
use Butterfly\Component\Form\IConstraint;
use Butterfly\Component\Form\ListConstraint;
use Butterfly\Component\Form\ScalarConstraint;
use Butterfly\Component\Form\Transform\Trim;
use Butterfly\Component\Form\Validation\IsNotEmpty;
use Butterfly\Component\Form\Validation\StringLength;
use Butterfly\Component\Form\Validation\Type;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ListConstraintIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestFilterIfScalarConstraint()
    {
        return array(
            array(array(' abc ', 'def'), true, 0, 'filter list - success'),
            array(array(), true, 0, 'filter empty list - success'),
            array(array('abc', ''), false, 1, 'filter list - fail'),
        );
    }

    /**
     * @dataProvider getDataForTestFilterIfScalarConstraint
     *
     * @param array $value
     * @param bool $expectedIsValue
     * @param int $expectedCountErrors
     * @param string $caseMessage
     */
    public function testFilterIfScalarConstraint(array $value, $expectedIsValue, $expectedCountErrors, $caseMessage)
    {
        $constraint = ListConstraint::create()
            ->declareAsScalar()
                ->addTransformer(new Trim())
                ->addValidator(new IsNotEmpty(), 'Value can not be empty')
            ->end();

        $constraint->filter($value);

        $this->assertEquals($expectedIsValue, $constraint->isValid(), $caseMessage);
        $this->assertCount($expectedCountErrors, $constraint->getErrorMessages(), $caseMessage);
    }

    public function getDataForTestFilterIfArrayConstraint()
    {
        return array(
            array(array(
                array('phone' => '81112223344', 'text' => 'test text 1'),
                array('phone' => '71112223344', 'text' => 'test text 2'),
            ), true, 0, 'filter list - success'),

            array(array(
                array('phone' => '81112223344', 'text' => 'test text 1'),
                array('phone' => '71112223344', 'text' => ''),
            ), false, 1, 'filter list - fail'),
        );
    }

    /**
     * @dataProvider getDataForTestFilterIfArrayConstraint
     *
     * @param array $value
     * @param bool $expectedIsValue
     * @param int $expectedCountErrors
     * @param string $caseMessage
     */
    public function testFilterIfArrayConstraint(array $value, $expectedIsValue, $expectedCountErrors, $caseMessage)
    {
        $constraint = ListConstraint::create()
            ->declareAsArray()
                ->addScalarConstraint('phone')
                    ->addValidator(new IsNotEmpty(), 'Phone can not be empty')
                ->end()
                ->addScalarConstraint('text')
                    ->addValidator(new IsNotEmpty(), 'Text can not be empty')
                ->end()
            ->end();

        $constraint->filter($value);

        $this->assertEquals($expectedIsValue, $constraint->isValid(), $caseMessage);
        $this->assertCount($expectedCountErrors, $constraint->getErrorMessages(), $caseMessage);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFilterIfIncorrectValue()
    {
        $constraint = ListConstraint::create()
            ->declareAsScalar()
                ->addTransformer(new Trim())
                ->addValidator(new IsNotEmpty(), 'Value can not be empty')
            ->end();

        $constraint->filter(1);
    }

    public function testGetFirstErrorMessage()
    {
        $value = array(
            'abc',
            1,
            false,
        );

        $constraint = ListConstraint::create()
            ->declareAsScalar()
                ->addValidator(new Type(Type::TYPE_STRING), 'The value must be a string')
            ->end();

        $constraint->filter($value);

        $this->assertEquals('The value must be a string', $constraint->getFirstErrorMessage());
    }

    public function testGetFirstErrorMessageIfValidValue()
    {
        $value = array(
            'abc',
            'def',
        );

        $constraint = ListConstraint::create()
            ->declareAsScalar()
                ->addValidator(new Type(Type::TYPE_STRING), 'The value must be a string')
            ->end();

        $constraint->filter($value);

        $this->assertNull($constraint->getFirstErrorMessage());
    }

    public function testGetErrorMessages()
    {
        $value = array(
            'abc',
            1,
            false,
        );

        $constraint = ListConstraint::create()
            ->declareAsScalar()
                ->addValidator(new Type(Type::TYPE_STRING), 'The value must be a string')
            ->end();

        $constraint->filter($value);

        $this->assertCount(2, $constraint->getErrorMessages());
    }

    public function testGetParent()
    {
        $parent = new ArrayConstraint();

        $constraint = ListConstraint::create();
        $constraint->setParent($parent);

        $this->assertEquals($parent, $constraint->getParent());
    }

    public function testEnd()
    {
        $parent = new ArrayConstraint();

        $constraint = ListConstraint::create();
        $constraint->setParent($parent);

        $this->assertEquals($parent, $constraint->end());
    }

    public function testGetValue()
    {
        $value = array(
            ' abc ',
            ' def ',
        );

        $constraint = ListConstraint::create()
            ->declareAsScalar()
                ->addTransformer(new Trim())
            ->end();

        $constraint->filter($value);

        $this->assertEquals(array('abc', 'def'), $constraint->getValue());
    }

    public function testOldGetValue()
    {
        $value = array(
            ' abc ',
            ' def ',
        );

        $constraint = ListConstraint::create()
            ->declareAsScalar()
                ->addTransformer(new Trim())
            ->end();

        $constraint->filter($value);

        $this->assertEquals($value, $constraint->getOldValue());
    }

    public function testHasValue()
    {
        $value = array(
            ' abc ',
            ' def ',
        );

        $constraint = ListConstraint::create()
            ->declareAsScalar()
            ->addTransformer(new Trim())
            ->end();

        $constraint->filter($value);

        $this->assertTrue($constraint->hasValue(IConstraint::VALUE_BEFORE));
        $this->assertTrue($constraint->hasValue(IConstraint::VALUE_AFTER));
    }

    public function testHasValueIfUndefinedLabel()
    {
        $value = array(
            ' abc ',
            ' def ',
        );

        $constraint = ListConstraint::create()
            ->declareAsScalar()
                ->addTransformer(new Trim())
            ->end();

        $constraint->filter($value);

        $this->assertFalse($constraint->hasValue('undefined'));
    }

    public function testClean()
    {
        $value = array(
            ' abc ',
            '',
        );

        $constraint = ListConstraint::create()
            ->declareAsScalar()
                ->addTransformer(new Trim())
                ->addValidator(new IsNotEmpty(), 'Value can not be empty')
            ->end();

        $constraint->filter($value);

        $this->assertFalse($constraint->isValid());
        $this->assertCount(1, $constraint->getErrorMessages());

        $constraint->clean();

        $this->assertTrue($constraint->isValid());
        $this->assertCount(0, $constraint->getErrorMessages());
    }

    public function testGet()
    {
        $value = array(
            ' abc ',
            ' def ',
        );

        $constraint = ListConstraint::create()
            ->declareAsScalar()
                ->addTransformer(new Trim())
                ->addValidator(new IsNotEmpty(), 'Value can not be empty')
            ->end();

        $constraint->filter($value);

        $this->assertEquals('abc', $constraint->get(0)->getValue());
        $this->assertEquals(' abc ', $constraint->get(0)->getOldValue());

        $this->assertEquals('def', $constraint->get(1)->getValue());
        $this->assertEquals(' def ', $constraint->get(1)->getOldValue());
    }

    public function testFilterAssociativeArray()
    {
        $value = array(
            'value1' => ' abc ',
            'value2' => ' def ',
        );

        $constraint = ListConstraint::create()
            ->declareAsScalar()
                ->addTransformer(new Trim())
                ->addValidator(new IsNotEmpty(), 'Value can not be empty')
            ->end();

        $constraint->filter($value);

        $this->assertEquals('abc', $constraint->get('value1')->getValue());
        $this->assertEquals(' abc ', $constraint->get('value1')->getOldValue());

        $this->assertEquals('def', $constraint->get('value2')->getValue());
        $this->assertEquals(' def ', $constraint->get('value2')->getOldValue());
    }
    
    public function testArrayAccess()
    {
        $value = array(
            ' abc ',
            'value2' => ' def ',
        );

        $constraint = ListConstraint::create()
            ->declareAsScalar()
                ->addTransformer(new Trim())
                ->addValidator(new IsNotEmpty(), 'Value can not be empty')
            ->end();

        $constraint->filter($value);

        /** @var IConstraint[] $constraint */
        $this->assertCount(2, $constraint);

        $values = array();
        foreach ($constraint as $itemConstraint) {
            $values[] = $itemConstraint->getValue();
        }

        $this->assertEquals(array('abc', 'def'), $values);

        $this->assertTrue(isset($constraint['value2']));
        $this->assertEquals('abc', $constraint[0]->getValue());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testArrayAccessSet()
    {
        $constraint = ListConstraint::create();
        $constraint['abc'] = 1;
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testArrayAccessUnset()
    {
        $constraint = ListConstraint::create();
        unset($constraint['abc']);
    }

    public function testClone()
    {
        $constraint = ListConstraint::create()
            ->declareAsScalar()
                ->addValidator(new IsNotEmpty(), 'Cannot be empty')
            ->end();

        $constraint2 = clone $constraint;

        $constraint->filter(array('abc', ''));

        $this->assertNotEquals($constraint, $constraint2);
    }

    public function testGetStructuredErrorMessages()
    {
        $constraint = ListConstraint::create()
            ->declareAsScalar()
                ->addValidator(new StringLength(3, StringLength::GREATER), 'incorrect value 1')
                ->addValidator(new StringLength(5, StringLength::GREATER), 'incorrect value 2')
            ->end();

        $constraint->filter(array('ab', 'cd', 'ef'));

        $expectedErrorMessages = array(
            array(
                'incorrect value 1',
                'incorrect value 2',
            ),
            array(
                'incorrect value 1',
                'incorrect value 2',
            ),
            array(
                'incorrect value 1',
                'incorrect value 2',
            ),
        );
        $this->assertEquals($expectedErrorMessages, $constraint->getStructuredErrorMessages());
    }
}
