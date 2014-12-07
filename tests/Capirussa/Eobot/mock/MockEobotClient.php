<?php
require_once(dirname(__FILE__) . '/../../../init.php');

use Capirussa\Eobot;

class MockEobotClient extends Eobot\Client
{
    /**
     * Returns a new mock Request object
     *
     * @param string $requestMethod (Optional) Defaults to Request::METHOD_GET
     * @return MockEobotRequest
     */
    protected function getRequest($requestMethod = Eobot\Request::METHOD_GET)
    {
        return new MockEobotRequest($requestMethod);
    }
}