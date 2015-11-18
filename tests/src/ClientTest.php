<?php
require_once(dirname(__FILE__) . '/../init.php');

use RickDenHaan\Eobot\Client;

/**
 * Tests RickDenHaan\Eobot\Client
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

        /* @type $request \Buzz\Browser */

        $this->assertTrue($request->getClient()
                                  ->getVerifyPeer());

        $client->disableSslVerification();

        $this->assertFalse($this->getObjectAttribute($client, 'validateSsl'));

        $request = $reflectionMethod->invoke($client);

        $this->assertFalse($request->getClient()
                                   ->getVerifyPeer());
    }

    public function testSetTimeout()
    {
        $client = new Client();

        $this->assertEquals(30, $this->getObjectAttribute($client, 'timeout'));

        $client->setTimeout(10);

        $this->assertEquals(10, $this->getObjectAttribute($client, 'timeout'));
    }

    public function testSetTimeoutToRequestPassthrough()
    {
        $client = new Client();

        $this->assertEquals(30, $this->getObjectAttribute($client, 'timeout'));

        // getting the request is done through a protected function, we have to call that through reflection
        $reflectionClient = new ReflectionObject($client);
        $reflectionMethod = $reflectionClient->getMethod('getRequest');
        $reflectionMethod->setAccessible(true);

        $request = $reflectionMethod->invoke($client);

        /* @type $request \Buzz\Browser */

        $this->assertEquals(30, $request->getClient()
                                        ->getTimeout());

        $client->setTimeout(10);

        $this->assertEquals(10, $this->getObjectAttribute($client, 'timeout'));

        $request = $reflectionMethod->invoke($client);

        $this->assertEquals(10, $request->getClient()
                                        ->getTimeout());
    }

    public function testGetCoinValueWithoutParameters()
    {
        $client = new MockEobotClient();

        $coinValue = $client->getCoinValue();

        $this->assertEquals(239.51, $coinValue);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid coin type
     */
    public function testGetCoinValueWithInvalidCoin()
    {
        $client = new MockEobotClient();

        $client->getCoinValue('foo');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Invalid API response
     */
    public function testGetCoinValueWithInvalidApiResponse()
    {
        $client = new MockEobotClient();

        $client->getCoinValue(Client::COIN_DASH);
    }

    public function testGetCoinValueWithValidCoins()
    {
        $client = new MockEobotClient();

        $coins = array(
            Client::COIN_BITCOIN      => 239.51,
            Client::COIN_BITSHARES    => 0.00444021,
            Client::COIN_BLACKCOIN    => 0.02531201,
            Client::COIN_BYTECOIN     => 0.00005984,
            Client::COIN_COUNTERPARTY => 0.92357389,
            Client::COIN_CURECOIN     => 0.01073379,
            Client::COIN_DOGECOIN     => 0.00012923,
            Client::COIN_ETHERIUM     => 1.15773517,
            Client::COIN_GRIDCOIN     => 0.0013353,
            Client::COIN_LITECOIN     => 2.9510757,
            Client::COIN_LUMENS       => 0.00220185,
            Client::COIN_MAIDSAFECOIN => 0.02252202,
            Client::COIN_MONERO       => 0.52178501,
            Client::COIN_NAMECOIN     => 0.41365257,
            Client::COIN_NXT          => 0.00852383,
            Client::COIN_PEERCOIN     => 0.39906844,
            Client::COIN_REDDCOIN     => 0.00001207,
            Client::COIN_RIPPLE       => 0.00766181,
            Client::COIN_STORJCOIN_X  => 0.01743132,
            Client::EO_CLOUD_FOLDING  => 0.05,
            Client::EO_CLOUD_SHA256_2 => 0.55,
            Client::EO_CLOUD_SHA256_3 => 0.005,
            Client::RENTAL_FOLDING    => 0.00013699,
            Client::RENTAL_SHA256     => 0.00101718,
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
        $client = new MockEobotClient();

        $client->getCoinValue(Client::COIN_BITCOIN, 'foo');
    }

    public function testGetCoinValueWithValidCurrency()
    {
        $client = new MockEobotClient();

        $coinValue = $client->getCoinValue(Client::COIN_BITCOIN, Client::CURRENCY_US_DOLLAR);

        $this->assertEquals(239.51, $coinValue);

        $coinValue = $client->getCoinValue(Client::COIN_BITCOIN, Client::CURRENCY_EURO);

        $this->assertEquals(220.3492, $coinValue);
    }

    public function testGetExchangeRateWithoutParameters()
    {
        $client = new MockEobotClient();

        $exchangeRate = $client->getExchangeRate();

        $this->assertEquals(0.92, $exchangeRate);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid currency type
     */
    public function testGetExchangeRateWithInvalidCurrency()
    {
        $client = new MockEobotClient();

        $client->getExchangeRate('foo');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Invalid API response
     */
    public function testGetExchangeRateWithInvalidApiResponse()
    {
        $client = new MockEobotClient();

        $client->getExchangeRate(Client::CURRENCY_AUSTRALIAN_DOLLAR);
    }

    public function testGetExchangeRateWithValidCurrencies()
    {
        $client = new MockEobotClient();

        $currencies = array(
            Client::CURRENCY_BRITISH_POUND         => 0.67,
            Client::CURRENCY_CANADIAN_DOLLAR       => 1.26,
            Client::CURRENCY_CHINESE_YUAN_RENMINBI => 6.23,
            Client::CURRENCY_CZECH_KORUNA          => 25.24,
            Client::CURRENCY_DANISH_KRONE          => 6.88,
            Client::CURRENCY_EURO                  => 0.92,
            Client::CURRENCY_HONG_KONG_DOLLAR      => 7.75,
            Client::CURRENCY_INDIAN_RUPEE          => 62.22,
            Client::CURRENCY_INDONESIAN_RUPIAH     => 12996.0,
            Client::CURRENCY_ISRAELI_SHEKEL        => 4.04,
            Client::CURRENCY_JAPANESE_YEN          => 120.3,
            Client::CURRENCY_MALAYSIAN_RINGGIT     => 3.68,
            Client::CURRENCY_MEXICAN_PESO          => 15.13,
            Client::CURRENCY_NORWEGIAN_KRONE       => 8.2,
            Client::CURRENCY_POLISH_ZLOTY          => 3.82,
            Client::CURRENCY_ROMANIAN_NEW_LEU      => 4.1,
            Client::CURRENCY_RUSSIAN_RUBLE         => 59.36,
            Client::CURRENCY_SERBIAN_DINAR         => 110.89,
            Client::CURRENCY_SWISS_FRANC           => 0.99,
            Client::CURRENCY_UKRAINIAN_HRYVNIA     => 23.63,
            Client::CURRENCY_US_DOLLAR             => 1.0,
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
        $client = new MockEobotClient();

        $client->getBalance();
    }

    public function testGetBalanceWithoutParametersAndPriorUserId()
    {
        $client = new MockEobotClient(1234);

        $balances = $client->getBalance();

        $this->assertInternalType('array', $balances);
        $this->assertCount(23, $balances);

        $this->assertEquals(0.32751004, $balances['Total']);
        $this->assertEquals(0.00040978, $balances[Client::COIN_BITCOIN]);
        $this->assertEquals(0.0141392, $balances[Client::COIN_BITSHARES]);
        $this->assertEquals(0.08188563, $balances[Client::COIN_BLACKCOIN]);
        $this->assertEquals(0.01537264, $balances[Client::COIN_BYTECOIN]);
        $this->assertEquals(0.05292104, $balances[Client::COIN_CURECOIN]);
        $this->assertEquals(23.78557417, $balances[Client::COIN_DOGECOIN]);
        $this->assertEquals(15.2361832, $balances[Client::COIN_ETHERIUM]);
        $this->assertEquals(0.15432515, $balances[Client::COIN_GRIDCOIN]);
        $this->assertEquals(0.03013698, $balances[Client::COIN_LITECOIN]);
        $this->assertEquals(0.06467642, $balances[Client::COIN_LUMENS]);
        $this->assertEquals(0.00188207, $balances[Client::COIN_NAMECOIN]);
        $this->assertEquals(0.03115914, $balances[Client::COIN_RIPPLE]);
        $this->assertEquals(0.10494402, $balances[Client::COIN_NXT]);
        $this->assertEquals(0.00502554, $balances[Client::COIN_PEERCOIN]);
        $this->assertEquals(0.02830923, $balances[Client::COIN_REDDCOIN]);
        $this->assertEquals(0.02748126, $balances[Client::COIN_MAIDSAFECOIN]);
        $this->assertEquals(0.02639744, $balances[Client::COIN_STORJCOIN_X]);
        $this->assertEquals(0.03641862, $balances[Client::COIN_MONERO]);
        $this->assertEquals(0.00636984, $balances[Client::COIN_COUNTERPARTY]);
        $this->assertEquals(2.16726154, $balances[Client::EO_CLOUD_FOLDING]);
        $this->assertEquals(15.42138465, $balances[Client::EO_CLOUD_SHA256_2]);
        $this->assertEquals(20.00019989, $balances[Client::EO_CLOUD_SHA256_3]);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage not a valid coin type
     */
    public function testGetBalanceWithInvalidCoin()
    {
        $client = new MockEobotClient();

        $client->getBalance('foo');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage must be numeric
     */
    public function testGetBalanceWithInvalidUserId()
    {
        $client = new MockEobotClient();

        $client->getBalance(null, 'foo');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Invalid API response
     */
    public function testGetBalanceWithInvalidApiResponse()
    {
        $client = new MockEobotClient();

        $client->getBalance(null, 2345);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage not in the balance sheet
     */
    public function testGetBalanceForMissingCoin()
    {
        $client = new MockEobotClient(1234);

        $client->getBalance(Client::COIN_DASH);
    }

    public function testGetBalanceTotalInCurrency()
    {
        $client = new MockEobotClient(1234);

        $balance = $client->getBalance(Client::CURRENCY_BRITISH_POUND);
        $this->assertEquals(0.2194317268, $balance);

        $balance = $client->getBalance(Client::CURRENCY_CANADIAN_DOLLAR);
        $this->assertEquals(0.4126626504, $balance);

        $balance = $client->getBalance(Client::CURRENCY_CHINESE_YUAN_RENMINBI);
        $this->assertEquals(2.0403875492, $balance);

        $balance = $client->getBalance(Client::CURRENCY_CZECH_KORUNA);
        $this->assertEquals(8.2663534096, $balance);

        $balance = $client->getBalance(Client::CURRENCY_DANISH_KRONE);
        $this->assertEquals(2.2532690752, $balance);

        $balance = $client->getBalance(Client::CURRENCY_EURO);
        $this->assertEquals(0.3013092368, $balance);

        $balance = $client->getBalance(Client::CURRENCY_HONG_KONG_DOLLAR);
        $this->assertEquals(2.53820281, $balance);

        $balance = $client->getBalance(Client::CURRENCY_INDONESIAN_RUPIAH);
        $this->assertEquals(4256.32047984, $balance);

        $balance = $client->getBalance(Client::CURRENCY_ISRAELI_SHEKEL);
        $this->assertEquals(1.3231405616, $balance);

        $balance = $client->getBalance(Client::CURRENCY_INDIAN_RUPEE);
        $this->assertEquals(20.3776746888, $balance);

        $balance = $client->getBalance(Client::CURRENCY_JAPANESE_YEN);
        $this->assertEquals(39.399457812, $balance);

        $balance = $client->getBalance(Client::CURRENCY_MALAYSIAN_RINGGIT);
        $this->assertEquals(1.2052369472, $balance);

        $balance = $client->getBalance(Client::CURRENCY_MEXICAN_PESO);
        $this->assertEquals(4.9552269052, $balance);

        $balance = $client->getBalance(Client::CURRENCY_NORWEGIAN_KRONE);
        $this->assertEquals(2.685582328, $balance);

        $balance = $client->getBalance(Client::CURRENCY_POLISH_ZLOTY);
        $this->assertEquals(1.2510883528, $balance);

        $balance = $client->getBalance(Client::CURRENCY_ROMANIAN_NEW_LEU);
        $this->assertEquals(1.342791164, $balance);

        $balance = $client->getBalance(Client::CURRENCY_RUSSIAN_RUBLE);
        $this->assertEquals(19.4409959744, $balance);

        $balance = $client->getBalance(Client::CURRENCY_SERBIAN_DINAR);
        $this->assertEquals(36.3175883356, $balance);

        $balance = $client->getBalance(Client::CURRENCY_SWISS_FRANC);
        $this->assertEquals(0.3242349396, $balance);

        $balance = $client->getBalance(Client::CURRENCY_UKRAINIAN_HRYVNIA);
        $this->assertEquals(7.7390622452, $balance);

        $balance = $client->getBalance(Client::CURRENCY_US_DOLLAR);
        $this->assertEquals(0.32751004, $balance);
    }

    public function testGetBalanceCoin()
    {
        $client = new MockEobotClient(1234);

        $balance = $client->getBalance(Client::COIN_BITCOIN);
        $this->assertEquals(0.00040978, $balance);

        $balance = $client->getBalance(Client::COIN_BITSHARES);
        $this->assertEquals(0.0141392, $balance);

        $balance = $client->getBalance(Client::COIN_BLACKCOIN);
        $this->assertEquals(0.08188563, $balance);

        $balance = $client->getBalance(Client::COIN_BYTECOIN);
        $this->assertEquals(0.01537264, $balance);

        $balance = $client->getBalance(Client::COIN_CURECOIN);
        $this->assertEquals(0.05292104, $balance);

        $balance = $client->getBalance(Client::COIN_DOGECOIN);
        $this->assertEquals(23.78557417, $balance);

        $balance = $client->getBalance(Client::COIN_ETHERIUM);
        $this->assertEquals(15.2361832, $balance);

        $balance = $client->getBalance(Client::COIN_GRIDCOIN);
        $this->assertEquals(0.15432515, $balance);

        $balance = $client->getBalance(Client::COIN_LITECOIN);
        $this->assertEquals(0.03013698, $balance);

        $balance = $client->getBalance(Client::COIN_NAMECOIN);
        $this->assertEquals(0.00188207, $balance);

        $balance = $client->getBalance(Client::COIN_RIPPLE);
        $this->assertEquals(0.03115914, $balance);

        $balance = $client->getBalance(Client::COIN_NXT);
        $this->assertEquals(0.10494402, $balance);

        $balance = $client->getBalance(Client::COIN_PEERCOIN);
        $this->assertEquals(0.00502554, $balance);

        $balance = $client->getBalance(Client::COIN_REDDCOIN);
        $this->assertEquals(0.02830923, $balance);

        $balance = $client->getBalance(Client::COIN_MAIDSAFECOIN);
        $this->assertEquals(0.02748126, $balance);

        $balance = $client->getBalance(Client::COIN_STORJCOIN_X);
        $this->assertEquals(0.02639744, $balance);

        $balance = $client->getBalance(Client::COIN_MONERO);
        $this->assertEquals(0.03641862, $balance);

        $balance = $client->getBalance(Client::COIN_COUNTERPARTY);
        $this->assertEquals(0.00636984, $balance);

        $balance = $client->getBalance(Client::COIN_LUMENS);
        $this->assertEquals(0.06467642, $balance);

        $balance = $client->getBalance(Client::EO_CLOUD_FOLDING);
        $this->assertEquals(2.16726154, $balance);

        $balance = $client->getBalance(Client::EO_CLOUD_SHA256_2);
        $this->assertEquals(15.42138465, $balance);

        $balance = $client->getBalance(Client::EO_CLOUD_SHA256_3);
        $this->assertEquals(20.00019989, $balance);

        $balance = $client->getBalance(Client::EO_CLOUD_FOLDING_CONTRACT);
        $this->assertEquals(2.16726154, $balance);

        $balance = $client->getBalance(Client::EO_CLOUD_SHA256_2_CONTRACT);
        $this->assertEquals(15.42138465, $balance);

        $balance = $client->getBalance(Client::EO_CLOUD_SHA256_3_CONTRACT);
        $this->assertEquals(20.00019989, $balance);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage no user ID is known
     */
    public function testGetMiningModeWithoutParameters()
    {
        $client = new MockEobotClient();

        $client->getMiningMode();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage must be numeric
     */
    public function testGetMiningModeWithInvalidUserId()
    {
        $client = new MockEobotClient();

        $client->getMiningMode('foo');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Invalid API response
     */
    public function testGetMiningModeWithInvalidApiResponse()
    {
        $client = new MockEobotClient(2345);

        $client->getMiningMode();
    }

    public function testGetMiningModeContractConversion()
    {
        $client = new MockEobotClient();

        $miningMode = $client->getMiningMode(9012);
        $this->assertEquals(Client::EO_CLOUD_SHA256_2, $miningMode);

        $miningMode = $client->getMiningMode(3456);
        $this->assertEquals(Client::EO_CLOUD_SHA256_3, $miningMode);

        $miningMode = $client->getMiningMode(8901);
        $this->assertEquals(Client::EO_CLOUD_FOLDING, $miningMode);
    }

    public function testGetMiningMode()
    {
        $client = new MockEobotClient(1234);

        $miningMode = $client->getMiningMode();
        $this->assertEquals(Client::COIN_BITCOIN, $miningMode);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage no user ID is known
     */
    public function testGetSpeedWithoutParameters()
    {
        $client = new MockEobotClient();

        $client->getSpeed();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage must be numeric
     */
    public function testGetSpeedWithInvalidUserId()
    {
        $client = new MockEobotClient();

        $client->getSpeed('foo');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Invalid API response
     */
    public function testGetSpeedWithInvalidApiResponse()
    {
        $client = new MockEobotClient(2345);

        $client->getSpeed();
    }

    public function testGetSpeed()
    {
        $client = new MockEobotClient(1234);

        $speeds = $client->getSpeed();

        $this->assertInternalType('array', $speeds);
        $this->assertCount(5, $speeds);
        $this->assertEquals(0.0, $speeds['MiningSHA-256']);
        $this->assertEquals(0.0, $speeds['MiningScrypt']);
        $this->assertEquals(20.0001998933406, $speeds['CloudSHA-256']);
        $this->assertEquals(10.5013062743518, $speeds['Cloud2SHA-256']);
        $this->assertEquals(0.0111580929310733, $speeds['CloudScrypt']);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage no user ID is known
     */
    public function testGetEstimatesWithoutParameters()
    {
        $client = new MockEobotClient();

        $client->getEstimates();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid currency given
     */
    public function testGetEstimatesWithInvalidCurrency()
    {
        $client = new MockEobotClient();

        $client->getEstimates('foo');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage must be numeric
     */
    public function testGetEstimatesWithInvalidUserId()
    {
        $client = new MockEobotClient();

        $client->getEstimates(Client::CURRENCY_US_DOLLAR, 'foo');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Invalid API response
     */
    public function testGetEstimatesWithInvalidApiResponse()
    {
        $client = new MockEobotClient(2345);

        $client->getEstimates();
    }

    public function testGetEstimates()
    {
        $client = new MockEobotClient(1234);

        $estimates = $client->getEstimates();

        $this->assertInternalType('array', $estimates);
        $this->assertCount(5, $estimates);
        $this->assertEquals(0.0, $estimates['MiningSHA-256']);
        $this->assertEquals(0.0, $estimates['MiningScrypt']);
        $this->assertEquals(3.36369763, $estimates['CloudSHA-256']);
        $this->assertEquals(8.40883507, $estimates['Cloud2SHA-256']);
        $this->assertEquals(1.0111580929310733, $estimates['CloudScrypt']);
    }

    public function testGetEstimatesInCurrency()
    {
        $client = new MockEobotClient(1234);

        $estimates = $client->getEstimates(Client::CURRENCY_EURO);

        $this->assertInternalType('array', $estimates);
        $this->assertCount(5, $estimates);
        $this->assertEquals(0.0, $estimates['MiningSHA-256']);
        $this->assertEquals(0.0, $estimates['MiningScrypt']);
        $this->assertEquals(3.0946018196, $estimates['CloudSHA-256']);
        $this->assertEquals(7.7361282644, $estimates['Cloud2SHA-256']);
        $this->assertEquals(0.93026544549659, $estimates['CloudScrypt']);
    }

    public function testGetLastResponse()
    {
        $client = new MockEobotClient();

        $this->assertNull($client->getLastResponse());

        $coinValue = $client->getCoinValue(Client::EO_CLOUD_SHA256_3);

        $response = $client->getLastResponse();
        $this->assertNotNull($response);
        $this->assertInstanceof('Buzz\\Message\\Response', $response);

        $coinValue2 = floatval(trim($response->getContent()));

        $this->assertEquals($coinValue2, $coinValue);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage no user ID is known
     */
    public function testGetDepositAddressWithoutParameters()
    {
        $client = new MockEobotClient();

        $client->getDepositAddress();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid coin type
     */
    public function testGetDepositAddressWithInvalidCoinType()
    {
        $client = new MockEobotClient();

        $client->getDepositAddress('foo');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage must be numeric
     */
    public function testGetDepositAddressWithInvalidUserId()
    {
        $client = new MockEobotClient();

        $client->getDepositAddress(Client::COIN_BITCOIN, 'foo');
    }

    public function testGetDepositAddress()
    {
        $client = new MockEobotClient(1234);

        $address = $client->getDepositAddress(Client::COIN_BITCOIN);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_BITCOIN);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        // for Bitshares-X you need to contact support, the API will give an "n/a" address for it
        $address = $client->getDepositAddress(Client::COIN_BITSHARES);
        $this->assertEquals('n/a', $address);

        $address = $client->getDepositAddress(Client::COIN_BLACKCOIN);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_BYTECOIN);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_CURECOIN);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_DASH);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_DOGECOIN);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_ETHERIUM);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_GRIDCOIN);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_LITECOIN);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_NAMECOIN);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_RIPPLE);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_NXT);
        $this->assertEquals('NXT-1234-5678-90AB-CDEF', $address);

        $address = $client->getDepositAddress(Client::COIN_PEERCOIN);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_REDDCOIN);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_MAIDSAFECOIN);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_STORJCOIN_X);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_MONERO);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_COUNTERPARTY);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_LUMENS);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage No email address given, but it is required when no user ID is set
     */
    public function testGetUserIdWithoutParameters()
    {
        $client = new MockEobotClient();

        $client->getUserId();
    }

    public function testGetUserIdWithoutParametersWithPresetUserId()
    {
        $client = new MockEobotClient(1234);

        $userId = $client->getUserId();

        $this->assertEquals(1234, $userId);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage No password given, but it is required when a user ID is being fetched
     */
    public function testGetUserIdWithoutPassword()
    {
        $client = new MockEobotClient();

        $client->getUserId('test@example.com');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Invalid password given
     */
    public function testGetUserIdWithIncorrectPassword()
    {
        $client = new MockEobotClient();

        $client->getUserId('test@example.com', 'incorrectPassword');
    }

    public function testGetUserId()
    {
        $client = new MockEobotClient();

        $userId = $client->getUserId('test@example.com', 'correctPassword');

        $this->assertEquals(1234, $userId);
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testSetMiningModeWithoutParameters()
    {
        $client = new MockEobotClient();

        /** @noinspection PhpParamsInspection (this is on purpose) */
        $client->setMiningMode();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid mining type given
     */
    public function testSetMiningModeWithInvalidCoinType()
    {
        $client = new MockEobotClient();

        $client->setMiningMode('foo', null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage no user ID is known
     */
    public function testSetMiningModeWithoutUserId()
    {
        $client = new MockEobotClient();

        $client->setMiningMode(Client::COIN_BITCOIN, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage must be numeric
     */
    public function testSetMiningModeWithInvalidUserId()
    {
        $client = new MockEobotClient();

        $client->setMiningMode(Client::COIN_BITCOIN, null, null, 'foo');
    }

    public function testSetMiningModeWithInvalidCredentials()
    {
        $client = new MockEobotClient(1234);

        $this->assertFalse($client->setMiningMode(Client::COIN_DASH, 'test@example.com', 'incorrectPassword'));
    }

    public function testSetMiningModeContract()
    {
        $client = new MockEobotClient();

        $this->assertTrue($client->setMiningMode(Client::EO_CLOUD_SHA256_2_CONTRACT, 'test@example.com',
            'correctPassword', 9012));
        $this->assertTrue($client->setMiningMode(Client::EO_CLOUD_SHA256_3_CONTRACT, 'test@example.com',
            'correctPassword', 3456));
        $this->assertTrue($client->setMiningMode(Client::EO_CLOUD_FOLDING_CONTRACT, 'test@example.com',
            'correctPassword', 8901));
    }

    public function testSetMiningMode()
    {
        $client = new MockEobotClient(1234);

        $this->assertTrue($client->setMiningMode(Client::COIN_BITCOIN, 'test@example.com', 'correctPassword'));
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testSetAutomaticWithdrawWithoutParameters()
    {
        $client = new MockEobotClient();

        /** @noinspection PhpParamsInspection (this is on purpose) */
        $client->setAutomaticWithdraw();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid coin type given
     */
    public function testSetAutomaticWithdrawWithInvalidCoinType()
    {
        $client = new MockEobotClient();

        $client->setAutomaticWithdraw('foo', null, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid amount given
     */
    public function testSetAutomaticWithdrawWithInvalidAmount()
    {
        $client = new MockEobotClient();

        $client->setAutomaticWithdraw(Client::COIN_BITCOIN, 'foo', null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid amount given
     */
    public function testSetAutomaticWithdrawWithNegativeAmount()
    {
        $client = new MockEobotClient();

        $client->setAutomaticWithdraw(Client::COIN_BITCOIN, -5, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid amount given
     */
    public function testSetAutomaticWithdrawWithZeroAmount()
    {
        $client = new MockEobotClient();

        $client->setAutomaticWithdraw(Client::COIN_BITCOIN, 0, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage no user ID is known
     */
    public function testSetAutomaticWithdrawWithoutUserId()
    {
        $client = new MockEobotClient();

        $client->setAutomaticWithdraw(Client::COIN_BITCOIN, 1, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage must be numeric
     */
    public function testSetAutomaticWithdrawWithInvalidUserId()
    {
        $client = new MockEobotClient();

        $client->setAutomaticWithdraw(Client::COIN_BITCOIN, 1, null, null, null, 'foo');
    }

    public function testSetAutomaticWithdrawWithInvalidCredentials()
    {
        $client = new MockEobotClient(1234);

        // Unfortunately, the Eobot API does not currently respond in a way that can be used to determine whether the
        // change was successful, so the Client always assumes it worked
        $this->assertTrue($client->setAutomaticWithdraw(Client::COIN_BITCOIN, 1, '1234567890abcdefghijklmnopqrstuvwx',
            'test@example.com', 'incorrectPassword'));
    }

    public function testSetAutomaticWithdrawWithInvalidWallet()
    {
        $client = new MockEobotClient(1234);

        // Unfortunately, the Eobot API does not currently respond in a way that can be used to determine whether the
        // change was successful, so the Client always assumes it worked
        $this->assertTrue($client->setAutomaticWithdraw(Client::COIN_BITCOIN, 1, 'invalid', 'test@example.com',
            'correctPassword'));
    }

    /**
     * At the time of writing, the minimum amount for automatic withdrawal of Bitcoins is 0.001 BTC
     */
    public function testSetAutomaticWithdrawWithInsufficientAmount()
    {
        $client = new MockEobotClient(1234);

        // Unfortunately, the Eobot API does not currently respond in a way that can be used to determine whether the
        // change was successful, so the Client always assumes it worked
        $this->assertTrue($client->setAutomaticWithdraw(Client::COIN_BITCOIN, 0.00001,
            '1234567890abcdefghijklmnopqrstuvwx', 'test@example.com', 'correctPassword'));
    }

    public function testSetAutomaticWithdraw()
    {
        $client = new MockEobotClient(1234);

        // Unfortunately, the Eobot API does not currently respond in a way that can be used to determine whether the
        // change was successful, so the Client always assumes it worked
        $this->assertTrue($client->setAutomaticWithdraw(Client::COIN_BITCOIN, 1, '1234567890abcdefghijklmnopqrstuvwx',
            'test@example.com', 'correctPassword'));
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testWithdrawFundsWithoutParameters()
    {
        $client = new MockEobotClient();

        /** @noinspection PhpParamsInspection (this is on purpose) */
        $client->withdrawFunds();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid coin type given
     */
    public function testWithdrawFundsWithInvalidCoinType()
    {
        $client = new MockEobotClient();

        $client->withdrawFunds('foo', null, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid amount given
     */
    public function testWithdrawFundsWithInvalidAmount()
    {
        $client = new MockEobotClient();

        $client->withdrawFunds(Client::COIN_BITCOIN, 'foo', null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid amount given
     */
    public function testWithdrawFundsWithNegativeAmount()
    {
        $client = new MockEobotClient();

        $client->withdrawFunds(Client::COIN_BITCOIN, -5, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid amount given
     */
    public function testWithdrawFundsWithZeroAmount()
    {
        $client = new MockEobotClient();

        $client->withdrawFunds(Client::COIN_BITCOIN, 0, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage no user ID is known
     */
    public function testWithdrawFundsWithoutUserId()
    {
        $client = new MockEobotClient();

        $client->withdrawFunds(Client::COIN_BITCOIN, 1, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage must be numeric
     */
    public function testWithdrawFundsWithInvalidUserId()
    {
        $client = new MockEobotClient();

        $client->withdrawFunds(Client::COIN_BITCOIN, 1, null, null, null, 'foo');
    }

    public function testWithdrawFundsWithInvalidCredentials()
    {
        $client = new MockEobotClient(1234);

        $this->assertFalse($client->withdrawFunds(Client::COIN_BITCOIN, 1, '1234567890abcdefghijklmnopqrstuvwx',
            'test@example.com', 'incorrectPassword'));
    }

    public function testWithdrawFundsWithInvalidWallet()
    {
        $client = new MockEobotClient(1234);

        $this->assertFalse($client->withdrawFunds(Client::COIN_BITCOIN, 1, 'invalid', 'test@example.com',
            'correctPassword'));
    }

    /**
     * At the time of writing, the minimum amount for manual withdrawal of Bitcoins is 0.001 BTC
     */
    public function testWithdrawFundsWithInsufficientAmount()
    {
        $client = new MockEobotClient(1234);

        $this->assertFalse($client->withdrawFunds(Client::COIN_BITCOIN, 0.00001, '1234567890abcdefghijklmnopqrstuvwx',
            'test@example.com', 'correctPassword'));
    }

    public function testWithdrawFundsWithInsufficientFunds()
    {
        $client = new MockEobotClient(1234);

        $this->assertFalse($client->withdrawFunds(Client::COIN_BITCOIN, 100, '1234567890abcdefghijklmnopqrstuvwx',
            'test@example.com', 'correctPassword'));
    }

    public function testWithdrawFunds()
    {
        $client = new MockEobotClient(1234);

        $this->assertTrue($client->withdrawFunds(Client::COIN_BITCOIN, 0.002, '1234567890abcdefghijklmnopqrstuvwx',
            'test@example.com', 'correctPassword'));
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testConvertCoinToCloudWithoutParameters()
    {
        $client = new MockEobotClient();

        /** @noinspection PhpParamsInspection (this is on purpose) */
        $client->convertCoinToCloud();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid coin type given
     */
    public function testConvertCoinToCloudWithInvalidCoinType()
    {
        $client = new MockEobotClient();

        $client->convertCoinToCloud('foo', null, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid amount given
     */
    public function testConvertCoinToCloudWithInvalidAmount()
    {
        $client = new MockEobotClient();

        $client->convertCoinToCloud(Client::COIN_BITCOIN, 'foo', null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid amount given
     */
    public function testConvertCoinToCloudWithNegativeAmount()
    {
        $client = new MockEobotClient();

        $client->convertCoinToCloud(Client::COIN_BITCOIN, -5, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid amount given
     */
    public function testConvertCoinToCloudWithZeroAmount()
    {
        $client = new MockEobotClient();

        $client->convertCoinToCloud(Client::COIN_BITCOIN, 0, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid cloud type
     */
    public function testConvertCoinToCloudWithInvalidCloudType()
    {
        $client = new MockEobotClient();

        $client->convertCoinToCloud(Client::COIN_BITCOIN, 1, 'foo', null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid cloud type
     */
    public function testConvertCoinToCloudWithCoinAsCloudType()
    {
        $client = new MockEobotClient();

        $client->convertCoinToCloud(Client::COIN_BITCOIN, 1, Client::COIN_DASH, null, null);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Cannot convert a cloud type to itself
     */
    public function testConvertCoinToCloudWithConversionToSelf()
    {
        $client = new MockEobotClient();

        $client->convertCoinToCloud(Client::EO_CLOUD_SHA256_3, 1, Client::EO_CLOUD_SHA256_3, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage no user ID is known
     */
    public function testConvertCoinToCloudWithoutUserId()
    {
        $client = new MockEobotClient();

        $client->convertCoinToCloud(Client::COIN_BITCOIN, 1, Client::EO_CLOUD_SHA256_3, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage must be numeric
     */
    public function testConvertCoinToCloudWithInvalidUserId()
    {
        $client = new MockEobotClient();

        $client->convertCoinToCloud(Client::COIN_BITCOIN, 1, Client::EO_CLOUD_SHA256_3, null, null, 'foo');
    }

    public function testConvertCoinToCloudWithInvalidCredentials()
    {
        $client = new MockEobotClient(1234);

        $this->assertFalse($client->convertCoinToCloud(Client::COIN_BITCOIN, 0.00002, Client::EO_CLOUD_FOLDING_CONTRACT,
            'test@example.com', 'incorrectPassword'));
    }

    public function testConvertCoinToCloudWithInsufficientFunds()
    {
        $client = new MockEobotClient(1234);

        $this->assertFalse($client->convertCoinToCloud(Client::COIN_BITCOIN, 100, Client::EO_CLOUD_SHA256_3_CONTRACT,
            'test@example.com', 'correctPassword'));

        $this->assertFalse($client->convertCoinToCloud(Client::COIN_BITCOIN, 100, Client::EO_CLOUD_SHA256_2_CONTRACT,
            'test@example.com', 'correctPassword'));
    }

    public function testConvertCoinToCloudFromCloud()
    {
        $client = new MockEobotClient(1234);

        $this->assertTrue($client->convertCoinToCloud(Client::EO_CLOUD_SHA256_2_CONTRACT, 0.00002,
            Client::RENTAL_SHA256,
            'test@example.com', 'correctPassword'));
        $this->assertTrue($client->convertCoinToCloud(Client::EO_CLOUD_SHA256_3_CONTRACT, 0.00002,
            Client::RENTAL_SHA256,
            'test@example.com', 'correctPassword'));
        $this->assertTrue($client->convertCoinToCloud(Client::EO_CLOUD_FOLDING_CONTRACT, 0.00002,
            Client::RENTAL_FOLDING, 'test@example.com', 'correctPassword'));
    }

    public function testConvertCoinToCloud()
    {
        $client = new MockEobotClient(1234);

        // Unfortunately, the Eobot API does not currently respond in a way that can be used to determine whether the
        // change was successful, so the Client always assumes it worked
        $this->assertTrue($client->convertCoinToCloud(Client::COIN_BITCOIN, 0.002, Client::EO_CLOUD_SHA256_3_CONTRACT,
            'test@example.com', 'correctPassword'));
    }
}