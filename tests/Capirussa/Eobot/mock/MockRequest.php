<?php
require_once(dirname(__FILE__) . '/../../../init.php');

use Capirussa\Eobot;
use Capirussa\Http;

class MockRequest extends Eobot\Request
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

        // read file contents
        switch ($requestUrl) {
            default:
                $simulatedResponse = $this->loadMockResponse('mock_generic_error.txt');
                break;

            case 'eobot://mock/?coin=AUD':
                $simulatedResponse = $this->loadMockResponse('coin=AUD.txt');
                break;

            case 'eobot://mock/?coin=BC':
                $simulatedResponse = $this->loadMockResponse('coin=BC.txt');
                break;

            case 'eobot://mock/?coin=BTC':
                $simulatedResponse = $this->loadMockResponse('coin=BTC.txt');
                break;

            case 'eobot://mock/?coin=CAD':
                $simulatedResponse = $this->loadMockResponse('coin=CAD.txt');
                break;

            case 'eobot://mock/?coin=CNY':
                $simulatedResponse = $this->loadMockResponse('coin=CNY.txt');
                break;

            case 'eobot://mock/?coin=BTSX':
                $simulatedResponse = $this->loadMockResponse('coin=BTSX.txt');
                break;

            case 'eobot://mock/?coin=CURE':
                $simulatedResponse = $this->loadMockResponse('coin=CURE.txt');
                break;

            case 'eobot://mock/?coin=CZK':
                $simulatedResponse = $this->loadMockResponse('coin=CZK.txt');
                break;

            case 'eobot://mock/?coin=DOGE':
                $simulatedResponse = $this->loadMockResponse('coin=DOGE.txt');
                break;

            case 'eobot://mock/?coin=DRK':
                $simulatedResponse = $this->loadMockResponse('coin=DRK.txt');
                break;

            case 'eobot://mock/?coin=EUR':
                $simulatedResponse = $this->loadMockResponse('coin=EUR.txt');
                break;

            case 'eobot://mock/?coin=GBP':
                $simulatedResponse = $this->loadMockResponse('coin=GBP.txt');
                break;

            case 'eobot://mock/?coin=GHS':
                $simulatedResponse = $this->loadMockResponse('coin=GHS.txt');
                break;

            case 'eobot://mock/?coin=IDR':
                $simulatedResponse = $this->loadMockResponse('coin=IDR.txt');
                break;

            case 'eobot://mock/?coin=JPY':
                $simulatedResponse = $this->loadMockResponse('coin=JPY.txt');
                break;

            case 'eobot://mock/?coin=LTC':
                $simulatedResponse = $this->loadMockResponse('coin=LTC.txt');
                break;

            case 'eobot://mock/?coin=MXN':
                $simulatedResponse = $this->loadMockResponse('coin=MXN.txt');
                break;

            case 'eobot://mock/?coin=NAUT':
                $simulatedResponse = $this->loadMockResponse('coin=NAUT.txt');
                break;

            case 'eobot://mock/?coin=NMC':
                $simulatedResponse = $this->loadMockResponse('coin=NMC.txt');
                break;

            case 'eobot://mock/?coin=NOK':
                $simulatedResponse = $this->loadMockResponse('coin=NOK.txt');
                break;

            case 'eobot://mock/?coin=NXT':
                $simulatedResponse = $this->loadMockResponse('coin=NXT.txt');
                break;

            case 'eobot://mock/?coin=PLN':
                $simulatedResponse = $this->loadMockResponse('coin=PLN.txt');
                break;

            case 'eobot://mock/?coin=PPC':
                $simulatedResponse = $this->loadMockResponse('coin=PPC.txt');
                break;

            case 'eobot://mock/?coin=RUB':
                $simulatedResponse = $this->loadMockResponse('coin=RUB.txt');
                break;

            case 'eobot://mock/?coin=SCRYPT':
                $simulatedResponse = $this->loadMockResponse('coin=SCRYPT.txt');
                break;

            case 'eobot://mock/?coin=VTC':
                $simulatedResponse = $this->loadMockResponse('coin=VTC.txt');
                break;

            case 'eobot://mock/?idmining=1234':
                $simulatedResponse = $this->loadMockResponse('idmining=1234.txt');
                break;

            case 'eobot://mock/?idspeed=1234':
                $simulatedResponse = $this->loadMockResponse('idspeed=1234.txt');
                break;

            case 'eobot://mock/?total=1234':
                $simulatedResponse = $this->loadMockResponse('total=1234.txt');
                break;
        }

        // the response should contain \r\n line endings, but Git sometimes screws that up
        if (!strpos($simulatedResponse, "\r\n")) {
            $simulatedResponse = str_replace(array("\r", "\n"), "\r\n", $simulatedResponse);
        }

        $this->response = new Http\Response($simulatedResponse);

        return $this->response;
    }

    private function loadMockResponse($filename)
    {
        return file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . $filename);
    }
}