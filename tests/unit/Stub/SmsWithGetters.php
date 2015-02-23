<?php

namespace Butterfly\Component\Form\Tests\Stub;

class SmsWithGetters
{
    protected $phone;
    protected $body;

    public function __construct($phone, $body)
    {
        $this->phone = $phone;
        $this->body  = $body;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getBody()
    {
        return $this->body;
    }
}
