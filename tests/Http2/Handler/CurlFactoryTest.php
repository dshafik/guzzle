<?php
namespace GuzzleHttp\Tests\Http2\Handler;

use GuzzleHttp\Tests\CurlOptTrait;
use GuzzleHttp\Tests\Handler\CurlFactoryTest as Http1CurlFactoryTest;
use GuzzleHttp\Tests\Http2\ServerTrait;

/**
 * @covers \GuzzleHttp\Handler\CurlFactory
 */
class CurlFactoryTest extends Http1CurlFactoryTest
{
    use ServerTrait, CurlOptTrait;
}
