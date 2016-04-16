<?php
namespace RickDenHaan\Eobot;

use Buzz\Browser;
use Buzz\Client\Curl;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Eobot Client is the class that communicates with the Eobot API
 *
 * @package RickDenHaan\Eobot
 */
class Client
{
    /**
     * The coin abbreviation for Bitcoin
     */
    const COIN_BITCOIN = 'BTC';

    /**
     * The coin abbreviation for Litecoin
     */
    const COIN_LITECOIN = 'LTC';

    /**
     * The coin abbreviation for BlackCoin
     */
    const COIN_BLACKCOIN = 'BLK';

    /**
     * The coin abbreviation for Namecoin
     */
    const COIN_NAMECOIN = 'NMC';

    /**
     * The coin abbreviation for Dogecoin
     */
    const COIN_DOGECOIN = 'DOGE';

    /**
     * The coin abbreviation for Ripple
     */
    const COIN_RIPPLE = 'XRP';

    /**
     * The coin abbreviation for Dash
     */
    const COIN_DASH = 'DASH';

    /**
     * The coin abbreviation for Reddcoin
     */
    const COIN_REDDCOIN = 'RDD';

    /**
     * The coin abbreviation for BitShares
     */
    const COIN_BITSHARES = 'BTS';

    /**
     * The coin abbreviation for CureCoin
     */
    const COIN_CURECOIN = 'CURE';

    /**
     * The coin abbreviation for Monero
     */
    const COIN_MONERO = 'XMR';

    /**
     * The coin abbreviation for NEM
     */
    const COIN_NEM = 'XEM';

    /**
     * The coin abbreviation for Lumens
     */
    const COIN_LUMENS = 'XLM';

    /**
     * The coin abbreviation for Voxels
     */
    const COIN_VOXELS = 'VOX';

    /**
     * The coin abbreviation for Bytecoin
     */
    const COIN_BYTECOIN = 'BCN';

    /**
     * The coin abbreviation for Peercoin
     */
    const COIN_PEERCOIN = 'PPC';

    /**
     * The coin abbreviation for MaidSafeCoin
     */
    const COIN_MAIDSAFECOIN = 'MAID';

    /**
     * The coin abbreviation for Etherium
     */
    const COIN_ETHERIUM = 'ETH';

    /**
     * The coin abbreviation for Gridcoin
     */
    const COIN_GRIDCOIN = 'GRC';

    /**
     * The coin abbreviation for Factom
     */
    const COIN_FACTOM = 'FCT';

    /**
     * The Eobot abbreviation for their 2nd generation Cloud SHA-256 miners
     */
    const EO_CLOUD_SHA256_2          = 'GHS2';
    const EO_CLOUD_SHA256_2_CONTRACT = 'GHS2CONTRACT';

    /**
     * The Eobot abbreviation for their Cloud SHA-256 v3 miners
     */
    const EO_CLOUD_SHA256_3          = 'GHS';
    const EO_CLOUD_SHA256_3_CONTRACT = 'GHSCONTRACT';

    /**
     * The Eobot abbreviation for their Cloud Folding service
     */
    const EO_CLOUD_FOLDING          = 'PPD';
    const EO_CLOUD_FOLDING_CONTRACT = 'PPDCONTRACT';

    /**
     * The Eobot abbreviation for their Cloud SETI service
     */
    const EO_CLOUD_SETI          = 'BPPD';
    const EO_CLOUD_SETI_CONTRACT = 'BPPDCONTRACT';

    /**
     * The currency abbreviation for US Dollar
     */
    const CURRENCY_US_DOLLAR = 'USD';

    /**
     * The currency abbreviation for Euro
     */
    const CURRENCY_EURO = 'EUR';

    /**
     * The currency abbreviation for Japanese Yen
     */
    const CURRENCY_JAPANESE_YEN = 'JPY';

    /**
     * The currency abbreviation for British Pound
     */
    const CURRENCY_BRITISH_POUND = 'GBP';

    /**
     * The currency abbreviation for Russian Ruble
     */
    const CURRENCY_RUSSIAN_RUBLE = 'RUB';

    /**
     * The currency abbreviation for Chinese Yuan Renminbi
     */
    const CURRENCY_CHINESE_YUAN_RENMINBI = 'CNY';

    /**
     * The currency abbreviation for Canadian Dollar
     */
    const CURRENCY_CANADIAN_DOLLAR = 'CAD';

    /**
     * The currency abbreviation for Australian Dollar
     */
    const CURRENCY_AUSTRALIAN_DOLLAR = 'AUD';

    /**
     * The currency abbreviation for Mexican Peso
     */
    const CURRENCY_MEXICAN_PESO = 'MXN';

    /**
     * The currency abbreviation for Indonesian Rupiah
     */
    const CURRENCY_INDONESIAN_RUPIAH = 'IDR';

    /**
     * The currency abbreviation for Norwegian Krone
     */
    const CURRENCY_NORWEGIAN_KRONE = 'NOK';

    /**
     * The currency abbreviation for Czech Koruna
     */
    const CURRENCY_CZECH_KORUNA = 'CZK';

    /**
     * The currency abbreviation for Polish Zloty
     */
    const CURRENCY_POLISH_ZLOTY = 'PLN';

    /**
     * The currency abbreviation for Danish Krone
     */
    const CURRENCY_DANISH_KRONE = 'DKK';

    /**
     * The currency abbreviation for Indian Rupee
     */
    const CURRENCY_INDIAN_RUPEE = 'INR';

    /**
     * The currency abbreviation for Romanian New Leu
     */
    const CURRENCY_ROMANIAN_NEW_LEU = 'RON';

    /**
     * The currency abbreviation for Ukrainian Hryvnia
     */
    const CURRENCY_UKRAINIAN_HRYVNIA = 'UAH';

    /**
     * The currency abbreviation for Hong Kong Dollar
     */
    const CURRENCY_HONG_KONG_DOLLAR = 'HKD';

    /**
     * The currency abbreviation for Serbian Dinar
     */
    const CURRENCY_SERBIAN_DINAR = 'RSD';

    /**
     * The currency abbreviation for Malaysian Ringgit
     */
    const CURRENCY_MALAYSIAN_RINGGIT = 'MYR';

    /**
     * The currency abbreviation for Israeli Shekel
     */
    const CURRENCY_ISRAELI_SHEKEL = 'ILS';

    /**
     * The currency abbreviation for Swiss Franc
     */
    const CURRENCY_SWISS_FRANC = 'CHF';

    /**
     * Query parameter used to request a coin value or currency exchange rate
     */
    const QUERY_COIN = 'coin';

    /**
     * Query parameter used to request the user's current mining totals
     */
    const QUERY_TOTAL = 'total';

    /**
     * Query parameter used to check what a user is currently mining
     */
    const QUERY_IDMINING = 'idmining';

    /**
     * Query parameter used to check what the user's current mining speeds are
     */
    const QUERY_IDSPEED = 'idspeed';

    /**
     * Query parameter used to check what the user's current mining income is
     */
    const QUERY_IDESTIMATES = 'idestimates';

    /**
     * Query parameter used to identify a user when changing a setting
     */
    const QUERY_ID = 'id';

    /**
     * Query parameter used as the username when changing a user's setting
     */
    const QUERY_EMAIL = 'email';

    /**
     * Query parameter used as the password when changing a user's setting
     */
    const QUERY_PASSWORD = 'password';

    /**
     * Query parameter used to change what the user is currently mining
     */
    const QUERY_MINING = 'mining';

    /**
     * Query parameter used to request a deposit wallet address for a particular cryptocurrency
     */
    const QUERY_DEPOSIT = 'deposit';

