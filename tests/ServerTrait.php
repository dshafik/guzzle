<?php
namespace GuzzleHttp\Tests;

trait ServerTrait
{
    static $requestOptions = [];
    
    public static function setUpBeforeClass()
    {
        try {
            Server::setVersion(Server::HTTP_VERSION_1_1);
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
