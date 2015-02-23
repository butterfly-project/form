<?php

namespace Butterfly\Component\Form\Tests\Stub;

class SmsWithPublicField
{
    public $phone;
    public $body;

    public function __construct($phone, $body)
    {
        $this->phone = $phone;
        $this->body  = $body;
    }
}
