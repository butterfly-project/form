<?php

namespace Butterfly\Component\Form\Transform;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class RegReplace implements ITransformer
{
    /**
     * @var string
     */
    protected $pattern;

    /**
     * @var string
     */
    protected $replacement;

    /**
     * @var string
     */
    protected $options;

    /**
     * @param string $pattern
     * @param string $replacement
     * @param string $options
     */
    public function __construct($pattern, $replacement, $options = null)
    {
        $this->pattern     = $pattern;
        $this->replacement = $replacement;
        $this->options     = $options;
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws \InvalidArgumentException if incorrect value type
     */
    public function transform($value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException(sprintf('Value type is not string'));
        }

        return (null === $this->options)
            ? mb_ereg_replace($this->pattern, $this->replacement, $value)
            : mb_ereg_replace($this->pattern, $this->replacement, $value, $this->options);
    }
}