    /**
     * Query parameter used to configure the automatic withdrawal wallet address for a particular cryptocurrency
     */
    const QUERY_WITHDRAW = 'withdraw';

    /**
     * Query parameter used to withdraw a specific amount of funds from Eobot
     */
    const QUERY_AMOUNT = 'amount';

    /**
     * Query parameter used to specify a wallet address for withdrawing funds from Eobot
     */
    const QUERY_WALLET = 'wallet';

    /**
     * Query parameter used to manually withdraw funds for a particular cryptocurrency
     */
    const QUERY_MANUAL_WITHDRAW = 'manualwithdraw';

    /**
     * Query parameter used to convert cryptocurrency funds to cloud mining power
     */
    const QUERY_CONVERT_FROM = 'convertfrom';

    /**
     * Query parameter used to convert cryptocurrency funds to cloud mining power
     */
    const QUERY_CONVERT_TO = 'convertto';

    /**
     * Query parameter used to request the USD values for all supported coins
     */
    const QUERY_SUPPORTED_COINS = 'supportedcoins';

    /**
     * Query parameter used to request the exchange rates from USD for all supported currencies
     */
    const QUERY_SUPPORTED_CURRENCIES = 'supportedfiat';

    /**
     * Query parameter used to request a JSON response from the server
     */
    const QUERY_JSON = 'json';

    /**
     * The Eobot abbreviation for their 24-hour Cloud SCRYPT miner rental service
     */
    const RENTAL_SCRYPT = 'SCRYPTTEMP';

    /**
     * The Eobot abbreviation for their 24-hour Cloud SHA-256 miner rental service
     */
    const RENTAL_SHA256_3 = 'GHSTEMP';

    /**
     * The Eobot abbreviation for their 24-hour Cloud Folding rental service
     */
    const RENTAL_FOLDING = 'PPDTEMP';

    /**
     * The Eobot user identifier
     *
     * @type int
     */
    protected $userId = null;

    /**
     * The local cache to use for GET requests
     *
     * @type CacheItemPoolInterface
     */
    protected $cachePool = null;

    /**
     * A boolean indicator which can be set to false so that the Eobot SSL certificate is not verified. **Not
     * recommended.**
     *
     * @type bool
     */
    private $validateSsl = true;

    /**
     * The timeout to use when connecting to Eobot (in seconds).
     *
     * @type int
     */
    private $timeout = 30;

    /**
     * This property contains the base URL for all API requests.
     *
     * @type string
     */
    protected $baseUrl = 'https://www.eobot.com/api.aspx';

    /**
     * Contains the Response object for the last submitted request.
     *
     * @type \Buzz\Message\Response
     */
    protected $response;

