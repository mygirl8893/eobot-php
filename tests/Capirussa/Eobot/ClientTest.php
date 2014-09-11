<?php
require_once(dirname(__FILE__) . '/../../init.php');

use Capirussa\Eobot\Client;

/**
 * Tests Capirussa\Eobot\Client
 *
 */
class ClientTest extends PHPUnit_Framework_TestCase
{
    public function testConstructWithoutParameters()
    {
        $client = new Client();

        $this->assertNull($this->getObjectAttribute($client, 'userId'));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage must be numeric
     */
    public function testConstructWithInvalidParameters()
    {
        new Client('foo');
    }

    public function testConstructWithValidParameters()
    {
        $client = new Client(1234);

        $this->assertEquals(1234, $this->getObjectAttribute($client, 'userId'));
    }

    public function testDisableSslVerification()
    {
        $client = new Client();

        $this->assertTrue($this->getObjectAttribute($client, 'validateSsl'));

        $client->disableSslVerification();

        $this->assertFalse($this->getObjectAttribute($client, 'validateSsl'));
    }

    public function testDisableSslVerificationToRequestPassthrough()
    {
        $client = new Client();

        $this->assertTrue($this->getObjectAttribute($client, 'validateSsl'));

        // getting the request is done through a protected function, we have to call that through reflection
        $reflectionClient = new ReflectionObject($client);
        $reflectionMethod = $reflectionClient->getMethod('getRequest');
        $reflectionMethod->setAccessible(true);

        $request = $reflectionMethod->invoke($client);

        $this->assertTrue($this->getObjectAttribute($request, 'validateSsl'));

        $client->disableSslVerification();

        $this->assertFalse($this->getObjectAttribute($client, 'validateSsl'));

        $request = $reflectionMethod->invoke($client);

        $this->assertFalse($this->getObjectAttribute($request, 'validateSsl'));
    }

    public function testGetCoinValueWithoutParameters()
    {
        $client = new MockClient();

        $coinValue = $client->getCoinValue();

        $this->assertEquals(458.36, $coinValue);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid coin type
     */
    public function testGetCoinValueWithInvalidCoin()
    {
        $client = new MockClient();

        $client->getCoinValue('foo');
    }

    public function testGetCoinValueWithValidCoins()
    {
        $client = new MockClient();

        $coins = array(
            Client::COIN_BITCOIN      => 458.36,
            Client::COIN_BITSHARESX   => 0.04157706,
            Client::COIN_BLACKCOIN    => 0.040105,
            Client::COIN_CURECOIN     => 0.033222,
            Client::COIN_DARKCOIN     => 1.77,
            Client::COIN_DOGECOIN     => 0.000106,
            Client::COIN_LITECOIN     => 3.62,
            Client::COIN_NAMECOIN     => 0.874518,
            Client::COIN_NAUTILUSCOIN => 0.084194,
            Client::COIN_NXT          => 0.029618,
            Client::COIN_PEERCOIN     => 0.60667,
            Client::COIN_VERTCOIN     => 0.091551,

            Client::EO_CLOUD_SCRYPT   => 0.07,
            Client::EO_CLOUD_SHA256   => 1.79,
        );

        foreach ($coins as $coin => $expectedValue) {
            $coinValue = $client->getCoinValue($coin);

            $this->assertEquals($expectedValue, $coinValue, $coin);
        }
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid currency type
     */
    public function testGetCoinValueWithInvalidCurrency()
    {
        $client = new MockClient();

        $client->getCoinValue(Client::COIN_BITCOIN, 'foo');
    }

    public function testGetCoinValueWithValidCurrency()
    {
        $client = new MockClient();

        $coinValue = $client->getCoinValue(Client::COIN_BITCOIN, Client::CURR_USD);

        $this->assertEquals(458.36, $coinValue);

        $coinValue = $client->getCoinValue(Client::COIN_BITCOIN, Client::CURR_EUR);

        $this->assertEquals(343.02837352, $coinValue);
    }

    public function testGetExchangeRateWithoutParameters()
    {
        $client = new MockClient();

        $exchangeRate = $client->getExchangeRate();

        $this->assertEquals(0.748382, $exchangeRate);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid currency type
     */
    public function testGetExchangeRateWithInvalidCurrency()
    {
        $client = new MockClient();

        $client->getExchangeRate('foo');
    }

    public function testGetExchangeRateWithValidCurrencies()
    {
        $client = new MockClient();

        $currencies = array(
            Client::CURR_AUD => 1.07221,
            Client::CURR_CAD => 1.0886,
            Client::CURR_CNY => 6.14322,
            Client::CURR_CZK => 20.889,
            Client::CURR_EUR => 0.748382,
            Client::CURR_GBP => 0.597745,
            Client::CURR_IDR => 11689.78,
            Client::CURR_JPY => 102.563,
            Client::CURR_MXN => 13.0479,
            Client::CURR_NOK => 6.15738,
            Client::CURR_PLN => 3.13386,
            Client::CURR_RUB => 36.0362,
            Client::CURR_USD => 1.0,
        );

        foreach ($currencies as $currency => $expectedRate) {
            $exchangeRate = $client->getExchangeRate($currency);

            $this->assertEquals($expectedRate, $exchangeRate, $currency);
        }
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage no user ID is known
     */
    public function testGetBalanceWithoutParameters()
    {
        $client = new MockClient();

        $client->getBalance();
    }

    public function testGetBalanceWithoutParametersAndPriorUserId()
    {
        $client = new MockClient(1234);

        $balances = $client->getBalance();

        $this->assertInternalType('array', $balances);
        $this->assertCount(15, $balances);

        $this->assertEquals(0.32751004, $balances['Total']);
        $this->assertEquals(0.00040978, $balances[Client::COIN_BITCOIN]);
        $this->assertEquals(0.0141392, $balances[Client::COIN_BITSHARESX]);
        $this->assertEquals(0.08188563, $balances[Client::COIN_BLACKCOIN]);
        $this->assertEquals(0.05292104, $balances[Client::COIN_CURECOIN]);
        $this->assertEquals(0.00085430, $balances[Client::COIN_DARKCOIN]);
        $this->assertEquals(23.78557417, $balances[Client::COIN_DOGECOIN]);
        $this->assertEquals(0.03013698, $balances[Client::COIN_LITECOIN]);
        $this->assertEquals(0.00188207, $balances[Client::COIN_NAMECOIN]);
        $this->assertEquals(0.03115914, $balances[Client::COIN_NAUTILUSCOIN]);
        $this->assertEquals(0.10494402, $balances[Client::COIN_NXT]);
        $this->assertEquals(0.00502554, $balances[Client::COIN_PEERCOIN]);
        $this->assertEquals(0.02830923, $balances[Client::COIN_VERTCOIN]);
        $this->assertEquals(0.01115809, $balances[Client::EO_CLOUD_SCRYPT]);
        $this->assertEquals(20.00019989, $balances[Client::EO_CLOUD_SHA256]);
    }

    public function testGetBalanceTotalInCurrency()
    {
        $client = new MockClient(1234);

        $balance = $client->getBalance(Client::CURR_AUD);
        $this->assertEquals(0.3511595399884, $balance);

        $balance = $client->getBalance(Client::CURR_CAD);
        $this->assertEquals(0.356527429544, $balance);

        $balance = $client->getBalance(Client::CURR_CNY);
        $this->assertEquals(2.0119662279288, $balance);

        $balance = $client->getBalance(Client::CURR_CZK);
        $this->assertEquals(6.84135722556, $balance);

        $balance = $client->getBalance(Client::CURR_EUR);
        $this->assertEquals(0.24510261875528, $balance);

        $balance = $client->getBalance(Client::CURR_GBP);
        $this->assertEquals(0.1957674888598, $balance);

        $balance = $client->getBalance(Client::CURR_IDR);
        $this->assertEquals(3828.5203153912, $balance);

        $balance = $client->getBalance(Client::CURR_JPY);
        $this->assertEquals(33.59041223252, $balance);

        $balance = $client->getBalance(Client::CURR_MXN);
        $this->assertEquals(4.273318250916, $balance);

        $balance = $client->getBalance(Client::CURR_NOK);
        $this->assertEquals(2.0166037700952, $balance);

        $balance = $client->getBalance(Client::CURR_PLN);
        $this->assertEquals(1.0263706139544, $balance);

        $balance = $client->getBalance(Client::CURR_RUB);
        $this->assertEquals(11.802217303448, $balance);

        $balance = $client->getBalance(Client::CURR_USD);
        $this->assertEquals(0.32751004, $balance);
    }

    public function testGetBalanceCoin()
    {
        $client = new MockClient(1234);

        $balance = $client->getBalance(Client::COIN_BITCOIN);
        $this->assertEquals(0.00040978, $balance);

        $balance = $client->getBalance(Client::COIN_BITSHARESX);
        $this->assertEquals(0.0141392, $balance);

        $balance = $client->getBalance(Client::COIN_BLACKCOIN);
        $this->assertEquals(0.08188563, $balance);

        $balance = $client->getBalance(Client::COIN_CURECOIN);
        $this->assertEquals(0.05292104, $balance);

        $balance = $client->getBalance(Client::COIN_DARKCOIN);
        $this->assertEquals(0.00085430, $balance);

        $balance = $client->getBalance(Client::COIN_DOGECOIN);
        $this->assertEquals(23.78557417, $balance);

        $balance = $client->getBalance(Client::COIN_LITECOIN);
        $this->assertEquals(0.03013698, $balance);

        $balance = $client->getBalance(Client::COIN_NAMECOIN);
        $this->assertEquals(0.00188207, $balance);

        $balance = $client->getBalance(Client::COIN_NAUTILUSCOIN);
        $this->assertEquals(0.03115914, $balance);

        $balance = $client->getBalance(Client::COIN_NXT);
        $this->assertEquals(0.10494402, $balance);

        $balance = $client->getBalance(Client::COIN_PEERCOIN);
        $this->assertEquals(0.00502554, $balance);

        $balance = $client->getBalance(Client::COIN_VERTCOIN);
        $this->assertEquals(0.02830923, $balance);

        $balance = $client->getBalance(Client::EO_CLOUD_SCRYPT);
        $this->assertEquals(0.01115809, $balance);

        $balance = $client->getBalance(Client::EO_CLOUD_SHA256);
        $this->assertEquals(20.00019989, $balance);
    }

    public function testGetMiningMode()
    {
        $client = new MockClient(1234);

        $miningMode = $client->getMiningMode();
        $this->assertEquals(Client::COIN_BITCOIN, $miningMode);
    }

    public function testGetSpeed()
    {
        $client = new MockClient(1234);

        $speeds = $client->getSpeed();

        $this->assertInternalType('array', $speeds);
        $this->assertCount(4, $speeds);
        $this->assertEquals(0.0, $speeds['MiningSHA-256']);
        $this->assertEquals(0.0, $speeds['MiningScrypt']);
        $this->assertEquals(20.0001998933406, $speeds['CloudSHA-256']);
        $this->assertEquals(0.0111580929310733, $speeds['CloudScrypt']);
    }

    public function testGetLastResponse()
    {
        $client = new MockClient();

        $this->assertNull($client->getLastResponse());

        $coinValue = $client->getCoinValue();

        $response = $client->getLastResponse();
        $this->assertNotNull($response);
        $this->assertInstanceof('Capirussa\\Http\\Response', $response);

        $coinValue2 = floatval(trim($response->getRawBody()));

        $this->assertEquals($coinValue2, $coinValue);
    }
}