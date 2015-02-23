<?php

namespace Butterfly\Component\Form\Tests\Stub;

class SmsWithMagicGet
{
    protected $properties = array();

    public function __construct($phone, $body)
    {
        $this->properties['phone'] = $phone;
        $this->properties['body']  = $body;
    }

    public function __get($name)
    {
        return array_key_exists($name, $this->properties) ? $this->properties[$name] : null;
    }
}
