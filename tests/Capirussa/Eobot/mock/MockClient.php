<?php
require_once(dirname(__FILE__) . '/../../../init.php');

use Capirussa\Eobot;

class MockClient extends Eobot\Client
{
    /**
     * Returns a new mock Request object
     *
     * @param string $requestMethod (Optional) Defaults to Request::METHOD_GET
     * @return MockRequest
     */
    protected function getRequest($requestMethod = Eobot\Request::METHOD_GET)
    {
        return new MockRequest($requestMethod);
    }
}