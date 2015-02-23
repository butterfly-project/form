<?php

namespace Butterfly\Component\Form\Tests\Stub;

class SmsWithStaticGetters2
{
    protected static $phone;
    protected static $body;

    public function __construct($phone, $body)
    {
        self::$phone = $phone;
        self::$body  = $body;
    }

    public static function getPhone()
    {
        return self::$phone;
    }

    public static function getBody()
    {
        return self::$body;
    }
}
