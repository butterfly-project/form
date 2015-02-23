<?php

namespace Butterfly\Component\Form\Tests\Stub;

class SmsWithMagicGetAndIsset
{
    protected $properties = array();

    public function __construct($phone)
    {
        $this->properties['phone'] = $phone;
    }

    public function __get($name)
    {
        if (!array_key_exists($name, $this->properties)) {
            throw new \InvalidArgumentException(sprintf("Field %s is not exists", $name));
        }

        return $this->properties[$name];
    }

    public function __isset($name)
    {
        return array_key_exists($name, $this->properties);
    }
}
