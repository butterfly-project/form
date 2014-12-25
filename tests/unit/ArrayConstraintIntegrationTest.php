<?php

namespace Butterfly\Component\Form\Tests;

use Butterfly\Component\Form\ArrayConstraint;
use Butterfly\Component\Form\IConstraint;
use Butterfly\Component\Transform\String\StringMaxLength;
use Butterfly\Component\Transform\String\StringTrim;
use Butterfly\Component\Validation\String\StringLengthGreat;

class ArrayConstraintIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestFilter()
    {
        $message = array(
            'from' => 'abcde',
            'text' => 'abcde',
        );

        return array(
            array(array('caption' => ' abc ', 'body' => ' abc ', 'message' => $message), true, 0, 'abc'),
            array(array('caption' => 'abc123', 'body' => ' abc ', 'message' => $message), true, 0, 'abc1'),
            array(array('caption' => 'abc123', 'body' => '', 'message' => $message), false, 1, 'abc1'),
        );
    }

    /**
     * @dataProvider getDataForTestFilter
     *
     * @param $value
     * @param $isValid
     * @param $countMessages
     * @param $captionValue
     */
    public function testFilter($value, $isValid, $countMessages, $captionValue)
    {
        $form = $this->getForm();

        $form->filter($value);

        $this->assertEquals($isValid, $form->isValid());
        $this->assertCount($countMessages, $form->getErrorMessages());
        $this->assertEquals($captionValue, $form->get('caption')->getValue());
    }

    /**
     * @return ArrayConstraint
     */
    protected function getForm()
    {
        return ArrayConstraint::create()
            ->addScalarConstraint('caption')
                ->addTransformer(new StringTrim())
                ->addTransformer(new StringMaxLength(4))
                ->addValidator(new StringLengthGreat(0), 'incorrect caption')
            ->end()
            ->addScalarConstraint('body')
                ->addTransformer(new StringTrim())
                ->addTransformer(new StringMaxLength(10))
                ->addValidator(new StringLengthGreat(0), 'incorrect body')
            ->end()
            ->addArrayConstraint('message')
                ->addScalarConstraint('from')
                    ->addTransformer(new StringTrim())
                    ->addTransformer(new StringMaxLength(2))
                ->end()
                ->addScalarConstraint('text')
                    ->addTransformer(new StringTrim())
                    ->addTransformer(new StringMaxLength(2))
                ->end()
            ->end()
        ;
    }

    public function testParent()
    {
        $parent = $this->getArrayConstraint();

        $constraint = ArrayConstraint::create()->setParent($parent);

        $this->assertEquals($parent, $constraint->end());
        $this->assertEquals($parent, $constraint->getParent());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ArrayConstraint
     */
    protected function getArrayConstraint()
    {
        return $this->getMock('\Butterfly\Component\Form\ArrayConstraint');
    }

    public function testRemoveConstraint()
    {
        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('caption')
                ->addTransformer(new StringTrim())
                ->addTransformer(new StringMaxLength(4))
                ->addValidator(new StringLengthGreat(0), 'incorrect caption')
            ->end()
            ->addScalarConstraint('body')
                ->addTransformer(new StringTrim())
                ->addTransformer(new StringMaxLength(10))
                ->addValidator(new StringLengthGreat(0), 'incorrect body')
            ->end()
            ;

        $constraint->removeConstraint('body');

        $constraint->filter(array('caption' => ' abc  '));

        $this->assertTrue($constraint->isValid());
        $this->assertEquals('abc', $constraint->get('caption')->getValue());
    }

    public function testFirstErrorMessage()
    {
        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('caption')
                ->addTransformer(new StringTrim())
                ->addValidator(new StringLengthGreat(10), 'incorrect caption 1')
                ->addValidator(new StringLengthGreat(10), 'incorrect caption 2')
            ->end();

        $constraint->filter(array('caption' => ' abc  '));

        $this->assertEquals('incorrect caption 1', $constraint->getFirstErrorMessage());
    }

    public function testFirstErrorMessageIfNoMessages()
    {
        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('caption')
                ->addTransformer(new StringTrim())
                ->addValidator(new StringLengthGreat(0), 'incorrect caption 1')
                ->addValidator(new StringLengthGreat(0), 'incorrect caption 2')
            ->end();

        $constraint->filter(array('caption' => ' abc  '));

        $this->assertNull($constraint->getFirstErrorMessage());
    }

    public function testCount()
    {
        $constraint = $this->getForm();

        $this->assertCount(3, $constraint);
    }

    public function testIterator()
    {
        $constraint = $this->getForm();

        $keys = array();
        foreach ($constraint as $key => $value) {
            $keys[] = $key;
        }

        $this->assertEquals(array('caption', 'body', 'message'), $keys);

        $this->assertTrue(isset($constraint['caption']));
        $this->assertFalse(isset($constraint['undefined']));
        $this->assertTrue($constraint['caption'] instanceof IConstraint);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testOffsetSet()
    {
        $constraint = ArrayConstraint::create();
        $constraint['abc'] = 1;
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testOffsetUnset()
    {
        $constraint = ArrayConstraint::create();
        unset($constraint['abc']);
    }

}
