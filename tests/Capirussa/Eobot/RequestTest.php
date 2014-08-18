<?php
require_once(dirname(__FILE__) . '/../../init.php');

use Capirussa\Eobot\Request;

/**
 * Tests Capirussa\Eobot\Request
 *
 */
class RequestTest extends PHPUnit_Framework_TestCase
{
    public function testConstructWithoutParameters()
    {
        $request = new Request();

        $this->assertEquals(Request::METHOD_GET, $request->getRequestMethod());
        $this->assertInternalType('array', $request->getQueryParameters());
        $this->assertCount(0, $request->getQueryParameters());
        $this->assertInternalType('array', $request->getPostParameters());
        $this->assertCount(0, $request->getPostParameters());
        $this->assertTrue($this->getObjectAttribute($request, 'validateSsl'));
        $this->assertNull($request->getLastResponse());
    }

    public function testConstructWithRequestMethod()
    {
        $request = new Request(Request::METHOD_POST);

        $this->assertEquals(Request::METHOD_POST, $request->getRequestMethod());
    }

    public function testConstructWithDisableSslVerification()
    {
        $request = new Request(null, false);

        $this->assertFalse($this->getObjectAttribute($request, 'validateSsl'));
    }

    /**
     * @expectedException PHPUnit_Framework_Error_Warning
     */
    public function testIsValidRequestMethodWithoutRequestMethod()
    {
        /** @noinspection PhpParamsInspection (this is intentional) */
        Request::isValidRequestMethod();
    }

    public function testIsValidRequestMethodWithRequestMethod()
    {
        $validRequestMethodsByConstant = array(
            Request::METHOD_GET,
            Request::METHOD_POST,
        );

        foreach ($validRequestMethodsByConstant as $requestMethod) {
            $this->assertTrue(Request::isValidRequestMethod($requestMethod));
        }

        $validRequestMethodsByValue = array(
            'GET',
            'POST',
        );

        foreach ($validRequestMethodsByValue as $requestMethod) {
            $this->assertTrue(Request::isValidRequestMethod($requestMethod));
        }

        for ($idx = 0; $idx < 1000; $idx++) {
            $requestMethod = '';

            for ($chr = 0; $chr < mt_rand(0, 10); $chr++) {
                $requestMethod .= ord(mt_rand(0, 255));
            }

            if (!in_array($requestMethod, $validRequestMethodsByConstant) && !in_array($requestMethod, $validRequestMethodsByValue)) {
                $this->assertFalse(Request::isValidRequestMethod($requestMethod));
            }
        }
    }

