<?php
namespace GuzzleHttp\Tests\Handler;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Tests\Server;
use GuzzleHttp\Tests\ServerTrait;

/**
 * @covers \GuzzleHttp\Handler\CurlHandler
 */
class CurlHandlerTest extends \PHPUnit_Framework_TestCase
{
    use ServerTrait {
        ServerTrait::setUpBeforeClass as serverUp;
        ServerTrait::tearDownAfterClass as serverDown;
    }
    
    /**
     * @expectedException \GuzzleHttp\Exception\ConnectException
     * @expectedExceptionMessage cURL
     */
    public function testCreatesCurlErrors()
    {
        $handler = new CurlHandler();
        $request = new Request('GET', 'http://localhost:123');
        $handler($request,  array_replace_recursive(
            self::$requestOptions, 
            ['timeout' => 0.001, 'connect_timeout' => 0.001]
        ))->wait();
    }

    public function testReusesHandles()
    {
        Server::flush();
        $response = new response(200);
        Server::enqueue([$response, $response]);
        $a = new CurlHandler();
        $request = new Request('GET', Server::getUrl());
        $a($request, array_replace_recursive(self::$requestOptions, []));
        $a($request, array_replace_recursive(self::$requestOptions, []));
    }

    public function testDoesSleep()
    {
        $response = new response(200);
        Server::enqueue([$response]);
        $a = new CurlHandler();
        $request = new Request('GET', Server::getUrl());
        $s = microtime(true);
        $a($request, array_replace_recursive(self::$requestOptions, ['delay' => 0.1]))->wait();
        $this->assertGreaterThan(0.0001, microtime(true) - $s);
    }

    public function testCreatesCurlErrorsWithContext()
    {
        $handler = new CurlHandler();
        $request = new Request('GET', 'http://localhost:123');
        $called = false;
        $p = $handler($request, array_replace_recursive(
            self::$requestOptions, 
            ['timeout' => 0.001, 'connect_timeout' => 0.001]
        ))->otherwise(function (ConnectException $e) use (&$called) {
            $called = true;
            $this->assertArrayHasKey('errno', $e->getHandlerContext());
        });
        $p->wait();
        $this->assertTrue($called);
    }

    public function testUsesContentLengthWhenOverInMemorySize()
    {
        Server::flush();
        Server::enqueue([new Response()]);
        $stream = Psr7\stream_for(str_repeat('.', 1000000));
        $handler = new CurlHandler();
        $request = new Request(
            'PUT',
            Server::getUrl(),
            ['Content-Length' => 1000000],
            $stream
        );
        $handler($request, array_replace_recursive(self::$requestOptions, []))->wait();
        $received = Server::received()[0];
        $this->assertEquals(1000000, $received->getHeaderLine('Content-Length'));
        $this->assertFalse($received->hasHeader('Transfer-Encoding'));
    }
}
