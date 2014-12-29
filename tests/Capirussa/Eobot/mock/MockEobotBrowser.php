<?php
require_once(dirname(__FILE__) . '/../../../init.php');

class MockEobotBrowser extends \Buzz\Browser
{
    public $baseUrl;

    public function __construct($baseUrl) {
        $this->baseUrl = $baseUrl;

        parent::__construct();
    }

    public function get($url, $headers = array())
    {
        $fileName = substr($url, strlen($this->baseUrl) + 1) . '.txt';
        return $this->loadMockResponse($fileName);
    }

    public function post($url, $headers = array(), $content = '')
    {
        $fileName = substr($url, strlen($this->baseUrl) + 1) . $content . '.txt';
        return $this->loadMockResponse($fileName);
    }

    private function loadMockResponse($filename)
    {
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR;

        // @codeCoverageIgnoreStart
        if (file_exists($path . $filename)) {
            $simulatedResponse = file_get_contents($path . $filename);
        } else {
            echo 'Mock file not found: ' . $filename . PHP_EOL;
            $simulatedResponse = file_get_contents($path . 'mock_generic_error.txt');
        }
        // @codeCoverageIgnoreEnd

        // the response should contain \r\n line endings, but Git sometimes screws that up
        if (!strpos($simulatedResponse, "\r\n")) {
            $simulatedResponse = str_replace(array("\r", "\n"), "\r\n", $simulatedResponse);
        }

        $response = new \Buzz\Message\Response();

        list($headers, $content) = $this->parseResponse($simulatedResponse);

        $response->setHeaders($headers);
        $response->setContent($content);

        return $response;
    }

    private function parseResponse($apiResponse)
    {
        // parse the API response into sections
        $responseSections = explode("\r\n\r\n", $apiResponse);

        // the first section contains the headers
        $headerSection = array_shift($responseSections);

        $rawHeaders = array();
        $rawBody = implode("\r\n\r\n", $responseSections);

        $headerLines = explode("\r\n", $headerSection);
        foreach ($headerLines as $responseLine) {
            if (strtoupper(substr($responseLine, 0, 5)) == 'HTTP/') {
                continue;
            } else {
                $header = explode(':', $responseLine, 2);

                $rawHeaders[trim($header[0])] = trim($header[1]);
            }
        }

        return array($rawHeaders, $rawBody);
    }
}