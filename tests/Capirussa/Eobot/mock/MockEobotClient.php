<?php
require_once(dirname(__FILE__) . '/../../../init.php');

use Capirussa\Eobot;

class MockEobotClient extends Eobot\Client
{
    /**
     * Base URL for all calls
     *
     * @type string
     */
    protected $baseUrl = 'eobot://mock/';

    /**
     * Returns a new mock Request object
     *
     * @return MockEobotBrowser
     */
    protected function getRequest()
    {
        return new MockEobotBrowser($this->baseUrl);
    }
}