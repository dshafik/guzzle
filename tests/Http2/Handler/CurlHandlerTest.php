<?php
namespace GuzzleHttp\Tests\Http2\Handler;

use GuzzleHttp\Tests\CurlOptTrait;
use GuzzleHttp\Tests\Handler\CurlHandlerTest as Http1CurlHandlerTest;
use GuzzleHttp\Tests\Http2\ServerTrait;

/**
 * @covers \GuzzleHttp\Handler\CurlFactory
 */
class CurlHandlerTest extends Http1CurlHandlerTest
{
    use ServerTrait, CurlOptTrait {
        ServerTrait::setUpBeforeClass as serverUp;
    }

    public static function setUpBeforeClass()
    {
        self::serverUp();

        // Due to PHP bug #70437 we have to do this here. 
        static::$requestOptions = [
            'verify' => false,
        ];
    }
}