    /**
     * @expectedException PHPUnit_Framework_Error_Warning
     */
    public function testSetRequestMethodWithoutParameters()
    {
        $request = new Request();

        /** @noinspection PhpParamsInspection (this is intentional) */
        $request->setRequestMethod();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid request method
     */
    public function testSetRequestMethodWithInvalidRequestMethod()
    {
        $request = new Request();

        $request->setRequestMethod('invalidRequestMethod');
    }

    public function testSetRequestMethodWithValidRequestMethod()
    {
        $request = new Request();

        $this->assertEquals(Request::METHOD_GET, $request->getRequestMethod());

        $request->setRequestMethod(Request::METHOD_POST);

        $this->assertEquals(Request::METHOD_POST, $request->getRequestMethod());
    }

    /**
     * @expectedException PHPUnit_Framework_Error_Warning
     */
    public function testAddQueryParameterWithoutParameters()
    {
        $request = new Request();

        /** @noinspection PhpParamsInspection (this is intentional) */
        $request->addQueryParameter();
    }

    /**
     * @expectedException PHPUnit_Framework_Error_Warning
     */
    public function testAddQueryParameterWithInvalidParameter()
    {
        $request = new Request();

        $this->assertCount(0, $request->getQueryParameters());

        $request->addQueryParameter(array('foo'), 'bar');
    }

    public function testAddQueryParameterWithValidParameter()
    {
        $request = new Request();

        $this->assertCount(0, $request->getQueryParameters());

        $request->addQueryParameter('testParameter', 'testValue');

        $this->assertCount(1, $request->getQueryParameters());
        $this->assertArrayHasKey('testParameter', $request->getQueryParameters());
        $this->assertEquals('testValue', current($request->getQueryParameters()));
    }

    public function testAddQueryParameterWithArrayParameter()
    {
        $request = new Request();

        $this->assertCount(0, $request->getQueryParameters());

        $request->addQueryParameter('testParameter', array('testValue1', 'testValue2'));

        $this->assertCount(1, $request->getQueryParameters());
        $queryParameters = $request->getQueryParameters();

        $this->assertArrayHasKey('testParameter', $queryParameters);
        $this->assertInternalType('array', $queryParameters['testParameter']);
        $this->assertCount(2, $queryParameters['testParameter']);

        $this->assertEquals('testValue1', $queryParameters['testParameter'][0]);
        $this->assertEquals('testValue2', $queryParameters['testParameter'][1]);
    }

    /**
     * @expectedException PHPUnit_Framework_Error_Warning
     */
    public function testAddPostParameterWithoutParameters()
    {
        $request = new Request();

        /** @noinspection PhpParamsInspection (this is intentional) */
        $request->addPostParameter();
    }

    /**
     * @expectedException PHPUnit_Framework_Error_Warning
     */
    public function testAddPostParameterWithInvalidParameter()
    {
        $request = new Request();

        $this->assertCount(0, $request->getPostParameters());

        $request->addPostParameter(array('foo'), 'bar');
    }

    public function testAddPostParameterWithValidParameter()
    {
        $request = new Request();

        $this->assertCount(0, $request->getPostParameters());

        $request->addPostParameter('testParameter', 'testValue');

        $this->assertCount(1, $request->getPostParameters());
        $this->assertArrayHasKey('testParameter', $request->getPostParameters());
        $this->assertEquals('testValue', current($request->getPostParameters()));
    }

    public function testAddPostParameterWithArrayParameter()
    {
        $request = new Request();

        $this->assertCount(0, $request->getPostParameters());

        $request->addPostParameter('testParameter', array('testValue1', 'testValue2'));

        $this->assertCount(1, $request->getPostParameters());
        $queryParameters = $request->getPostParameters();

        $this->assertArrayHasKey('testParameter', $queryParameters);
        $this->assertInternalType('array', $queryParameters['testParameter']);
        $this->assertCount(2, $queryParameters['testParameter']);

        $this->assertEquals('testValue1', $queryParameters['testParameter'][0]);
        $this->assertEquals('testValue2', $queryParameters['testParameter'][1]);
    }

    public function testGetBaseUrl()
    {
        $request = new MockRequest();

        // getBaseUrl is a protected method, to test it we need to call it via reflection
        $reflectionRequest = new ReflectionObject($request);
        $reflectionMethod  = $reflectionRequest->getMethod('getBaseUrl');
        $reflectionMethod->setAccessible(true);

        $this->assertEquals('eobot://mock/', $reflectionMethod->invoke($request));
    }

    public function testBuildRequestUrl()
    {
        $request = new MockRequest();

        // buildRequestUrl is a protected method, to test it we need to call it via reflection
        $reflectionRequest = new ReflectionObject($request);
        $reflectionMethod  = $reflectionRequest->getMethod('buildRequestUrl');
        $reflectionMethod->setAccessible(true);

        // since we haven't set a sign, the request URL should be the base URL
        $this->assertEquals('eobot://mock/', $reflectionMethod->invoke($request));

        $request->addQueryParameter('testQueryParameter1', 'testValue1');

        $this->assertEquals('eobot://mock/?testQueryParameter1=testValue1', $reflectionMethod->invoke($request));

        $request->addQueryParameter('testQueryParameter2', array('testValue2', 'testValue3'));

        $this->assertEquals('eobot://mock/?testQueryParameter1=testValue1&testQueryParameter2%5B0%5D=testValue2&testQueryParameter2%5B1%5D=testValue3', $reflectionMethod->invoke($request));

        $request->addQueryParameter('testQueryParameter3', array('four' => 'testValue4', 'five' => 'testValue5'));

        $this->assertEquals('eobot://mock/?testQueryParameter1=testValue1&testQueryParameter2%5B0%5D=testValue2&testQueryParameter2%5B1%5D=testValue3&testQueryParameter3%5Bfour%5D=testValue4&testQueryParameter3%5Bfive%5D=testValue5', $reflectionMethod->invoke($request));
    }

    public function testSend()
    {
        $request = new MockRequest();

        $response = $request->send();

        $this->assertInstanceOf('Capirussa\\Eobot\\Response', $response);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('', trim($response->getRawBody()));
    }

    public function testGetLastResponse()
    {
        $request = new MockRequest();

        $this->assertNull($request->getLastResponse());

        $response = $request->send();

        $this->assertInstanceOf('Capirussa\\Eobot\\Response', $response);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('', trim($response->getRawBody()));

        $response = $request->getLastResponse();

        $this->assertInstanceOf('Capirussa\\Eobot\\Response', $response);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('', trim($response->getRawBody()));
    }
}