    /**
     * The constructor has one optional argument, the user identifier. By providing this here, it will be set globally
     * so you don't need to pass it along with every request.
     *
     * <code>
     * $client = new Client(1234);
     * </code>
     *
     * @param int                    $userId    (Optional) Defaults to null
     * @param CacheItemPoolInterface $cachePool (Optional) Defaults to null
     * @throws \InvalidArgumentException
     */
    public function __construct($userId = null, CacheItemPoolInterface $cachePool = null)
    {
        if ($userId !== null && preg_match('/[^0-9]/', $userId)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid user ID provided, must be numeric',
                    __METHOD__
                )
            );
        }

        $this->userId    = $userId;
        $this->cachePool = $cachePool;
    }

    /**
     * This method returns the current value of a type of cryptocurrency, according to popular exchanges. This method
     * has three optional arguments:
     *
     * * The cryptocurrency to retrieve the value for, which defaults to Bitcoin
     * * The real-world currency to retrieve the value in, which defaults to US Dollars
     * * Whether or not to force a fetch from the server (results are cached by default)
     *
     * <code>
     * $client = new Client();
     * $liteCoinValueInEuros = $client->getCoinValue(Client::COIN_LITECOIN, Client::CURRENCY_EUROS);
     * </code>
     *
     * @param string $coin       (Optional) Defaults to Bitcoin
     * @param string $currency   (Optional) Defaults to US Dollars
     * @param bool   $forceFetch (Optional) Defaults to false, which returns the cached result from a previous fetch
     * @throws \InvalidArgumentException|\LogicException
     * @return float
     */
    public function getCoinValue($coin = self::COIN_BITCOIN, $currency = self::CURRENCY_US_DOLLAR, $forceFetch = false)
    {
        if (!self::isValidCoin($coin) && !self::isValidEobotInternalType($coin) && !self::isValidRentalType($coin)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid coin type given: %2$s',
                    __METHOD__,
                    $coin
                )
            );
        }

        if (!self::isValidCurrency($currency)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid currency type given: %2$s',
                    __METHOD__,
                    $currency
                )
            );
        }

        $coinValues             = array();
        $supportedCoinsResponse = null;

        // check whether we have the coin values in USD for all supported coins cached
        if (($this->cachePool !== null && $this->cachePool->hasItem('eobot_coin_values_supported')) && !$forceFetch) {
            // retrieve USD values for all supported coins
            $cachedSupportedCoinValues = $this->cachePool->getItem('eobot_coin_values_supported');

            if ($cachedSupportedCoinValues->isHit()) {
                $supportedCoinsResponse = $cachedSupportedCoinValues->get();
            }
        }

        // if not, or we're asked to force-fetch a result, get the supported coins from the server
        if ($supportedCoinsResponse === null) {
            $request                = $this->getRequest();
            $supportedCoinsResponse = $request->get(
                sprintf(
                    '%1$s?%2$s',
                    $this->baseUrl,
                    http_build_query(
                        array(
                            self::QUERY_SUPPORTED_COINS => 'true',
                            self::QUERY_JSON            => 'true',
                        )
                    )
                ),
                $this->getRequestHeaders()
            );

            // store this response into the cache
            if ($this->cachePool !== null) {
                $cachedSupportedCoinValues = $this->cachePool->getItem('eobot_coin_values_supported');
                $cachedSupportedCoinValues->set($supportedCoinsResponse);
                $cachedSupportedCoinValues->expiresAfter(new \DateInterval('PT5M'));

                $this->cachePool->save($cachedSupportedCoinValues);
            }
        }

        $this->response = $supportedCoinsResponse;

        // get the response content
        $coinValuesInUsd = json_decode(trim($this->response->getContent()), true);
        if (is_array($coinValuesInUsd) && !empty($coinValuesInUsd)) {
            foreach ($coinValuesInUsd as $thisCoin => $values) {
                $thisCoin = strtoupper($thisCoin);

                if (self::isValidCoin($thisCoin) || self::isValidEobotInternalType($thisCoin) || self::isValidRentalType($thisCoin)) {
                    $coinValues[$thisCoin] = floatval($values['Price']);
                }
            }
        }

        // if the coin was not found in the all-supported-coins results, fetch the individual coin
        if (!isset($coinValues[$coin])) {
            $specificCoinResponse = null;

            // check whether the request for this specific coin was cached
            if (($this->cachePool !== null && $this->cachePool->hasItem('eobot_coin_value_' . $coin)) && !$forceFetch) {
                // retrieve USD value for this coin
                $cachedSpecificCoinValue = $this->cachePool->getItem('eobot_coin_value_' . $coin);

                if ($cachedSpecificCoinValue->isHit()) {
                    $specificCoinResponse = $cachedSpecificCoinValue->get();
                }
            }

            // if not, or we're asked to force-fetch a result, get the coin value from the server
            if ($specificCoinResponse === null) {
                $request              = $this->getRequest();
                $specificCoinResponse = $request->get(
                    sprintf(
                        '%1$s?%2$s',
                        $this->baseUrl,
                        http_build_query(
                            array(
                                self::QUERY_COIN => $coin,
                                self::QUERY_JSON => 'true',
                            )
                        )
                    ),
                    $this->getRequestHeaders()
                );

                // store this response into the cache
                if ($this->cachePool !== null) {
                    $cachedSpecificCoinValue = $this->cachePool->getItem('eobot_coin_value_' . $coin);
                    $cachedSpecificCoinValue->set($specificCoinResponse);
                    $cachedSpecificCoinValue->expiresAfter(new \DateInterval('PT5M'));

                    $this->cachePool->save($cachedSpecificCoinValue);
                }
            }

            $this->response = $specificCoinResponse;

            // get the response content
            $coinValueInUsd = json_decode(trim($this->response->getContent()), true);
            if (is_array($coinValueInUsd) && !empty($coinValueInUsd)) {
                foreach ($coinValueInUsd as $thisCoin => $value) {
                    $thisCoin = strtoupper($thisCoin);

                    if (self::isValidCoin($thisCoin) || self::isValidEobotInternalType($thisCoin) || self::isValidRentalType($thisCoin)) {
                        $coinValues[$thisCoin] = $value;
                    }
                }
            }
        }

        // if we still don't have a value for this coin, throw an exception
        if (!isset($coinValues[$coin])) {
            throw new \LogicException(
                sprintf(
                    '%1$s: Failed to retrieve value in USD for coin: %2$s',
                    __METHOD__,
                    $coin
                )
            );
        }

        $retValue = $coinValues[$coin];

        // check whether we were asked to retrieve a different currency
        if ($currency != self::CURRENCY_US_DOLLAR) {
            $response       = $this->response;
            $exchangeRate   = $this->getExchangeRate($currency);
            $this->response = $response;
            $retValue       = ($retValue * $exchangeRate);
        }

        return $retValue;
    }

    /**
     * This method retrieves the current exchange rate of the given currency in relation to US Dollars. This method
     * has two optional arguments: the currency to retrieve (this defaults to Euros) and whether or not to force a
     * fetch from the server (exchange rates are cached by default).
     *
     * <code>
     * $client = new Client();
     * $exchangeRate = $client->getExchangeRate(Client::CURRENCY_JAPANESE_YEN);
     * </code>
     *
     * @param string $currency
     * @param bool   $forceFetch (Optional) Defaults to false, which returns the cached result from a previous fetch
     * @throws \InvalidArgumentException|\LogicException
     * @return float
     */
    public function getExchangeRate($currency = self::CURRENCY_EURO, $forceFetch = false)
    {
        $retValue = 1.0;

        if ($currency != self::CURRENCY_US_DOLLAR) {
            if (!self::isValidCurrency($currency)) {
                throw new \InvalidArgumentException(
                    sprintf(
                        '%1$s: Invalid currency type given',
                        __METHOD__
                    )
                );
            }

            $exchangeRates               = array();
            $supportedCurrenciesResponse = null;

            // check whether we have the exchange rates from USD for all supported currencies cached
            if (($this->cachePool !== null && $this->cachePool->hasItem('eobot_exchange_rates_supported')) && !$forceFetch) {
                // retrieve exchange rates from USD for all supported currencies
                $cachedSupportedExchangeRates = $this->cachePool->getItem('eobot_exchange_rates_supported');

                if ($cachedSupportedExchangeRates->isHit()) {
                    $supportedCurrenciesResponse = $cachedSupportedExchangeRates->get();
                }
            }

            // if not, or we're asked to force-fetch a result, get the supported currencies from the server
            if ($supportedCurrenciesResponse === null) {
                $request                     = $this->getRequest();
                $supportedCurrenciesResponse = $request->get(
                    sprintf(
                        '%1$s?%2$s',
                        $this->baseUrl,
                        http_build_query(
                            array(
                                self::QUERY_SUPPORTED_CURRENCIES => 'true',
                                self::QUERY_JSON                 => 'true',
                            )
                        )
                    ),
                    $this->getRequestHeaders()
                );

                // store this response into the cache
                if ($this->cachePool !== null) {
                    $cachedSupportedExchangeRates = $this->cachePool->getItem('eobot_exchange_rates_supported');
                    $cachedSupportedExchangeRates->set($supportedCurrenciesResponse);
                    $cachedSupportedExchangeRates->expiresAfter(new \DateInterval('PT10M'));

                    $this->cachePool->save($cachedSupportedExchangeRates);
                }
            }

            $this->response = $supportedCurrenciesResponse;

            // get the response content
            $exchangeRatesFromUsd = json_decode(trim($this->response->getContent()), true);
            if (is_array($exchangeRatesFromUsd) && !empty($exchangeRatesFromUsd)) {
                foreach ($exchangeRatesFromUsd as $thisCurrency => $values) {
                    $thisCurrency = strtoupper($thisCurrency);

                    if (self::isValidCurrency($thisCurrency)) {
                        $exchangeRates[$thisCurrency] = floatval($values['Price']);
                    }
                }
            }

            // if the currency was not found in the all-supported-currencies results, fetch the individual currency
            if (!isset($exchangeRates[$currency])) {
                $specificCurrencyResponse = null;

                // check whether the request for this specific currency was cached
                if (($this->cachePool !== null && $this->cachePool->hasItem('eobot_exchange_rate_' . $currency)) && !$forceFetch) {
                    // retrieve exchange rate from USD for this currency
                    $cachedSpecificCurrency = $this->cachePool->getItem('eobot_exchange_rate_' . $currency);

                    if ($cachedSpecificCurrency->isHit()) {
                        $specificCurrencyResponse = $cachedSpecificCurrency->get();
                    }
                }

                // if not, or we're asked to force-fetch a result, get the exchange rate from the server
                if ($specificCurrencyResponse === null) {
                    $request                  = $this->getRequest();
                    $specificCurrencyResponse = $request->get(
                        sprintf(
                            '%1$s?%2$s',
                            $this->baseUrl,
                            http_build_query(
                                array(
                                    self::QUERY_COIN => $currency,
                                    self::QUERY_JSON => 'true',
                                )
                            )
                        ),
                        $this->getRequestHeaders()
                    );

                    // store this response into the cache
                    if ($this->cachePool !== null) {
                        $cachedSpecificCurrency = $this->cachePool->getItem('eobot_exchange_rate_' . $currency);
                        $cachedSpecificCurrency->set($specificCurrencyResponse);
                        $cachedSpecificCurrency->expiresAfter(new \DateInterval('PT10M'));

                        $this->cachePool->save($cachedSpecificCurrency);
                    }
                }

                $this->response = $specificCurrencyResponse;

                // get the response content
                $exchangeRateFromUsd = json_decode(trim($this->response->getContent()), true);
                if (is_array($exchangeRateFromUsd) && !empty($exchangeRateFromUsd)) {
                    foreach ($exchangeRateFromUsd as $thisCurrency => $value) {
                        $thisCurrency = strtoupper($thisCurrency);

                        if (self::isValidCurrency($thisCurrency)) {
                            $exchangeRates[$thisCurrency] = $value;
                        }
                    }
                }
            }

            // if we still don't have an exchange rate for this currency, throw an exception
            if (!isset($exchangeRates[$currency])) {
                throw new \LogicException(
                    sprintf(
                        '%1$s: Failed to retrieve exchange rate from USD for currency: %2$s',
                        __METHOD__,
                        $currency
                    )
                );
            }

            $retValue = $exchangeRates[$currency];
        }

        return $retValue;
    }

    /**
     * This method retrieves the current balance of a specific type for the current or given user. This method has
     * three optional arguments:
     *
     * * The type of balance to fetch, which can be a coin type, Eobot type or currency type. Defaults to null, which
     * fetches everything
     * * The Eobot user id to fetch the balances for, which is optional if it was passed to the constructor
     * * Whether or not to force a fetch from the server (results are cached by default)
     *
     * <code>
     * $client = new Client(1234);
     * $btcBalance = $client->getBalance(Client::COIN_BITCOIN);
     *
     * $client = new Client();
     * $ltcBalance = $client->getBalance(Client::COIN_LITECOIN, 1234);
     * </code>
     *
     * @param string $type       (Optional) Defaults to null, which returns everything
     * @param int    $userId     (Optional) Defaults to null
     * @param bool   $forceFetch (Optional) Defaults to false, which returns the cached result from a previous fetch
     * @throws \InvalidArgumentException|\LogicException
     * @return float|float[]
     */
    public function getBalance($type = null, $userId = null, $forceFetch = false)
    {
        if ($type !== null) {
            if (!self::isValidCoin($type) && !self::isValidEobotInternalType($type) && !self::isValidCurrency($type)) {
                throw new \InvalidArgumentException(
                    sprintf(
                        '%1$s: Invalid balance type given, it is not a valid coin type nor a valid currency type',
                        __METHOD__
                    )
                );
            }
        }

        if ($this->userId === null && $userId === null) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: No user ID given, and no user ID is known from the constructor',
                    __METHOD__
                )
            );
        }

        if ($userId !== null && preg_match('/[^0-9]/', $userId)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid user ID provided, must be numeric',
                    __METHOD__
                )
            );
        }

        if ($userId === null) {
            $userId = $this->userId;
        }

        $balances          = array();
        $balanceTotalInUsd = 0.0;
        $balancesResponse  = null;

        // check whether we have the balances for this user cached
        if (($this->cachePool !== null && $this->cachePool->hasItem('eobot_balance_all_' . $userId)) && !$forceFetch) {
            // retrieve the balances from cache
            $cachedBalances = $this->cachePool->getItem('eobot_balance_all_' . $userId);

            if ($cachedBalances->isHit()) {
                $balancesResponse = $cachedBalances->get();
            }
        }

        // if not, or we're asked to force-fetch a result, get the balances from the server
        if ($balancesResponse === null) {
            // retrieve the balances for the given user
            $request          = $this->getRequest();
            $balancesResponse = $request->get(
                sprintf(
                    '%1$s?%2$s',
                    $this->baseUrl,
                    http_build_query(
                        array(
                            self::QUERY_TOTAL => $userId,
                            self::QUERY_JSON  => 'true',
                        )
                    )
                ),
                $this->getRequestHeaders()
            );

            // store this response into the cache
            if ($this->cachePool !== null) {
                $cachedBalances = $this->cachePool->getItem('eobot_balance_all_' . $userId);
                $cachedBalances->set($balancesResponse);
                $cachedBalances->expiresAfter(new \DateInterval('PT1M'));

                $this->cachePool->save($cachedBalances);
            }
        }

        $this->response = $balancesResponse;

        // get the response content
        $allBalances = json_decode(trim($this->response->getContent()), true);
        if (is_array($allBalances) && !empty($allBalances)) {
            foreach ($allBalances as $thisItem => $balance) {
                $thisItem = strtoupper($thisItem);

                if ($thisItem == self::EO_CLOUD_SHA256_2_CONTRACT) {
                    $thisItem = self::EO_CLOUD_SHA256_2;
                } elseif ($thisItem == self::EO_CLOUD_SHA256_3_CONTRACT) {
                    $thisItem = self::EO_CLOUD_SHA256_3;
                } elseif ($thisItem == self::EO_CLOUD_FOLDING_CONTRACT) {
                    $thisItem = self::EO_CLOUD_FOLDING;
                } elseif ($thisItem == self::EO_CLOUD_SETI_CONTRACT) {
                    $thisItem = self::EO_CLOUD_SETI;
                }

                if (self::isValidCoin($thisItem) || self::isValidEobotInternalType($thisItem) || self::isValidRentalType($thisItem)) {
                    $balances[$thisItem] = floatval($balance);
                } elseif ($thisItem == 'TOTAL') {
                    $balanceTotalInUsd = floatval($balance);
                }
            }
        }

        if ($type === null) {
            if (empty($balances)) {
                throw new \LogicException(
                    sprintf(
                        '%1$s: Invalid API response, response does not contain any balances: %2$s',
                        __METHOD__,
                        $this->response->getContent()
                    )
                );
            }

            $retValue = $balances;
        } elseif (self::isValidCoin($type) && isset($balances[$type])) {
            $retValue = $balances[$type];
        } elseif (self::isValidCurrency($type)) {
            $response       = $this->response;
            $exchangeRate   = $this->getExchangeRate($type);
            $this->response = $response;
            $retValue       = ($balanceTotalInUsd * $exchangeRate);
        } else {
            if ($type == self::EO_CLOUD_SHA256_2_CONTRACT) {
                $type = self::EO_CLOUD_SHA256_2;
            }

            if ($type == self::EO_CLOUD_SHA256_3_CONTRACT) {
                $type = self::EO_CLOUD_SHA256_3;
            }

            if ($type == self::EO_CLOUD_FOLDING_CONTRACT) {
                $type = self::EO_CLOUD_FOLDING;
            }

            if ($type == self::EO_CLOUD_SETI_CONTRACT) {
                $type = self::EO_CLOUD_SETI;
            }

            if (self::isValidEobotInternalType($type) || self::isValidRentalType($type) && isset($balances[$type])) {
                $retValue = $balances[$type];
            } else {
                throw new \LogicException(
                    sprintf(
                        '%1$s: Invalid balance type given, it is not in the balance sheet returned by the API: %2$s',
                        __METHOD__,
                        $this->response->getContent()
                    )
                );
            }
        }

        return $retValue;
    }

    /**
     * This method returns the coin type currently being mined. It expects two optional parameters, which are the Eobot
     * user identifier (if it was not passed into the constructor, it is required here) and whether or not to force a
     * fetch from the server (response is cached for 1 minute by default).
     *
     * <code>
     * $client = new Client(1234);
     * $type = $client->getMiningMode();
     *
     * $client = new Client();
     * $type = $client->getMiningMode(1234);
     * </code>
     *
     * @param int  $userId     (Optional) Defaults to null
     * @param bool $forceFetch (Optional) Defaults to false
     * @throws \InvalidArgumentException|\LogicException
     * @return string
     */
    public function getMiningMode($userId = null, $forceFetch = false)
    {
        if ($this->userId === null && $userId === null) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: No user ID given, and no user ID is known from the constructor',
                    __METHOD__
                )
            );
        }

        if ($userId !== null && preg_match('/[^0-9]/', $userId)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid user ID provided, must be numeric',
                    __METHOD__
                )
            );
        }

        if ($userId === null) {
            $userId = $this->userId;
        }

        $miningModeResponse = null;

        // check whether we have the mining mode for this user cached
        if (($this->cachePool !== null && $this->cachePool->hasItem('eobot_mining_mode_' . $userId)) && !$forceFetch) {
            // retrieve the mining mode from cache
            $cachedMiningMode = $this->cachePool->getItem('eobot_mining_mode_' . $userId);

            if ($cachedMiningMode->isHit()) {
                $miningModeResponse = $cachedMiningMode->get();
            }
        }

        // if not, or we're asked to force-fetch a result, get the mining mode from the server
        if ($miningModeResponse === null) {
            // retrieve the type currently being mined by the given user
            $request            = $this->getRequest();
            $miningModeResponse = $request->get(
                sprintf(
                    '%1$s?%2$s',
                    $this->baseUrl,
                    http_build_query(
                        array(
                            self::QUERY_IDMINING => $userId,
                            self::QUERY_JSON     => 'true',
                        )
                    )
                ),
                $this->getRequestHeaders()
            );

            // store this response into the cache
            if ($this->cachePool !== null) {
                $cachedMiningMode = $this->cachePool->getItem('eobot_mining_mode_' . $userId);
                $cachedMiningMode->set($miningModeResponse);
                $cachedMiningMode->expiresAfter(new \DateInterval('PT1M'));

                $this->cachePool->save($cachedMiningMode);
            }
        }

        $this->response = $miningModeResponse;

        // get the response content
        $miningData = json_decode(trim($this->response->getContent()), true);

        $retValue = null;
        if (is_array($miningData) && !empty($miningData)) {
            $retValue = strtoupper($miningData['mining']);
        }

        if ($retValue == self::EO_CLOUD_SHA256_2_CONTRACT) {
            $retValue = self::EO_CLOUD_SHA256_2;
        }

        if ($retValue == self::EO_CLOUD_SHA256_3_CONTRACT) {
            $retValue = self::EO_CLOUD_SHA256_3;
        }

        if ($retValue == self::EO_CLOUD_FOLDING_CONTRACT) {
            $retValue = self::EO_CLOUD_FOLDING;
        }

        if ($retValue == self::EO_CLOUD_SETI_CONTRACT) {
            $retValue = self::EO_CLOUD_SETI;
        }

        if (!self::isValidCoin($retValue) && !self::isValidEobotInternalType($retValue) && !self::isValidRentalType($retValue)) {
            throw new \LogicException(
                sprintf(
                    '%1$s: Invalid API response received, given response is not a valid coin type: %2$s',
                    __METHOD__,
                    $retValue
                )
            );
        }

        return $retValue;
    }

    /**
     * This method returns the current mining and cloud speeds. It expects two optional parameter, which are the Eobot
     * user identifier (if the user identifier was not passed into the constructor, it is required here) and whether or
     * not to fetch a fresh result from the server (response is cached for 10 minutes by default). This method returns
     * an array with five values:
     *
     * * MiningSHA-256: The current mining speed for SHA-256 mining (in GHS)
     * * MiningScrypt : The current mining speed for SCRYPT mining (in MHS)
     * * Cloud2SHA-256: The current Eobot cloud mining speed for SHA-256 v2 mining (in GHS)
     * * CloudSHA-256 : The current Eobot cloud mining speed for SHA-256 v3 mining (in GHS)
     * * CloudScrypt  : The current Eobot cloud mining speed for SCRYPT mining (in MHS)
     *
     * @param int  $userId     (Optional) Defaults to null
     * @param bool $forceFetch (Optional) Defaults to false
     * @throws \InvalidArgumentException|\LogicException
     * @return array
     */
    public function getSpeed($userId = null, $forceFetch = false)
    {
        if ($this->userId === null && $userId === null) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: No user ID given, and no user ID is known from the constructor',
                    __METHOD__
                )
            );
        }

        if ($userId !== null && preg_match('/[^0-9]/', $userId)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid user ID provided, must be numeric',
                    __METHOD__
                )
            );
        }

        if ($userId === null) {
            $userId = $this->userId;
        }

        $retValue = array(
            'MiningSHA-256' => 0.0,
            'MiningScrypt'  => 0.0,
            'CloudSHA-256'  => 0.0,
            'Cloud2SHA-256' => 0.0,
            'CloudScrypt'   => 0.0,
        );

        $miningSpeedsResponse = null;

        // check whether we have the mining speeds for this user cached
        if (($this->cachePool !== null && $this->cachePool->hasItem('eobot_mining_speeds_' . $userId)) && !$forceFetch) {
            // retrieve the mining speeds from cache
            $cachedMiningSpeeds = $this->cachePool->getItem('eobot_mining_speeds_' . $userId);

            if ($cachedMiningSpeeds->isHit()) {
                $miningSpeedsResponse = $cachedMiningSpeeds->get();
            }
        }

        // if not, or we're asked to force-fetch a result, get the mining speeds from the server
        if ($miningSpeedsResponse === null) {
            // retrieve the current mining speeds for the given user
            $request              = $this->getRequest();
            $miningSpeedsResponse = $request->get(
                sprintf(
                    '%1$s?%2$s',
                    $this->baseUrl,
                    http_build_query(
                        array(
                            self::QUERY_IDSPEED => $userId,
                            self::QUERY_JSON    => 'true',
                        )
                    )
                ),
                $this->getRequestHeaders()
            );

            // store this response into the cache
            if ($this->cachePool !== null) {
                $cachedMiningSpeeds = $this->cachePool->getItem('eobot_mining_speeds_' . $userId);
                $cachedMiningSpeeds->set($miningSpeedsResponse);
                $cachedMiningSpeeds->expiresAfter(new \DateInterval('PT10M'));

                $this->cachePool->save($cachedMiningSpeeds);
            }
        }

        $this->response = $miningSpeedsResponse;

        // get the response content
        $miningSpeeds = json_decode(trim($this->response->getContent()), true);
        if (is_array($miningSpeeds) && !empty($miningSpeeds)) {
            foreach ($miningSpeeds as $type => $speed) {
                if (array_key_exists($type, $retValue)) {
                    $retValue[$type] = floatval($speed);
                }
            }
        } else {
            throw new \LogicException(
                sprintf(
                    '%1$s: Invalid API response received, given response does not contain mining speeds: %2$s',
                    __METHOD__,
                    $this->response->getContent()
                )
            );
        }

        return $retValue;
    }

    /**
     * This method returns the current mining and cloud estimates. It expects three optional parameters, which are the
     * currency to return the results in, the Eobot user identifier (if the user identifier was not passed into the
     * constructor, it is required here) and whether or not to force fetch a fresh result from the server (response is
     * cached for 10 minutes by default). This method returns an array with five values: the estimated monthly income
     * for SHA-256 mining, Scrypt mining, Eobot cloud SHA-256 mining, Eobot cloud SHA-256 v2 mining and for Eobot cloud
     * Scrypt mining.
     *
     * @param string $currency   (Optional) Defaults to US Dollars
     * @param int    $userId     (Optional) Defaults to null
     * @param bool   $forceFetch (Optional) Defaults to false
     * @throws \InvalidArgumentException|\LogicException
     * @return array
     */
    public function getEstimates($currency = self::CURRENCY_US_DOLLAR, $userId = null, $forceFetch = false)
    {
        if (!$this->isValidCurrency($currency)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid currency given',
                    __METHOD__
                )
            );
        }

        if ($this->userId === null && $userId === null) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: No user ID given, and no user ID is known from the constructor',
                    __METHOD__
                )
            );
        }

        if ($userId !== null && preg_match('/[^0-9]/', $userId)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid user ID provided, must be numeric',
                    __METHOD__
                )
            );
        }

        if ($userId === null) {
            $userId = $this->userId;
        }

        $retValue = array(
            'MiningSHA-256' => 0.0,
            'MiningScrypt'  => 0.0,
            'CloudSHA-256'  => 0.0,
            'Cloud2SHA-256' => 0.0,
            'CloudScrypt'   => 0.0,
        );

        $miningEstimatesResponse = null;

        // check whether we have the mining estimates for this user cached
        if (($this->cachePool !== null && $this->cachePool->hasItem('eobot_mining_estimates_' . $userId)) && !$forceFetch) {
            // retrieve the mining estimates from cache
            $cachedMiningEstimates = $this->cachePool->getItem('eobot_mining_estimates_' . $userId);

            if ($cachedMiningEstimates->isHit()) {
                $miningEstimatesResponse = $cachedMiningEstimates->get();
            }
        }

        // if not, or we're asked to force-fetch a result, get the mining estimates from the server
        if ($miningEstimatesResponse === null) {
            // retrieve the current mining estimates for the given user
            $request                 = $this->getRequest();
            $miningEstimatesResponse = $request->get(
                sprintf(
                    '%1$s?%2$s',
                    $this->baseUrl,
                    http_build_query(
                        array(
                            self::QUERY_IDESTIMATES => $userId,
                            self::QUERY_JSON        => 'true',
                        )
                    )
                ),
                $this->getRequestHeaders()
            );

            // store this response into the cache
            if ($this->cachePool !== null) {
                $cachedMiningEstimates = $this->cachePool->getItem('eobot_mining_estimates_' . $userId);
                $cachedMiningEstimates->set($miningEstimatesResponse);
                $cachedMiningEstimates->expiresAfter(new \DateInterval('PT10M'));

                $this->cachePool->save($cachedMiningEstimates);
            }
        }

        $this->response = $miningEstimatesResponse;

        // get the response content
        $miningEstimates = json_decode(trim($this->response->getContent()), true);
        if (is_array($miningEstimates) && !empty($miningEstimates)) {
            foreach ($miningEstimates as $type => $estimate) {
                if (array_key_exists($type, $retValue)) {
                    $retValue[$type] = floatval($estimate);
                }
            }
        } else {
            throw new \LogicException(
                sprintf(
                    '%1$s: Invalid API response received, given response does not contain mining estimates: %2$s',
                    __METHOD__,
                    $this->response->getContent()
                )
            );
        }

        if ($currency != self::CURRENCY_US_DOLLAR) {
            $exchangeRate = $this->getExchangeRate($currency);

            foreach ($retValue as $estimate => $value) {
                $retValue[$estimate] = $value * $exchangeRate;
            }
        }

        return $retValue;
    }

    /**
     * This method returns a deposit wallet for the given coin type, which can be used to transfer funds to Eobot. It
     * expects two optional parameters, which are the coin type to request a deposit address for and the Eobot
     * user identifier. If the user identifier was not passed into the constructor, it is required here. This method
     * returns a wallet address as a string.
     *
     * @param string $coinType (Optional) Defaults to Bitcoin
     * @param string $userId   (Optional) Defaults to null, only required if not set via the constructor
     * @throws \InvalidArgumentException|\LogicException
     * @return string
     */
    public function getDepositAddress($coinType = self::COIN_BITCOIN, $userId = null)
    {
        if (!self::isValidCoin($coinType)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid coin type given',
                    __METHOD__
                )
            );
        }

        if ($this->userId === null && $userId === null) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: No user ID given, and no user ID is known from the constructor',
                    __METHOD__
                )
            );
        }

        if ($userId !== null && preg_match('/[^0-9]/', $userId)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid user ID provided, must be numeric',
                    __METHOD__
                )
            );
        }

        if ($userId === null) {
            $userId = $this->userId;
        }

        $depositResponse = null;

        // check whether we have the deposit address for this user and coin cached
        if ($this->cachePool !== null && $this->cachePool->hasItem('eobot_deposit_address_' . $userId . '_' . $coinType)) {
            // retrieve the deposit address from cache
            $cachedDepositAddress = $this->cachePool->getItem('eobot_deposit_address_' . $userId . '_' . $coinType);

            if ($cachedDepositAddress->isHit()) {
                $depositResponse = $cachedDepositAddress->get();
            }
        }

        // if not, get the deposit address from the server
        if ($depositResponse === null) {
            // retrieve the deposit address for the given user and coin type
            $request         = $this->getRequest();
            $depositResponse = $request->get(
                sprintf(
                    '%1$s?%2$s',
                    $this->baseUrl,
                    http_build_query(
                        array(
                            self::QUERY_ID      => $userId,
                            self::QUERY_DEPOSIT => $coinType,
                            self::QUERY_JSON    => 'true',
                        )
                    )
                ),
                $this->getRequestHeaders()
            );

            // store this response into the cache
            if ($this->cachePool !== null) {
                $cachedDepositAddress = $this->cachePool->getItem('eobot_deposit_address_' . $userId . '_' . $coinType);
                $cachedDepositAddress->set($depositResponse);
                $cachedDepositAddress->expiresAfter(new \DateInterval('PT30M'));

                $this->cachePool->save($cachedDepositAddress);
            }
        }

        $this->response = $depositResponse;

        // get the response content
        $retValue       = '';
        $depositAddress = json_decode(trim($this->response->getContent()), true);
        if (is_array($depositAddress) && !empty($depositAddress)) {
            foreach ($depositAddress as $type => $address) {
                $type = strtoupper($type);

                if ($type == $coinType) {
                    $retValue = $address;
                }
            }
        }

        return $retValue;
    }

    /**
     * This method is used to retrieve the user's Eobot user ID. If an email address and password are given, the user
     * ID is retrieved via the Eobot API. Otherwise, the user ID set when instantiating the Client is returned. If no
     * user ID was given to the client, and no email address and password are given, an exception is thrown.
     *
     * @param string $email    (Optional) Defaults to null, required if the user ID was not set via the constructor
     * @param string $password (Optional) Defaults to null, required if the user ID was not set via the constructor, or
     *                         if $email is set
     * @throws \LogicException
     * @return int
     */
    public function getUserId($email = null, $password = null)
    {
        if ($this->userId === null) {
            if ($email === null || strlen($email) == 0) {
                throw new \LogicException(
                    sprintf(
                        '%1$s: No email address given, but it is required when no user ID is set',
                        __METHOD__
                    )
                );
            }
        }

        if ($this->userId === null || $email !== null) {
            if ($password === null || strlen($password) == 0) {
                throw new \LogicException(
                    sprintf(
                        '%1$s: No password given, but it is required when a user ID is being fetched from Eobot',
                        __METHOD__
                    )
                );
            }
        }

        $retValue = $this->userId;

        if ($email !== null && $password !== null) {
            // fetch the user ID from Eobot
            $request        = $this->getRequest();
            $this->response = $request->post(
                $this->baseUrl,
                $this->getRequestHeaders(),
                http_build_query(
                    array(
                        self::QUERY_EMAIL    => $email,
                        self::QUERY_PASSWORD => $password,
                    )
                )
            );

            $retValue = trim($this->response->getContent());

            if (strlen($retValue) == 0) {
                throw new \LogicException(
                    sprintf(
                        '%1$s: Invalid password given for email address "%2$s"',
                        __METHOD__,
                        $email
                    )
                );
            }
        }

        return intval($retValue);
    }

    /**
     * This method is used to set the mining mode for the given user. Because this is a change in settings, the user's
     * email address and password are required parameters.
     *
     * @param string $type   (Optional) Defaults to Bitcoin
     * @param string $email
     * @param string $password
     * @param string $userId (Optional) Defaults to null, only required if not set via the constructor
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function setMiningMode($type = self::COIN_BITCOIN, $email, $password, $userId = null)
    {
        if (!self::isValidCoin($type) && !self::isValidEobotInternalType($type)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid mining type given, it is not a valid coin or Eobot type',
                    __METHOD__
                )
            );
        }

        if ($this->userId === null && $userId === null) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: No user ID given, and no user ID is known from the constructor',
                    __METHOD__
                )
            );
        }

        if ($userId !== null && preg_match('/[^0-9]/', $userId)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid user ID provided, must be numeric',
                    __METHOD__
                )
            );
        }

        if ($userId === null) {
            $userId = $this->userId;
        }

        if ($type == self::EO_CLOUD_SHA256_2_CONTRACT) {
            $type = self::EO_CLOUD_SHA256_2;
        }

        if ($type == self::EO_CLOUD_SHA256_3_CONTRACT) {
            $type = self::EO_CLOUD_SHA256_3;
        }

        if ($type == self::EO_CLOUD_FOLDING_CONTRACT) {
            $type = self::EO_CLOUD_FOLDING;
        }

        if ($type == self::EO_CLOUD_SETI_CONTRACT) {
            $type = self::EO_CLOUD_SETI;
        }

        // get the current mining mode
        $currentMiningMode = $this->getMiningMode($userId, true);

        // check whether we're trying to set the same mining mode
        if ($currentMiningMode == $type) {
            return true;
        }

        // switch the mining mode
        $request        = $this->getRequest();
        $this->response = $request->post(
            $this->baseUrl,
            $this->getRequestHeaders(),
            http_build_query(
                array(
                    self::QUERY_ID       => $userId,
                    self::QUERY_MINING   => $type,
                    self::QUERY_EMAIL    => $email,
                    self::QUERY_PASSWORD => $password,
                )
            )
        );

        // check whether the mining mode was successfully changed
        return ($this->getMiningMode($userId, true) == $type);
    }

    /**
     * This method is used to configure the automatic withdrawal of funds from Eobot to your own (or someone else's)
     * wallet. Because this is a change in settings, the user's email address and password are required parameters.
     *
     * @param string    $coinType (Optional) Defaults to Bitcoin
     * @param int|float $amount   (Optional) Defaults to 1.0
     * @param string    $wallet   Wallet address to send the funds to
     * @param string    $email
     * @param string    $password
     * @param string    $userId   (Optional) Defaults to null, only required if not set via the constructor
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function setAutomaticWithdraw(
        $coinType = self::COIN_BITCOIN,
        $amount = 1.0,
        $wallet,
        $email,
        $password,
        $userId = null
    ) {
        if (!self::isValidCoin($coinType)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid coin type given, it is not a valid coin type',
                    __METHOD__
                )
            );
        }

        if ((!is_int($amount) && !is_double($amount) && !is_float($amount)) || $amount <= 0) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid amount given, it must be an integer or float greater than zero',
                    __METHOD__
                )
            );
        }

        if ($this->userId === null && $userId === null) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: No user ID given, and no user ID is known from the constructor',
                    __METHOD__
                )
            );
        }

        if ($userId !== null && preg_match('/[^0-9]/', $userId)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid user ID provided, must be numeric',
                    __METHOD__
                )
            );
        }

        if ($userId === null) {
            $userId = $this->userId;
        }

        // switch the mining mode
        $request        = $this->getRequest();
        $this->response = $request->post(
            $this->baseUrl,
            $this->getRequestHeaders(),
            http_build_query(
                array(
                    self::QUERY_ID       => $userId,
                    self::QUERY_WITHDRAW => $coinType,
                    self::QUERY_AMOUNT   => $amount,
                    self::QUERY_WALLET   => $wallet,
                    self::QUERY_EMAIL    => $email,
                    self::QUERY_PASSWORD => $password,
                )
            )
        );

        $result = trim($this->response->getContent());

        // unfortunately, the API accepts anything you throw at it, and there's
        // no read-method to verify that the change was successful
        return ($result == '');
    }

    /**
     * This method is used to withdraw funds from Eobot to your own (or someone else's) wallet. Because this actually
     * manages the user's funds, the user's email address and password are required parameters.
     *
     * @param string    $coinType (Optional) Defaults to Bitcoin
     * @param int|float $amount   (Optional) Defaults to 1.0
     * @param string    $wallet   Wallet address to send the funds to
     * @param string    $email
     * @param string    $password
     * @param string    $userId   (Optional) Defaults to null, only required if not set via the constructor
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function withdrawFunds(
        $coinType = self::COIN_BITCOIN,
        $amount = 1.0,
        $wallet,
        $email,
        $password,
        $userId = null
    ) {
        if (!self::isValidCoin($coinType)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid coin type given, it is not a valid coin type',
                    __METHOD__
                )
            );
        }

        if ((!is_int($amount) && !is_double($amount) && !is_float($amount)) || $amount <= 0) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid amount given, it must be an integer or float greater than zero',
                    __METHOD__
                )
            );
        }

        if ($this->userId === null && $userId === null) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: No user ID given, and no user ID is known from the constructor',
                    __METHOD__
                )
            );
        }

        if ($userId !== null && preg_match('/[^0-9]/', $userId)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid user ID provided, must be numeric',
                    __METHOD__
                )
            );
        }

        if ($userId === null) {
            $userId = $this->userId;
        }

        // get the current balance for this coin
        $oldBalance = $this->getBalance($coinType, $userId, true);

        // switch the mining mode
        $request        = $this->getRequest();
        $this->response = $request->post(
            $this->baseUrl,
            $this->getRequestHeaders(),
            http_build_query(
                array(
                    self::QUERY_ID              => $userId,
                    self::QUERY_MANUAL_WITHDRAW => $coinType,
                    self::QUERY_AMOUNT          => $amount,
                    self::QUERY_WALLET          => $wallet,
                    self::QUERY_EMAIL           => $email,
                    self::QUERY_PASSWORD        => $password,
                )
            )
        );

        // unfortunately, the API does not return a result that indicates whether the withdraw was successful
        $newBalance = $this->getBalance($coinType, $userId, true);

        return ($newBalance < $oldBalance);
    }

    /**
     * This method is used to purchase Eobot mining power using mined (or deposited) coins. Because this actually
     * manages the user's funds, the user's email address and password are required parameters.
     *
     * @param string    $coinType  (Optional) Defaults to Bitcoin
     * @param int|float $amount    (Optional) Defaults to 1.0
     * @param string    $cloudType (Optional) Defaults to SHA-256
     * @param string    $email
     * @param string    $password
     * @param string    $userId    (Optional) Defaults to null, only required if not set via the constructor
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function convertCoinToCloud(
        $coinType = self::COIN_BITCOIN,
        $amount = 1.0,
        $cloudType = self::EO_CLOUD_SHA256_3,
        $email,
        $password,
        $userId = null
    ) {
        if (!self::isValidCoin($coinType) && !self::isValidEobotInternalType($coinType)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid coin type given',
                    __METHOD__
                )
            );
        }

        if ((!is_int($amount) && !is_double($amount) && !is_float($amount)) || $amount <= 0) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid amount given, it must be an integer or float greater than zero',
                    __METHOD__
                )
            );
        }

        if (!self::isValidEobotInternalType($cloudType) && !self::isValidRentalType($cloudType)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid cloud type given, it is not a valid Eobot mining service',
                    __METHOD__
                )
            );
        }

        if ($coinType == $cloudType) {
            throw new \LogicException(
                sprintf(
                    '%1$s: Cannot convert a cloud type to itself',
                    __METHOD__
                )
            );
        }

        if ($this->userId === null && $userId === null) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: No user ID given, and no user ID is known from the constructor',
                    __METHOD__
                )
            );
        }

        if ($userId !== null && preg_match('/[^0-9]/', $userId)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid user ID provided, must be numeric',
                    __METHOD__
                )
            );
        }

        if ($userId === null) {
            $userId = $this->userId;
        }

        if ($coinType == self::EO_CLOUD_SHA256_2_CONTRACT) {
            $coinType = self::EO_CLOUD_SHA256_2;
        }

        if ($coinType == self::EO_CLOUD_SHA256_3_CONTRACT) {
            $coinType = self::EO_CLOUD_SHA256_3;
        }

        if ($coinType == self::EO_CLOUD_FOLDING_CONTRACT) {
            $coinType = self::EO_CLOUD_FOLDING;
        }

        if ($coinType == self::EO_CLOUD_SETI_CONTRACT) {
            $coinType = self::EO_CLOUD_SETI;
        }

        if ($cloudType == self::EO_CLOUD_SHA256_2_CONTRACT) {
            $cloudType = self::EO_CLOUD_SHA256_2;
        }

        if ($cloudType == self::EO_CLOUD_SHA256_3_CONTRACT) {
            $cloudType = self::EO_CLOUD_SHA256_3;
        }

        if ($cloudType == self::EO_CLOUD_FOLDING_CONTRACT) {
            $cloudType = self::EO_CLOUD_FOLDING;
        }

        if ($cloudType == self::EO_CLOUD_SETI_CONTRACT) {
            $cloudType = self::EO_CLOUD_SETI;
        }

        // get the current balance for the coin we're going to convert
        $oldBalance = $this->getBalance($coinType, $userId, true);

        // purchase mining power
        $request        = $this->getRequest();
        $this->response = $request->post(
            $this->baseUrl,
            $this->getRequestHeaders(),
            http_build_query(
                array(
                    self::QUERY_ID           => $userId,
                    self::QUERY_CONVERT_FROM => $coinType,
                    self::QUERY_AMOUNT       => $amount,
                    self::QUERY_CONVERT_TO   => $cloudType,
                    self::QUERY_EMAIL        => $email,
                    self::QUERY_PASSWORD     => $password,
                )
            )
        );

        // check whether the balance has been successfully reduced
        return ($this->getBalance($coinType, $userId, true) < $oldBalance);
    }

    /**
     * This method checks the given value against the defined coin types. It returns a boolean `true` or `false`.
     *
     * <code>
     * $isValid = Client::isValidCoin(Client::COIN_BTC);
     * </code>
     *
     * @param string $coin
     * @return bool
     */
    public static function isValidCoin($coin)
    {
        $retValue = false;

        // get all constants defined in this class
        $reflectionClass = new \ReflectionClass(get_class());
        $constants       = $reflectionClass->getConstants();

        // if it contains the given value as a COIN_* or EO_* constant, the value is valid
        foreach ($constants as $constantName => $constantValue) {
            if (substr($constantName, 0, 5) == 'COIN_' && $constantValue == $coin) {
                $retValue = true;
                break;
            }
        }

        return $retValue;
    }

    /**
     * This method checks the given value against the defined currency types. It returns a boolean `true` or `false`.
     *
     * <code>
     * $isValid = Client::isValidCurrency(Client::CURRENCY_EUROS);
     * </code>
     *
     * @param string $currency
     * @return bool
     */
    public static function isValidCurrency($currency)
    {
        $retValue = false;

        // get all constants defined in this class
        $reflectionClass = new \ReflectionClass(get_class());
        $constants       = $reflectionClass->getConstants();

        // if it contains the given value as a CURR_* constant, the value is valid
        foreach ($constants as $constantName => $constantValue) {
            if (substr($constantName, 0, 9) == 'CURRENCY_' && $constantValue == $currency) {
                $retValue = true;
                break;
            }
        }

        return $retValue;
    }

    /**
     * This method checks the given value against the defined internal Eobot mining types. It returns a boolean `true`
     * or `false`.
     *
     * <code>
     * $isValid = Client::isValidEobotInternalType(Client::EO_CLOUD_SHA256_3);
     * </code>
     *
     * @param string $type
     * @return bool
     */
    public static function isValidEobotInternalType($type)
    {
        $retValue = false;

        // get all constants defined in this class
        $reflectionClass = new \ReflectionClass(get_class());
        $constants       = $reflectionClass->getConstants();

        // if it contains the given value as a EO_* constant, the value is valid
        foreach ($constants as $constantName => $constantValue) {
            if (substr($constantName, 0, 3) == 'EO_' && $constantValue == $type) {
                $retValue = true;
                break;
            }
        }

        return $retValue;
    }

    /**
     * This method checks the given value against the defined Eobot mining rental types. It returns a boolean `true`
     * or `false`.
     *
     * <code>
     * $isValid = Client::isValidRentalType(Client::RENTAL_SCRYPT);
     * </code>
     *
     * @param string $type
     * @return bool
     */
    public static function isValidRentalType($type)
    {
        $retValue = false;

        // get all constants defined in this class
        $reflectionClass = new \ReflectionClass(get_class());
        $constants       = $reflectionClass->getConstants();

        // if it contains the given value as a RENTAL_* constant, the value is valid
        foreach ($constants as $constantName => $constantValue) {
            if (substr($constantName, 0, 7) == 'RENTAL_' && $constantValue == $type) {
                $retValue = true;
                break;
            }
        }

        return $retValue;
    }

    /**
     * This method should really only be used if your server is having problems verifying the SSL certificates used by
     * Eobot. Disabling the SSL verification opens the door to potential man-in-the-middle attacks by not checking
     * whether the SSL certificate has been spoofed. But, if you **really** want to do this, just call this method. It
     * takes no arguments and returns nothing.
     *
     * <code>
     * $client = new Client();
     * $client->disableSslVerification();
     * </code>
     *
     * @return void
     */
    public function disableSslVerification()
    {
        $this->validateSsl = false;
    }

    /**
     * This method can be used to configure the connection timeout. Provide an integer in seconds.
     *
     * <code>
     * $client = new Client();
     * $client->setTimeout(10);
     * </code>
     *
     * @param int $timeout
     * @return void
     */
    public function setTimeout($timeout)
    {
        $this->timeout = (int)$timeout;
    }

    /**
     * This method replaces the cache pool set via the constructor.
     *
     * @param CacheItemPoolInterface $cachePool (Optional) Defaults to null, which disables caching
     * @return void
     */
    public function setCachePool(CacheItemPoolInterface $cachePool = null)
    {
        $this->cachePool = $cachePool;
    }

    /**
     * This method is used internally to retrieve a Browser object.
     *
     * <code>
     * $request = $this->getRequest();
     * </code>
     *
     * @return Browser
     *
     * Unittests overwrite this method to retrieve a mock request, so
     * @codeCoverageIgnore
     */
    protected function getRequest()
    {
        $retValue = new Browser(new Curl());
        $retValue->getClient()
                 ->setTimeout($this->timeout);
        $retValue->getClient()
                 ->setVerifyPeer($this->validateSsl);

        return $retValue;
    }

    /**
     * This method is used internally to build the default request headers.
     *
     * <code>
     * $headers = $this->getRequestHeaders();
     * </code>
     *
     * @return array
     */
    protected function getRequestHeaders()
    {
        return array(
            'User-Agent' => 'RickDenHaan-Eobot/1.9.0 (+http://github.com/rickdenhaan/eobot-php)',
        );
    }

    /**
     * This method returns the Response object returned for the last request, in case the method itself returned
     * something else. It accepts no arguments.
     *
     * <code>
     * $client = new Client(1234);
     * $mining = $client->getMiningMode();
     * $response = $client->getLastResponse();
     * </code>
     *
     * @return \Buzz\Message\Response|null
     */
    public function getLastResponse()
    {
        return $this->response;
    }
}
