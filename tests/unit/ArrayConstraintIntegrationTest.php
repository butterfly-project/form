<?php

namespace Butterfly\Component\Form\Tests;

use Butterfly\Component\Form\ArrayConstraint;
use Butterfly\Component\Form\IConstraint;
use Butterfly\Component\Form\ScalarConstraint;
use Butterfly\Component\Form\Tests\Stub\SmsWithArrayAccess;
use Butterfly\Component\Form\Tests\Stub\SmsWithGetters;
use Butterfly\Component\Form\Tests\Stub\SmsWithGettersWithProtected;
use Butterfly\Component\Form\Tests\Stub\SmsWithMagicGet;
use Butterfly\Component\Form\Tests\Stub\SmsWithMagicGetAndIsset;
use Butterfly\Component\Form\Tests\Stub\SmsWithPublicField;
use Butterfly\Component\Form\Tests\Stub\SmsWithPublicStaticField1;
use Butterfly\Component\Form\Tests\Stub\SmsWithPublicStaticField2;
use Butterfly\Component\Form\Tests\Stub\SmsWithStaticGetters1;
use Butterfly\Component\Form\Tests\Stub\SmsWithStaticGetters2;
use Butterfly\Component\Form\Transform\StringLength as StringLengthTransformer;
use Butterfly\Component\Form\Transform\Trim;
use Butterfly\Component\Form\Transform\ToType;
use Butterfly\Component\Form\Validation\IsNotEmpty;
use Butterfly\Component\Form\Validation\IsNotNull;
use Butterfly\Component\Form\Validation\IsNull;
use Butterfly\Component\Form\Validation\StringLength as StringLengthValidator;
use Butterfly\Component\Form\Validation\Type;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
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
                ->addTransformer(new Trim())
                ->addTransformer(new StringLengthTransformer(4))
                ->addValidator(new IsNotEmpty(), 'incorrect caption')
            ->end()
            ->addScalarConstraint('body')
                ->addTransformer(new Trim())
                ->addTransformer(new StringLengthTransformer(10))
                ->addValidator(new IsNotEmpty(), 'incorrect body')
            ->end()
            ->addArrayConstraint('message')
                ->addScalarConstraint('from')
                    ->addTransformer(new Trim())
                    ->addTransformer(new StringLengthTransformer(2))
                ->end()
                ->addScalarConstraint('text')
                    ->addTransformer(new Trim())
                    ->addTransformer(new StringLengthTransformer(2))
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

    public function testFilterIfKeyIsNull()
    {
        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('subject')
                ->addValidator(new IsNull(), 'Incorrect subject')
            ->end()
            ->addScalarConstraint('body')
                ->addValidator(new IsNull(), 'Incorrect body')
            ->end();

        $constraint->filter(array(
            'subject' => null,
            'body' => null,
        ));

        $this->assertTrue($constraint->isValid());
        $this->assertCount(0, $constraint->getErrorMessages());
    }

    public function testHasValue()
    {
        $value = array(
            'phone' => '',
            'body'  => '',
        );

        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('phone')
                ->addValidator(new IsNotEmpty(), 'Phone can not be empty')
            ->end()
            ->addScalarConstraint('body')
                ->addValidator(new IsNotEmpty(), 'Body can not be empty')
            ->end();

        $constraint->filter($value);

        $this->assertTrue($constraint->hasValue(IConstraint::VALUE_BEFORE));
        $this->assertTrue($constraint->hasValue(IConstraint::VALUE_AFTER));
    }

    public function testHasValueIfUndefinedLabel()
    {
        $value = array(
            'phone' => '',
            'body'  => '',
        );

        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('phone')
                ->addValidator(new IsNotEmpty(), 'Phone can not be empty')
            ->end()
            ->addScalarConstraint('body')
                ->addValidator(new IsNotEmpty(), 'Body can not be empty')
            ->end();

        $constraint->filter($value);

        $this->assertFalse($constraint->hasValue('undefined'));
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
                ->addTransformer(new Trim())
                ->addTransformer(new StringLengthTransformer(4))
                ->addValidator(new IsNotEmpty(), 'incorrect caption')
            ->end()
            ->addScalarConstraint('body')
                ->addTransformer(new Trim())
                ->addTransformer(new StringLengthTransformer(10))
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
                ->addTransformer(new Trim())
                ->addValidator(new StringLengthValidator(10, StringLengthValidator::GREATER), 'incorrect caption 1')
                ->addValidator(new StringLengthValidator(10, StringLengthValidator::GREATER), 'incorrect caption 2')
            ->end();

        $constraint->filter(array('caption' => ' abc  '));

        $this->assertEquals('incorrect caption 1', $constraint->getFirstErrorMessage());
    }

    public function testFirstErrorMessageIfNoMessages()
    {
        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('caption')
                ->addTransformer(new Trim())
                ->addValidator(new IsNotEmpty(), 'incorrect caption 1')
                ->addValidator(new IsNotEmpty(), 'incorrect caption 2')
            ->end();

        $constraint->filter(array('caption' => ' abc  '));

        $this->assertNull($constraint->getFirstErrorMessage());
    }

    public function testGetStructuredErrorMessages()
    {
        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('title')
                ->addValidator(new Type(Type::TYPE_STRING), 'incorrect title')
            ->end()
            ->addScalarConstraint('caption')
                ->addValidator(new Type(Type::TYPE_STRING), 'incorrect caption 1')
                ->addValidator(new Type(Type::TYPE_STRING), 'incorrect caption 2')
            ->end()
            ->addArrayConstraint('phones')
                ->addScalarConstraint('phone1')
                    ->addValidator(new Type(Type::TYPE_INT), 'Incorrect phone1 - error 1')
                    ->addValidator(new Type(Type::TYPE_INT), 'Incorrect phone1 - error 2')
                ->end()
                ->addScalarConstraint('phone2')
                    ->addValidator(new Type(Type::TYPE_INT), 'Incorrect phone2')
                ->end()
            ->end()
        ;

        $constraint->filter(array(
            'title' => 'title',
            'caption' => 1234,
            'phones' => array(
                'phone1' => 'incorrect_value',
                'phone2' => 'incorrect_value',
            ),
        ));

        $expectedErrorMessages = array(
            'title' => array(),
            'caption' => array(
                'incorrect caption 1',
                'incorrect caption 2',
            ),
            'phones' => array(
                'phone1' => array(
                    'Incorrect phone1 - error 1',
                    'Incorrect phone1 - error 2',
                ),
                'phone2' => array(
                    'Incorrect phone2'
                ),
            ),
        );

        $this->assertEquals($expectedErrorMessages, $constraint->getStructuredErrorMessages());
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
                ->addTransformer(new StringLengthTransformer(2))
            ->end()
            ->addScalarConstraint('key2')
                ->addTransformer(new StringLengthTransformer(2))
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
                ->addValidator(new StringLengthValidator(2, StringLengthValidator::GREATER))
            ->end()
            ->addScalarConstraint('password')
                ->addValidator(new StringLengthValidator(2, StringLengthValidator::GREATER))
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
                ->addTransformer(new ToType(ToType::TYPE_STRING))
            ->end()
            ->addScalarConstraint('password')
                ->addTransformer(new ToType(ToType::TYPE_STRING))
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

    public function getDataForTestFilterIfValueIsObject()
    {
        return array(
            array(new SmsWithArrayAccess('81112223344', 'body'), true, 'filter object with array access - success'),
            array(new SmsWithArrayAccess('', ''), false, 'filter object with array access - fail'),

            array(new SmsWithPublicField('81112223344', 'body'), true, 'filter object with public fields - success'),
            array(new SmsWithPublicField('', ''), false, 'filter object with public fields - fail'),

            array(new SmsWithPublicStaticField1('81112223344', 'body'), true, 'filter object with public static fields - success'),
            array(new SmsWithPublicStaticField2('', ''), false, 'filter object with public static fields - fail'),

            array(new SmsWithGetters('81112223344', 'body'), true, 'filter object with getters - success'),
            array(new SmsWithGetters('', ''), false, 'filter object with getters - fail'),
            array(new SmsWithGettersWithProtected('81112223344', 'body'), false, 'filter object with protected getters - fail'),

            array(new SmsWithStaticGetters1('81112223344', 'body'), true, 'filter object with static getters - success'),
            array(new SmsWithStaticGetters2('', ''), false, 'filter object with static getters - fail'),

            array(new SmsWithMagicGet('81112223344', 'body'), true, 'filter object with magic get - success'),
            array(new SmsWithMagicGet('', ''), false, 'filter object with magic get - fail'),

            array(new SmsWithMagicGetAndIsset('81112223344'), false, 'filter object with magic get and isset - fail'),
        );
    }

    /**
     * @dataProvider getDataForTestFilterIfValueIsObject
     *
     * @param mixed $value
     * @param bool $expectedResult
     * @param string $caseMessage
     */
    public function testFilterIfValueIsObject($value, $expectedResult, $caseMessage)
    {
        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('phone')
                ->addValidator(new IsNotEmpty(), 'Phone can not be empty')
            ->end()
            ->addScalarConstraint('body')
                ->addValidator(new IsNotEmpty(), 'Body can not be empty')
            ->end();

        $constraint->filter($value);

        $this->assertEquals($expectedResult, $constraint->isValid(), $caseMessage);
    }

    public function testClean()
    {
        $value = array(
            'phone' => '',
            'body'  => '',
        );

        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('phone')
                ->addValidator(new IsNotEmpty(), 'Phone can not be empty')
            ->end()
            ->addScalarConstraint('body')
                ->addValidator(new IsNotEmpty(), 'Body can not be empty')
            ->end();

        $constraint->filter($value);

        $this->assertFalse($constraint->isValid());
        $this->assertCount(2, $constraint->getErrorMessages());
        $this->assertTrue($constraint->hasValue(IConstraint::VALUE_BEFORE));
        $this->assertTrue($constraint->hasValue(IConstraint::VALUE_AFTER));

        $constraint->clean();

        $this->assertTrue($constraint->isValid());
        $this->assertCount(0, $constraint->getErrorMessages());
        $this->assertFalse($constraint->hasValue(IConstraint::VALUE_BEFORE));
        $this->assertFalse($constraint->hasValue(IConstraint::VALUE_AFTER));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFilterIfIncorrectValueType()
    {
        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('phone')
                ->addValidator(new IsNotEmpty(), 'Phone can not be empty')
            ->end()
            ->addScalarConstraint('body')
                ->addValidator(new IsNotEmpty(), 'Body can not be empty')
            ->end();

        $constraint->filter(1);
    }

    public function testAddListConstraint()
    {
        $value = array(
            'fio' => 'John Smith',
            'phones' => array(
                '81112223344',
                '71112223344',
                '61112223344',
                '51112223344',
            ),
        );

        $constraint = ArrayConstraint::create()
            ->addScalarConstraint('fio')
                ->addTransformer(new ToType(ToType::TYPE_STRING))
                ->addTransformer(new Trim())
                ->addValidator(new IsNotEmpty(), 'Fio can not be empty')
            ->end()
            ->addListConstraint('phones')
                ->declareAsScalar()
                    ->addTransformer(new ToType(ToType::TYPE_STRING))
                    ->addTransformer(new Trim())
                    ->addValidator(new IsNotEmpty(), 'Phone can not be empty')
                ->end()
            ->end();

        $constraint->filter($value);

        $this->assertTrue($constraint->isValid());
    }

    public function testIsFiltered()
    {
        $constraint = ArrayConstraint::create();

        $this->assertFalse($constraint->isFiltered());

        $constraint->filter(array('foo' => 'bar'));

        $this->assertTrue($constraint->isFiltered());

        $constraint->clean();

        $this->assertFalse($constraint->isFiltered());
    }
}
