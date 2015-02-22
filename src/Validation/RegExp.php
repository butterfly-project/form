<?php

namespace Butterfly\Component\Form\Validation;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class RegExp implements IValidator
{
    /**
     * @var string
     */
    protected $pattern;

    /**
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @param mixed $value
     * @return bool
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function check($value)
    {
        $result = @preg_match($this->pattern, $value);

        if (false === $result) {
            throw new \InvalidArgumentException(sprintf('Invalid regexp pattern: %s', $this->pattern));
        }

        return 1 === $result;
    }
}
