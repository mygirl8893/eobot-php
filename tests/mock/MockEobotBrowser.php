<?php
require_once(dirname(__FILE__) . '/../init.php');

class MockEobotBrowser extends \Buzz\Browser
{
    public $baseUrl;

    public function __construct($baseUrl) {
        $this->baseUrl = $baseUrl;

        parent::__construct();
    }

    public function get($url, $headers = array())
    {
        $parameters = substr($url, strlen($this->baseUrl) + 1);

        return $this->loadMockResponse($parameters);
    }

    public function post($url, $headers = array(), $content = '')
    {
        $parameters = substr($url, strlen($this->baseUrl) + 1) . $content;

        return $this->loadMockResponse($parameters);
    }

    private function loadMockResponse($parameters)
    {
        $parametersArray = array();
        parse_str($parameters, $parametersArray);

        $simulatedResponse = MockEobotResponder::getResponse($parametersArray);

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