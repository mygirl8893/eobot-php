<?php
require_once(dirname(__FILE__) . '/../../../init.php');

use Capirussa\Eobot;
use Capirussa\Http;

class MockEobotRequest extends Eobot\Request
{
    /**
     * Base URL for all calls
     *
     * @type string
     */
    protected $baseUrl = 'eobot://mock/';

    /**
     * Overrides the real send method to simulate a predefined response
     *
     * @return Http\Response
     */
    public function send()
    {
        // build the request URL
        $requestUrl = $this->buildRequestUrl();

        // strip the base URL from it
        $fileName = substr($requestUrl, strlen($this->baseUrl) + 1) . '.txt';

        // load the mock response
        $simulatedResponse = $this->loadMockResponse($fileName);

        // the response should contain \r\n line endings, but Git sometimes screws that up
        if (!strpos($simulatedResponse, "\r\n")) {
            $simulatedResponse = str_replace(array("\r", "\n"), "\r\n", $simulatedResponse);
        }

        $this->response = new Http\Response($simulatedResponse);

        return $this->response;
    }

    private function loadMockResponse($filename)
    {
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR;

        // @codeCoverageIgnoreStart
        if (file_exists($path . $filename)) {
            return file_get_contents($path . $filename);
        } else {
            echo 'Mock file not found: ' . $filename . PHP_EOL;
            return file_get_contents($path . 'mock_generic_error.txt');
        }
        // @codeCoverageIgnoreEnd
    }
}