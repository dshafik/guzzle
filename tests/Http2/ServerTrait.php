<?php
namespace GuzzleHttp\Tests\Http2;

use GuzzleHttp\Tests\Server;

trait ServerTrait
{
    public static function setUpBeforeClass()
    {
        try {
            Server::setVersion(Server::HTTP_VERSION_2);
            Server::start();
        } catch (\Exception $e) {
            Server::stop();
        }
    }

    public static function tearDownAfterClass()
    {
        Server::stop();
    }
}
