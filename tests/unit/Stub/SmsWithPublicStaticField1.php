<?php

namespace Butterfly\Component\Form\Tests\Stub;

class SmsWithPublicStaticField1
{
    public static $phone;
    public static $body;

    public function __construct($phone, $body)
    {
        self::$phone = $phone;
        self::$body  = $body;
    }
}
