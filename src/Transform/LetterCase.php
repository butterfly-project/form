<?php

namespace Butterfly\Component\Form\Transform;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class LetterCase implements ITransformer
{
    const TO_LOWER_CASE         = 'lower_case';
    const TO_UPPER_CASE         = 'upper_case';
    const TO_UPPER_CASE_WORDS   = 'upper_case_words';
    const UCWORDS               = 'upper_case_words';
    const TO_UPPER_CASE_FIRST   = 'upper_case_first';
    const UCFIRST               = 'upper_case_first';

    /**
     * @var string
     */
    protected $mode;

    /**
     * @var null|string
     */
    protected $encoding;

    /**
     * @param string $mode
     * @param string $encoding
     */
    public function __construct($mode, $encoding = null)
    {
        $this->mode     = $mode;
        $this->encoding = $encoding;
    }

    /**
     * @param string $value
     * @return string
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function transform($value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException(sprintf('Value type is not string'));
        }

        switch ($this->mode) {
            case self::TO_LOWER_CASE:
                return null === $this->encoding ? mb_convert_case($value, MB_CASE_LOWER) : mb_convert_case($value, MB_CASE_LOWER, $this->encoding);
            case self::TO_UPPER_CASE:
                return null === $this->encoding ? mb_convert_case($value, MB_CASE_UPPER) : mb_convert_case($value, MB_CASE_UPPER, $this->encoding);
            case self::TO_UPPER_CASE_WORDS:
                return null === $this->encoding ? mb_convert_case($value, MB_CASE_TITLE) : mb_convert_case($value, MB_CASE_TITLE, $this->encoding);
            case self::TO_UPPER_CASE_FIRST:
                return null === $this->encoding
                    ? mb_convert_case(mb_substr($value, 0, 1), MB_CASE_UPPER) . mb_substr($value, 1)
                    : mb_convert_case(mb_substr($value, 0, 1, $this->encoding), MB_CASE_UPPER, $this->encoding) . mb_substr($value, 1, $this->encoding);
            default:
                throw new \InvalidArgumentException(sprintf('Incorrect transform mode'));
        }
    }
}
