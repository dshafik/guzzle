<?php
namespace GuzzleHttp\Tests;
trait CurlOptTrait {
    public function setUp()
    {
        $_SERVER['curl_test'] = true;
        unset($_SERVER['_curl']);
    }

    public function tearDown()
    {
        unset($_SERVER['_curl'], $_SERVER['curl_test']);
    }
}
