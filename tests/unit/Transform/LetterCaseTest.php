<?php

namespace Butterfly\Component\Form\Tests\Transform;

use Butterfly\Component\Form\Transform\LetterCase;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class LetterCaseTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestTransformLetterCase()
    {
        return array(
            array(LetterCase::TO_UPPER_CASE, 'abc def', 'ABC DEF', 'Upper case - 1'),
            array(LetterCase::TO_UPPER_CASE, 'Abc Def', 'ABC DEF', 'Upper case - 2'),
            array(LetterCase::TO_UPPER_CASE, 'ABC DEF', 'ABC DEF', 'Upper case - 3'),
            array(LetterCase::TO_UPPER_CASE, 'абв где', 'АБВ ГДЕ', 'Upper case - 4'),

            array(LetterCase::TO_LOWER_CASE, 'ABC DEF', 'abc def', 'Lower case - 1'),
            array(LetterCase::TO_LOWER_CASE, 'Abc Def', 'abc def', 'Lower case - 2'),
            array(LetterCase::TO_LOWER_CASE, 'abc def', 'abc def', 'Lower case - 3'),
            array(LetterCase::TO_LOWER_CASE, 'АБВ ГДЕ', 'абв где', 'Lower case - 4'),

            array(LetterCase::TO_UPPER_CASE_FIRST, 'abc def', 'Abc def', 'Upper case first - 1'),
            array(LetterCase::TO_UPPER_CASE_FIRST, 'Abc Def', 'Abc Def', 'Upper case first - 2'),
            array(LetterCase::TO_UPPER_CASE_FIRST, 'ABC DEF', 'ABC DEF', 'Upper case first - 3'),
            array(LetterCase::TO_UPPER_CASE_FIRST, 'абв где', 'Абв где', 'Upper case first - 4'),

            array(LetterCase::TO_UPPER_CASE_WORDS, 'abc def', 'Abc Def', 'Upper case words - 1'),
            array(LetterCase::TO_UPPER_CASE_WORDS, 'Abc Def', 'Abc Def', 'Upper case words - 2'),
            array(LetterCase::TO_UPPER_CASE_WORDS, 'ABC DEF', 'Abc Def', 'Upper case words - 3'),
            array(LetterCase::TO_UPPER_CASE_WORDS, 'абв где', 'Абв Где', 'Upper case words - 4'),
        );
    }

    /**
     * @dataProvider getDataForTestTransformLetterCase
     *
     * @param string $type
     * @param string $input
     * @param string $expected
     * @param string $case
     */
    public function testTransformLetterCase($type, $input, $expected, $case)
    {
        $transformer = new LetterCase($type);

        $this->assertEquals($expected, $transformer->transform($input), $case);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTransformIfIncorrectMode()
    {
        $transformer = new LetterCase('unknown mode');

        $transformer->transform('abc');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTransformIfIncorrectValue()
    {
        $transformer = new LetterCase(LetterCase::TO_UPPER_CASE);

        $transformer->transform(1234);
    }
}
