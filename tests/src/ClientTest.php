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

        $this->assertEquals(378.99, $coinValue);
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
     * @expectedExceptionMessage Failed to retrieve value in USD for coin:
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
            Client::COIN_BITCOIN      => 378.99,
            Client::COIN_BITSHARES    => 0.00336616,
            Client::COIN_BLACKCOIN    => 0.02747326,
            Client::COIN_BYTECOIN     => 0.00003278,
            Client::COIN_COUNTERPARTY => 0.68706107,
            Client::COIN_CURECOIN     => 0.00720565,
            Client::COIN_DOGECOIN     => 0.00027966,
            Client::COIN_ETHERIUM     => 2.24435704,
            Client::COIN_FACTOM       => 0.90754598,
            Client::COIN_GRIDCOIN     => 0.005964,
            Client::COIN_LITECOIN     => 3.06900745,
            Client::COIN_LUMENS       => 0.00167083,
            Client::COIN_MAIDSAFECOIN => 0.02012823,
            Client::COIN_MONERO       => 0.49839172,
            Client::COIN_NAMECOIN     => 0.40521139,
            Client::COIN_NXT          => 0.00837119,
            Client::COIN_PEERCOIN     => 0.43636065,
            Client::COIN_REDDCOIN     => 0.00002293,
            Client::COIN_RIPPLE       => 0.00647743,
            Client::EO_CLOUD_FOLDING  => 0.05,
            Client::EO_CLOUD_SETI     => 0.8,
            Client::EO_CLOUD_SHA256_2 => 0.11,
            Client::EO_CLOUD_SHA256_3 => 0.45,
            Client::RENTAL_FOLDING    => 0.00014,
            Client::RENTAL_SCRYPT     => 0.02464,
            Client::RENTAL_SHA256_3   => 0.00114,
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

        $this->assertEquals(378.99, $coinValue);

        $coinValue = $client->getCoinValue(Client::COIN_BITCOIN, Client::CURRENCY_EURO);

        $this->assertEquals(349.9284517857, $coinValue);
    }

    public function testGetCoinValueWithCache()
    {
        $cachePool = new MockEobotCachePool();

        $client = new MockEobotClient();
        $client->setCachePool($cachePool);

        $this->assertFalse($cachePool->hasItem('eobot_coin_values_supported'));
        $client->getCoinValue(Client::COIN_BITCOIN);

        $this->assertTrue($cachePool->hasItem('eobot_coin_values_supported'));
        $client->getCoinValue(Client::COIN_BITCOIN);

        $this->assertFalse($cachePool->hasItem('eobot_coin_value_' . Client::EO_CLOUD_SHA256_3));
        $client->getCoinValue(Client::EO_CLOUD_SHA256_3);

        $this->assertTrue($cachePool->hasItem('eobot_coin_value_' . Client::EO_CLOUD_SHA256_3));
        $client->getCoinValue(Client::EO_CLOUD_SHA256_3);
    }

    public function testGetExchangeRateWithoutParameters()
    {
        $client = new MockEobotClient();

        $exchangeRate = $client->getExchangeRate();

        $this->assertEquals(0.92331843, $exchangeRate);
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
     * @expectedExceptionMessage Failed to retrieve exchange rate from USD for currency:
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
            Client::CURRENCY_BRITISH_POUND         => 0.70205004,
            Client::CURRENCY_CANADIAN_DOLLAR       => 1.3977,
            Client::CURRENCY_CHINESE_YUAN_RENMINBI => 6.57705,
            Client::CURRENCY_CZECH_KORUNA          => 24.946,
            Client::CURRENCY_DANISH_KRONE          => 6.8889,
            Client::CURRENCY_EURO                  => 0.92331843,
            Client::CURRENCY_HONG_KONG_DOLLAR      => 7.78205,
            Client::CURRENCY_INDIAN_RUPEE          => 67.88995,
            Client::CURRENCY_INDONESIAN_RUPIAH     => 13680.5,
            Client::CURRENCY_ISRAELI_SHEKEL        => 3.9614,
            Client::CURRENCY_JAPANESE_YEN          => 121.12,
            Client::CURRENCY_MALAYSIAN_RINGGIT     => 4.14295,
            Client::CURRENCY_MEXICAN_PESO          => 18.1109,
            Client::CURRENCY_NORWEGIAN_KRONE       => 8.6807,
            Client::CURRENCY_POLISH_ZLOTY          => 4.08195,
            Client::CURRENCY_ROMANIAN_NEW_LEU      => 4.18825,
            Client::CURRENCY_RUSSIAN_RUBLE         => 75.7245,
            Client::CURRENCY_SERBIAN_DINAR         => 113.26,
            Client::CURRENCY_SWISS_FRANC           => 1.023,
            Client::CURRENCY_UKRAINIAN_HRYVNIA     => 25.65,
            Client::CURRENCY_US_DOLLAR             => 1.0,
        );

        foreach ($currencies as $currency => $expectedRate) {
            $exchangeRate = $client->getExchangeRate($currency);

            $this->assertEquals($expectedRate, $exchangeRate, $currency);
        }
    }

    public function testGetExchangeRateWithCache()
    {
        $cachePool = new MockEobotCachePool();

        $client = new MockEobotClient();
        $client->setCachePool($cachePool);

        $this->assertFalse($cachePool->hasItem('eobot_exchange_rates_supported'));
        $client->getExchangeRate(Client::CURRENCY_EURO);

        $this->assertTrue($cachePool->hasItem('eobot_exchange_rates_supported'));
        $client->getExchangeRate(Client::CURRENCY_EURO);

        $this->assertFalse($cachePool->hasItem('eobot_exchange_rate_' . Client::CURRENCY_CANADIAN_DOLLAR));
        try {
            $client->getExchangeRate(Client::CURRENCY_CANADIAN_DOLLAR);
        } catch (Exception $e) {
            // ignore
        }

        $this->assertTrue($cachePool->hasItem('eobot_exchange_rate_' . Client::CURRENCY_CANADIAN_DOLLAR));
        try {
            $client->getExchangeRate(Client::CURRENCY_CANADIAN_DOLLAR);
        } catch (Exception $e) {
            // ignore
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

        $this->assertEquals(0.00040978, $balances[Client::COIN_BITCOIN]);
        $this->assertEquals(0.0141392, $balances[Client::COIN_BITSHARES]);
        $this->assertEquals(0.08188563, $balances[Client::COIN_BLACKCOIN]);
        $this->assertEquals(0.01537264, $balances[Client::COIN_BYTECOIN]);
        $this->assertEquals(0.05292104, $balances[Client::COIN_CURECOIN]);
        $this->assertEquals(23.78557417, $balances[Client::COIN_DOGECOIN]);
        $this->assertEquals(15.2361832, $balances[Client::COIN_ETHERIUM]);
        $this->assertEquals(0.02639744, $balances[Client::COIN_FACTOM]);
        $this->assertEquals(0.15432515, $balances[Client::COIN_GRIDCOIN]);
        $this->assertEquals(0.03013698, $balances[Client::COIN_LITECOIN]);
        $this->assertEquals(0.06467642, $balances[Client::COIN_LUMENS]);
        $this->assertEquals(0.00188207, $balances[Client::COIN_NAMECOIN]);
        $this->assertEquals(0.03115914, $balances[Client::COIN_RIPPLE]);
        $this->assertEquals(0.10494402, $balances[Client::COIN_NXT]);
        $this->assertEquals(0.00502554, $balances[Client::COIN_PEERCOIN]);
        $this->assertEquals(0.02830923, $balances[Client::COIN_REDDCOIN]);
        $this->assertEquals(0.02748126, $balances[Client::COIN_MAIDSAFECOIN]);
        $this->assertEquals(0.03641862, $balances[Client::COIN_MONERO]);
        $this->assertEquals(0.00636984, $balances[Client::COIN_COUNTERPARTY]);
        $this->assertEquals(2.16726154, $balances[Client::EO_CLOUD_FOLDING]);
        $this->assertEquals(4.73192563, $balances[Client::EO_CLOUD_SETI]);
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

        $vd = $client->getBalance(null, 2345);

        var_dump($vd);
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
        $this->assertEquals(0.2299284366824, $balance);

        $balance = $client->getBalance(Client::CURRENCY_CANADIAN_DOLLAR);
        $this->assertEquals(0.457760782908, $balance);

        $balance = $client->getBalance(Client::CURRENCY_CHINESE_YUAN_RENMINBI);
        $this->assertEquals(2.154049908582, $balance);

        $balance = $client->getBalance(Client::CURRENCY_CZECH_KORUNA);
        $this->assertEquals(8.17006545784, $balance);

        $balance = $client->getBalance(Client::CURRENCY_DANISH_KRONE);
        $this->assertEquals(2.256183914556, $balance);

        $balance = $client->getBalance(Client::CURRENCY_EURO);
        $this->assertEquals(0.30239605594204, $balance);

        $balance = $client->getBalance(Client::CURRENCY_HONG_KONG_DOLLAR);
        $this->assertEquals(2.548699506782, $balance);

        $balance = $client->getBalance(Client::CURRENCY_INDONESIAN_RUPIAH);
        $this->assertEquals(4480.50110222, $balance);

        $balance = $client->getBalance(Client::CURRENCY_ISRAELI_SHEKEL);
        $this->assertEquals(1.297398272456, $balance);

        $balance = $client->getBalance(Client::CURRENCY_INDIAN_RUPEE);
        $this->assertEquals(22.234640240098, $balance);

        $balance = $client->getBalance(Client::CURRENCY_JAPANESE_YEN);
        $this->assertEquals(39.6680160448, $balance);

        $balance = $client->getBalance(Client::CURRENCY_MALAYSIAN_RINGGIT);
        $this->assertEquals(1.356857720218, $balance);

        $balance = $client->getBalance(Client::CURRENCY_MEXICAN_PESO);
        $this->assertEquals(5.931501583436, $balance);

        $balance = $client->getBalance(Client::CURRENCY_NORWEGIAN_KRONE);
        $this->assertEquals(2.843016404228, $balance);

        $balance = $client->getBalance(Client::CURRENCY_POLISH_ZLOTY);
        $this->assertEquals(1.336879607778, $balance);

        $balance = $client->getBalance(Client::CURRENCY_ROMANIAN_NEW_LEU);
        $this->assertEquals(1.37169392503, $balance);

        $balance = $client->getBalance(Client::CURRENCY_RUSSIAN_RUBLE);
        $this->assertEquals(24.80053402398, $balance);

        $balance = $client->getBalance(Client::CURRENCY_SERBIAN_DINAR);
        $this->assertEquals(37.0937871304, $balance);

        $balance = $client->getBalance(Client::CURRENCY_SWISS_FRANC);
        $this->assertEquals(0.33504277092, $balance);

        $balance = $client->getBalance(Client::CURRENCY_UKRAINIAN_HRYVNIA);
        $this->assertEquals(8.400632526, $balance);

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

        $balance = $client->getBalance(Client::COIN_FACTOM);
        $this->assertEquals(0.02639744, $balance);

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

    public function testGetBalanceWithCache()
    {
        $cachePool = new MockEobotCachePool();

        $client = new MockEobotClient(1234);
        $client->setCachePool($cachePool);

        $this->assertFalse($cachePool->hasItem('eobot_balance_all_1234'));
        $client->getBalance(Client::EO_CLOUD_SETI_CONTRACT);

        $this->assertTrue($cachePool->hasItem('eobot_balance_all_1234'));
        $client->getBalance(Client::EO_CLOUD_SETI_CONTRACT);
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

    public function testGetMiningModeWithCache()
    {
        $cachePool = new MockEobotCachePool();

        $client = new MockEobotClient(7890);
        $client->setCachePool($cachePool);

        $this->assertFalse($cachePool->hasItem('eobot_mining_mode_7890'));
        $client->getMiningMode();

        $this->assertTrue($cachePool->hasItem('eobot_mining_mode_7890'));
        $client->getMiningMode();
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
        $this->assertEquals(20.00019989, $speeds['CloudSHA-256']);
        $this->assertEquals(10.50130627, $speeds['Cloud2SHA-256']);
        $this->assertEquals(0.01115809, $speeds['CloudScrypt']);
    }

    public function testGetSpeedWithCache()
    {
        $cachePool = new MockEobotCachePool();

        $client = new MockEobotClient(1234);
        $client->setCachePool($cachePool);

        $this->assertFalse($cachePool->hasItem('eobot_mining_speeds_1234'));
        $client->getSpeed();

        $this->assertTrue($cachePool->hasItem('eobot_mining_speeds_1234'));
        $client->getSpeed();
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
        $this->assertEquals(3.1057640147263, $estimates['CloudSHA-256']);
        $this->assertEquals(7.7640323949613, $estimates['Cloud2SHA-256']);
        $this->assertEquals(0.93362090284691, $estimates['CloudScrypt']);
    }

    public function testGetEstimatesWithCache()
    {
        $cachePool = new MockEobotCachePool();

        $client = new MockEobotClient(1234);
        $client->setCachePool($cachePool);

        $this->assertFalse($cachePool->hasItem('eobot_mining_estimates_1234'));
        $client->getEstimates();

        $this->assertTrue($cachePool->hasItem('eobot_mining_estimates_1234'));
        $client->getEstimates();
    }

    public function testGetLastResponse()
    {
        $client = new MockEobotClient();

        $this->assertNull($client->getLastResponse());

        $coinValue = $client->getCoinValue(Client::EO_CLOUD_SHA256_3);

        $response = $client->getLastResponse();
        $this->assertNotNull($response);
        $this->assertInstanceOf('Buzz\\Message\\Response', $response);

        $parsedResponse = json_decode(trim($response->getContent()), true);
        $coinValue2     = floatval($parsedResponse[Client::EO_CLOUD_SHA256_3]);

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

        $address = $client->getDepositAddress(Client::COIN_FACTOM);
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

        $address = $client->getDepositAddress(Client::COIN_MONERO);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_COUNTERPARTY);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);

        $address = $client->getDepositAddress(Client::COIN_LUMENS);
        $this->assertEquals('1234567890abcdefghijklmnopqrstuvwx', $address);
    }

    public function testGetDepositAddressWithCache()
    {
        $cachePool = new MockEobotCachePool();

        $client = new MockEobotClient(1234);
        $client->setCachePool($cachePool);

        $this->assertFalse($cachePool->hasItem('eobot_deposit_address_1234_' . Client::COIN_BITCOIN));
        $client->getDepositAddress(Client::COIN_BITCOIN);

        $this->assertTrue($cachePool->hasItem('eobot_deposit_address_1234_' . Client::COIN_BITCOIN));
        $client->getDepositAddress(Client::COIN_BITCOIN);
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
        $this->assertTrue($client->setMiningMode(Client::EO_CLOUD_SETI_CONTRACT, 'test@example.com',
            'correctPassword', 7890));
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
            Client::RENTAL_SHA256_3, 'test@example.com', 'correctPassword'));
        $this->assertTrue($client->convertCoinToCloud(Client::EO_CLOUD_SHA256_3_CONTRACT, 0.00002,
            Client::EO_CLOUD_SETI_CONTRACT, 'test@example.com', 'correctPassword'));
        $this->assertTrue($client->convertCoinToCloud(Client::EO_CLOUD_FOLDING_CONTRACT, 0.00002,
            Client::RENTAL_FOLDING, 'test@example.com', 'correctPassword'));
        $this->assertTrue($client->convertCoinToCloud(Client::EO_CLOUD_SETI_CONTRACT, 0.00002,
            Client::RENTAL_SCRYPT, 'test@example.com', 'correctPassword'));
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