<?php

namespace Butterfly\Component\Form\Transform;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class StrReplace implements ITransformer
{
    /**
     * @var array
     */
    protected $replaces;

    /**
     * @param array $replaces
     */
    public function __construct(array $replaces)
    {
        $this->replaces = $replaces;
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

        return str_replace(array_keys($this->replaces), array_values($this->replaces), $value);
    }
}
