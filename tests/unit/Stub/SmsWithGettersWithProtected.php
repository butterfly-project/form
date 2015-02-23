<?php

namespace Butterfly\Component\Form\Tests\Stub;

class SmsWithGettersWithProtected
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

    protected function getBody()
    {
        return $this->body;
    }
}
