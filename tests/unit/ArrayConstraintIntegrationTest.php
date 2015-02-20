<?php

namespace Butterfly\Component\Form\Tests;

use Butterfly\Component\Form\ArrayConstraint;
use Butterfly\Component\Form\IConstraint;
use Butterfly\Component\Form\ScalarConstraint;
use Butterfly\Component\Form\Transform\String\StringMaxLength;
use Butterfly\Component\Form\Transform\String\StringTrim;
use Butterfly\Component\Form\Transform\Type\ToString;
use Butterfly\Component\Form\Validation\IsNotEmpty;
use Butterfly\Component\Form\Validation\IsNotNull;
use Butterfly\Component\Form\Validation\StringLength;

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
                ->addValidator(new IsNotEmpty(), 'incorrect caption')
            ->end()
            ->addScalarConstraint('body')
                ->addTransformer(new StringTrim())
                ->addTransformer(new StringMaxLength(10))
                ->addValidator(new IsNotEmpty(), 'incorrect body')
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

    public function getDataForTestFilterIfKeyNotExists()
    {
        return array(
            array(array('subject' => 'test_subject', 'body' => 'test_body'), true, 0),
            array(array('subject' => 'test_subject'), false, 1),
            array(array('body' => 'test_body'), false, 1),
            array(array(), false, 2),
        );
    }

    /**
     * @dataProvider getDataForTestFilterIfKeyNotExists
     *
     * @param array $data
     * @param bool $expectedResult
     * @param int $expectedCountErrors
     */
    public function testFilterIfKeyNotExists(array $data, $expectedResult, $expectedCountErrors)
    {
        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('subject')
                ->addValidator(new IsNotNull(), 'Incorrect subject')
            ->end()
            ->addScalarConstraint('body')
                ->addValidator(new IsNotNull(), 'Incorrect body')
            ->end();

        $constraint->filter($data);

        $this->assertEquals($expectedResult, $constraint->isValid());
        $this->assertCount($expectedCountErrors, $constraint->getErrorMessages());
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
                ->addValidator(new IsNotEmpty(), 'incorrect caption')
            ->end()
            ->addScalarConstraint('body')
                ->addTransformer(new StringTrim())
                ->addTransformer(new StringMaxLength(10))
                ->addValidator(new IsNotEmpty(), 'incorrect body')
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
                ->addValidator(new StringLength(10, StringLength::GREATER), 'incorrect caption 1')
                ->addValidator(new StringLength(10, StringLength::GREATER), 'incorrect caption 2')
            ->end();

        $constraint->filter(array('caption' => ' abc  '));

        $this->assertEquals('incorrect caption 1', $constraint->getFirstErrorMessage());
    }

    public function testFirstErrorMessageIfNoMessages()
    {
        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('caption')
                ->addTransformer(new StringTrim())
                ->addValidator(new IsNotEmpty(), 'incorrect caption 1')
                ->addValidator(new IsNotEmpty(), 'incorrect caption 2')
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

    public function testGetValues()
    {
        $inputArr = array(
            'key1' => 'abcde',
            'key2' => 'abcde',
        );

        $expectedArr = array(
            'key1' => 'ab',
            'key2' => 'ab',
        );

        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('key1')
                ->addTransformer(new StringMaxLength(2))
            ->end()
            ->addScalarConstraint('key2')
                ->addTransformer(new StringMaxLength(2))
            ->end();

        $constraint->filter($inputArr);

        $this->assertEquals($inputArr, $constraint->getOldValue());
        $this->assertEquals($expectedArr, $constraint->getValue());
    }

    public function getDataForTestGetFormInValidator()
    {
        return array(
            array(array('username' => 'a', 'password' => 'b'), false, 3),
            array(array('username' => 'user1', 'password' => 'pass'), false, 1),
            array(array('username' => 'admin', 'password' => 'admin'), true, 0),
        );
    }

    /**
     * @dataProvider getDataForTestGetFormInValidator
     *
     * @param array $value
     * @param bool $expectedResult
     * @param int $countErrors
     */
    public function testGetFormInValidator(array $value, $expectedResult, $countErrors)
    {
        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('username')
                ->addValidator(new StringLength(2, StringLength::GREATER))
            ->end()
            ->addScalarConstraint('password')
                ->addValidator(new StringLength(2, StringLength::GREATER))
                ->addCallableValidator(function($value, ScalarConstraint $constraint) {
                    return $value == $constraint->getParent()->get('username')->getValue();
                })
            ->end()
        ;

        $constraint->filter($value);

        $this->assertEquals($expectedResult, $constraint->isValid());
        $this->assertCount($countErrors, $constraint->getErrorMessages());
    }

    public function testAddSyntheticConstraint()
    {
        $data = array(
            'username' => 'user1',
            'password' => 'pass1',
        );

        $userRepository = $this;

        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('username')
                ->addTransformer(new ToString())
            ->end()
            ->addScalarConstraint('password')
                ->addTransformer(new ToString())
            ->end()
            ->addSyntheticConstraint('user')
                ->addCallableTransformer(function(ArrayConstraint $form) use ($userRepository) {
                    $username = $form->get('username')->getValue();
                    $password = $form->get('password')->getValue();

                    return $userRepository->findUserByUsernameAndPassword($username, $password);
                })
                ->addValidator(new IsNotNull(), 'Access Denied')
            ->end()
        ;

        $constraint->filter($data);

        $this->assertTrue($constraint->isValid());
        $this->assertNull($constraint->get('user')->getOldValue());
        $this->assertEquals($this->getUser('user1', 'pass1'), $constraint->get('user')->getValue());
    }

    /**
     * @param string $username
     * @param string $password
     * @return null|\stdClass
     */
    public function findUserByUsernameAndPassword($username, $password)
    {
        if ($username != 'user1' || $password != 'pass1') {
            return null;
        }

        $user = $this->getUser($username, $password);

        return $user;
    }

    /**
     * @param string $username
     * @param string $password
     * @return \stdClass
     */
    private function getUser($username, $password)
    {
        $user           = new \stdClass();
        $user->username = $username;
        $user->password = $password;

        return $user;
    }

    public function testModidyFormAddNextKey()
    {
        $value = array(
            'key1' => '',
            'key2' => 'b',
            'flag' => 'on'
        );

        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('flag')
                ->addCallableTransformer(function($flag, ScalarConstraint $flagConstraint) {
                    if ('on' === $flag) {
                        $form = $flagConstraint->getParent();
                        $form
                            ->addScalarConstraint('key1')
                                ->addValidator(new IsNotEmpty())
                            ->end()
                            ->addScalarConstraint('key2')
                                ->addValidator(new IsNotEmpty())
                            ->end();
                    }
                })
            ->end()
        ;

        $constraint->filter($value);

        $this->assertFalse($constraint->isValid());
        $this->assertCount(1, $constraint->getErrorMessages());
    }

    public function testModidyFormRemoveNextKey()
    {
        $value = array(
            'key2' => 'b',
            'flag' => 'on'
        );

        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('flag')
                ->addCallableTransformer(function($flag, ScalarConstraint $flagConstraint) {
                    if ('on' === $flag) {
                        $form = $flagConstraint->getParent();
                        $form->removeConstraint('key1');
                    }
                })
            ->end()
            ->addScalarConstraint('key1')
                ->addValidator(new IsNotEmpty())
            ->end()
            ->addScalarConstraint('key2')
                ->addValidator(new IsNotEmpty())
            ->end()
        ;

        $constraint->filter($value);

        $this->assertTrue($constraint->isValid());
        $this->assertCount(0, $constraint->getErrorMessages());
    }

    public function testModidyFormRemovePreviousKey()
    {
        $value = array(
            'key1' => '',
            'key2' => 'b',
            'flag' => 'on'
        );

        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('key1')
                ->addValidator(new IsNotEmpty())
            ->end()
            ->addScalarConstraint('flag')
                ->addCallableTransformer(function($flag, ScalarConstraint $flagConstraint) {
                    if ('on' === $flag) {
                        $form = $flagConstraint->getParent();
                        $form->removeConstraint('key1');
                    }
                })
            ->end()
            ->addScalarConstraint('key2')
                ->addValidator(new IsNotEmpty())
            ->end()
        ;

        $constraint->filter($value);

        $this->assertTrue($constraint->isValid());
        $this->assertCount(0, $constraint->getErrorMessages());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testModidyFormRemoveCurrentKey()
    {
        $value = array(
            'flag' => 'on'
        );

        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('flag')
                ->addCallableTransformer(function($flag, ScalarConstraint $flagConstraint) {
                    $form = $flagConstraint->getParent();
                    $form->removeConstraint('flag');
                })
            ->end()
        ;

        $constraint->filter($value);
    }
}
