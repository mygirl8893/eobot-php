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

        $this->assertNull($request->getRequestUrl());
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

    public function testGetBaseUrl()
    {
        $request = new MockEobotRequest();

        // getBaseUrl is a protected method, to test it we need to call it via reflection
        $reflectionRequest = new ReflectionObject($request);
        $reflectionMethod  = $reflectionRequest->getMethod('getBaseUrl');
        $reflectionMethod->setAccessible(true);

        $this->assertEquals('eobot://mock/', $reflectionMethod->invoke($request));
    }

    public function testBuildRequestUrl()
    {
        $request = new MockEobotRequest();

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
}