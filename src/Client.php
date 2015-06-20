<?php
namespace RickDenHaan\Eobot;

use Buzz\Browser;

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
     * The coin abbreviation for BitSharesX
     */
    const COIN_BITSHARESX = 'BTS';

    /**
     * The coin abbreviation for CureCoin
     */
    const COIN_CURECOIN = 'CURE';

    /**
     * The coin abbreviation for Storjcoin X
     */
    const COIN_STORJCOIN_X = 'SJCX';

    /**
     * The coin abbreviation for Monero
     */
    const COIN_MONERO = 'XMR';

    /**
     * The coin abbreviation for Counterparty
     */
    const COIN_COUNTERPARTY = 'XCP';

    /**
     * The coin abbreviation for Stellar
     */
    const COIN_STELLAR = 'STR';

    /**
     * The coin abbreviation for Bytecoin
     */
    const COIN_BYTECOIN = 'BCN';

    /**
     * The coin abbreviation for Peercoin
     */
    const COIN_PEERCOIN = 'PPC';

    /**
     * The coin abbreviation for NXT
     */
    const COIN_NXT = 'NXT';

    /**
     * The coin abbreviation for MaidSafeCoin
     */
    const COIN_MAIDSAFECOIN = 'MAID';

    /**
     * The Eobot abbreviation for their Cloud SHA-256 miners
     */
    const EO_CLOUD_SHA256          = 'GHS';
    const EO_CLOUD_SHA256_CONTRACT = 'GHSCONTRACT';

    /**
     * The Eobot abbreviation for their 2nd generation Cloud SHA-256 miners
     */
    const EO_CLOUD_SHA256_2          = 'GHS2';
    const EO_CLOUD_SHA256_2_CONTRACT = 'GHS2CONTRACT';

    /**
     * The Eobot abbreviation for their Cloud Scrypt miners
     */
    const EO_CLOUD_SCRYPT          = 'SCRYPT';
    const EO_CLOUD_SCRYPT_CONTRACT = 'SCRYPTCONTRACT';

    /**
     * The Eobot abbreviation for their Cloud Folding service
     */
    const EO_CLOUD_FOLDING          = 'PPD';
    const EO_CLOUD_FOLDING_CONTRACT = 'PPDCONTRACT';

    /**
     * The currency abbreviation for Euro
     */
    const CURRENCY_EURO = 'EUR';

    /**
     * The currency abbreviation for US Dollar
     */
    const CURRENCY_US_DOLLAR = 'USD';

    /**
     * The currency abbreviation for Russian Ruble
     */
    const CURRENCY_RUSSIAN_RUBLE = 'RUB';

    /**
     * The currency abbreviation for British Pound
     */
    const CURRENCY_BRITISH_POUND = 'GBP';

    /**
     * The currency abbreviation for Indonesian Rupiah
     */
    const CURRENCY_INDONESIAN_RUPIAH = 'IDR';

    /**
     * The currency abbreviation for Canadian Dollar
     */
    const CURRENCY_CANADIAN_DOLLAR = 'CAD';

    /**
     * The currency abbreviation for Australian Dollar
     */
    const CURRENCY_AUSTRALIAN_DOLLAR = 'AUD';

    /**
     * The currency abbreviation for Japanese Yen
     */
    const CURRENCY_JAPANESE_YEN = 'JPY';

    /**
     * The currency abbreviation for Mexican Peso
     */
    const CURRENCY_MEXICAN_PESO = 'MXN';

    /**
     * The currency abbreviation for Chinese Yuan Renminbi
     */
    const CURRENCY_CHINESE_YUAN_RENMINBI = 'CNY';

    /**
     * The currency abbreviation for Czech Koruna
     */
    const CURRENCY_CZECH_KORUNA = 'CZK';

    /**
     * The currency abbreviation for Norwegian Krone
     */
    const CURRENCY_NORWEGIAN_KRONE = 'NOK';

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
     * The Eobot abbreviation for their 24-hour Cloud SHA-256 miner rental service
     */
    const RENTAL_SHA256 = 'GHSTEMP';

    /**
     * The Eobot abbreviation for their 24-hour Cloud Scrypt miner rental service
     */
    const RENTAL_SCRYPT = 'SCRYPTTEMP';

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
     * Local cache of exchange rates
     *
     * @type array
     */
    protected $exchangeRates = array();

    /**
     * Local cache of coin values
     *
     * @type array
     */
    protected $coinValues = array();

    /**
     * Local cache of coin balances
     *
     * @type array
     */
    protected $balances = array();

    /**
     * A boolean indicator which can be set to false so that the Eobot SSL certificate is not verified. **Not
     * recommended.**
     *
     * @type bool
     */
    private $validateSsl = true;

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
     * @param int $userId (Optional) Defaults to null
     * @throws \InvalidArgumentException
     */
    public function __construct($userId = null)
    {
        if ($userId !== null && preg_match('/[^0-9]/', $userId)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid user ID provided, must be numeric',
                    __METHOD__
                )
            );
        }

        $this->userId = $userId;
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

        // check whether we have the coin value in USD cached
        if (!isset($this->coinValues[$coin]) || $forceFetch) {
            // retrieve USD values for all coins
            $request        = $this->getRequest();
            $this->response = $request->get(
                sprintf(
                    '%1$s?%2$s',
                    $this->baseUrl,
                    http_build_query(
                        array(
                            self::QUERY_SUPPORTED_COINS => 'true',
                        )
                    )
                ),
                $this->getRequestHeaders()
            );

            $coinValuesInUsd = trim($this->response->getContent());

            $coinValues = explode(';', $coinValuesInUsd);

            foreach ($coinValues as $coinValue) {
                $properties = explode(',', $coinValue);

                $thisCoin = $properties[0];

                if (self::isValidCoin($thisCoin)) {
                    $price = null;

                    foreach ($properties as $property) {
                        if (strpos($property, 'Price:') !== false) {
                            $price = floatval(str_replace('Price:', '', $property));
                        }
                    }

                    if ($price !== null) {
                        $this->coinValues[$thisCoin] = $price;
                    }
                }
            }
        }

        // if the coin was not found in the all-coins results, fetch the individual coin
        if (!isset($this->coinValues[$coin])) {
            $request        = $this->getRequest();
            $this->response = $request->get(
                sprintf(
                    '%1$s?%2$s',
                    $this->baseUrl,
                    http_build_query(
                        array(
                            self::QUERY_COIN => $coin,
                        )
                    )
                ),
                $this->getRequestHeaders()
            );

            $coinValueInUsd = trim($this->response->getContent());

            if (!preg_match('/^[0-9.]+$/', $coinValueInUsd)) {
                throw new \LogicException(
                    sprintf(
                        '%1$s: Invalid API response received, given response is not a valid currency value: %2$s',
                        __METHOD__,
                        $coinValueInUsd
                    )
                );
            }

            $this->coinValues[$coin] = floatval($coinValueInUsd);
        }

        $retValue = $this->coinValues[$coin];

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
     * has one optional argument, which is the currency to retrieve. This defaults to Euros.
     *
     * <code>
     * $client = new Client();
     * $exchangeRate = $client->getExchangeRate(Client::CURRENCY_JAPANESE_YEN);
     * </code>
     *
     * @param string $currency
     * @throws \InvalidArgumentException|\LogicException
     * @return float
     */
    public function getExchangeRate($currency = self::CURRENCY_EURO)
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

            // check whether we have the exchange rate cached
            if (!isset($this->exchangeRates[$currency])) {
                // retrieve the currency's exchange rate
                $request        = $this->getRequest();
                $this->response = $request->get(
                    sprintf(
                        '%1$s?%2$s',
                        $this->baseUrl,
                        http_build_query(
                            array(
                                self::QUERY_COIN => $currency,
                            )
                        )
                    ),
                    $this->getRequestHeaders()
                );

                $exchangeRate = trim($this->response->getContent());

                if (!preg_match('/^[0-9.]+$/', $exchangeRate)) {
                    throw new \LogicException(
                        sprintf(
                            '%1$s: Invalid API response received, given response is not a valid exchange rate: %2$s',
                            __METHOD__,
                            $exchangeRate
                        )
                    );
                }

                $this->exchangeRates[$currency] = floatval($exchangeRate);
            }

            $retValue = $this->exchangeRates[$currency];
        }

        return $retValue;
    }

    /**
     * This method retrieves the current balance of a specific type for the current or given user. This method has three
     * optional arguments:
     *
     * * The type of balance to fetch, which can be a coin type, Eobot type or currency type. Defaults to null, which fetches everything
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

        if ($forceFetch || !isset($this->balances[$userId])) {
            $this->balances[$userId] = array();

            // retrieve the balances for the given user
            $request        = $this->getRequest();
            $this->response = $request->get(
                sprintf(
                    '%1$s?%2$s',
                    $this->baseUrl,
                    http_build_query(
                        array(
                            self::QUERY_TOTAL => $userId,
                        )
                    )
                ),
                $this->getRequestHeaders()
            );

            $balances = trim($this->response->getContent());

            if (!strstr($balances, ';') || !strstr($balances, ':')) {
                throw new \LogicException(
                    sprintf(
                        '%1$s: Invalid API response received, given response does not contain balances: %2$s',
                        __METHOD__,
                        $balances
                    )
                );
            }

            $balances = explode(';', $balances);
            foreach ($balances as $balance) {
                $balance = explode(':', trim($balance));

                if (trim($balance[0]) == self::EO_CLOUD_SHA256_CONTRACT) {
                    $balance[0] = self::EO_CLOUD_SHA256;
                }

                if (trim($balance[0]) == self::EO_CLOUD_SHA256_2_CONTRACT) {
                    $balance[0] = self::EO_CLOUD_SHA256_2;
                }

                if (trim($balance[0]) == self::EO_CLOUD_SCRYPT_CONTRACT) {
                    $balance[0] = self::EO_CLOUD_SCRYPT;
                }

                if (trim($balance[0]) == self::EO_CLOUD_FOLDING_CONTRACT) {
                    $balance[0] = self::EO_CLOUD_FOLDING;
                }

                $this->balances[$userId][trim($balance[0])] = floatval(trim($balance[1]));
            }
        }

        if ($type === null) {
            $retValue = $this->balances[$userId];
        } elseif (self::isValidCoin($type) && isset($this->balances[$userId][$type])) {
            $retValue = $this->balances[$userId][$type];
        } elseif (self::isValidCurrency($type)) {
            $response       = $this->response;
            $exchangeRate   = $this->getExchangeRate($type);
            $this->response = $response;
            $retValue       = ($this->balances[$userId]['Total'] * $exchangeRate);
        } else {
            if ($type == self::EO_CLOUD_SHA256_CONTRACT) {
                $type = self::EO_CLOUD_SHA256;
            }

            if ($type == self::EO_CLOUD_SHA256_2_CONTRACT) {
                $type = self::EO_CLOUD_SHA256_2;
            }

            if ($type == self::EO_CLOUD_SCRYPT_CONTRACT) {
                $type = self::EO_CLOUD_SCRYPT;
            }

            if ($type == self::EO_CLOUD_FOLDING_CONTRACT) {
                $type = self::EO_CLOUD_FOLDING;
            }

            if (self::isValidEobotInternalType($type) && isset($this->balances[$userId][$type])) {
                $retValue = $this->balances[$userId][$type];
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
     * This method returns the coin type currently being mined. It expects one optional parameter, which is the Eobot
     * user identifier. If the user identifier was not passed into the constructor, it is required here.
     *
     * <code>
     * $client = new Client(1234);
     * $type = $client->getMiningMode();
     *
     * $client = new Client();
     * $type = $client->getMiningMode(1234);
     * </code>
     *
     * @param int $userId (Optional) Defaults to null
     * @throws \InvalidArgumentException|\LogicException
     * @return string
     */
    public function getMiningMode($userId = null)
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

        // retrieve the type currently being mined by the given user
        $request        = $this->getRequest();
        $this->response = $request->get(
            sprintf(
                '%1$s?%2$s',
                $this->baseUrl,
                http_build_query(
                    array(
                        self::QUERY_IDMINING => $userId,
                    )
                )
            ),
            $this->getRequestHeaders()
        );

        $retValue = trim($this->response->getContent());

        if ($retValue == self::EO_CLOUD_SHA256_CONTRACT) {
            $retValue = self::EO_CLOUD_SHA256;
        }

        if ($retValue == self::EO_CLOUD_SHA256_2_CONTRACT) {
            $retValue = self::EO_CLOUD_SHA256_2;
        }

        if ($retValue == self::EO_CLOUD_SCRYPT_CONTRACT) {
            $retValue = self::EO_CLOUD_SCRYPT;
        }

        if ($retValue == self::EO_CLOUD_FOLDING_CONTRACT) {
            $retValue = self::EO_CLOUD_FOLDING;
        }

        if (!self::isValidCoin($retValue) && !self::isValidEobotInternalType($retValue)) {
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
     * This method returns the current mining and cloud speeds. It expects one optional parameter, which is the Eobot
     * user identifier. If the user identifier was not passed into the constructor, it is required here. This method
     * returns an array with four values: the current mining speed for SHA-256 mining (in GHS), the current mining
     * speed for Scrypt mining (in KHS), the current Eobot cloud mining speed for SHA-256 mining (in GHS) and the
     * current Eobot cloud mining speed for Scrypt mining (in KHS).
     *
     * @param int $userId (Optional) Defaults to null
     * @throws \InvalidArgumentException|\LogicException
     * @return array
     */
    public function getSpeed($userId = null)
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
            'CloudScrypt'   => 0.0,
        );

        // retrieve the type currently being mined by the given user
        $request        = $this->getRequest();
        $this->response = $request->get(
            sprintf(
                '%1$s?%2$s',
                $this->baseUrl,
                http_build_query(
                    array(
                        self::QUERY_IDSPEED => $userId,
                    )
                )
            ),
            $this->getRequestHeaders()
        );

        $speeds = trim($this->response->getContent());

        if (!strstr($speeds, ';') || !strstr($speeds, ':')) {
            throw new \LogicException(
                sprintf(
                    '%1$s: Invalid API response received, given response does not contain mining speeds: %2$s',
                    __METHOD__,
                    $speeds
                )
            );
        }

        $speeds = explode(';', $speeds);
        foreach ($speeds as $speed) {
            if (trim($speed) == '') {
                continue;
            }

            $speed = explode(':', trim($speed));

            $retValue[trim($speed[0])] = floatval(trim($speed[1]));
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

        // retrieve the deposit address for this coin type
        $request        = $this->getRequest();
        $this->response = $request->get(
            sprintf(
                '%1$s?%2$s',
                $this->baseUrl,
                http_build_query(
                    array(
                        self::QUERY_ID      => $userId,
                        self::QUERY_DEPOSIT => $coinType,
                    )
                )
            ),
            $this->getRequestHeaders()
        );

        return trim($this->response->getContent());
    }

    /**
     * This method is used to retrieve the user's Eobot user ID. If an email address and password are given, the user
     * ID is retrieved via the Eobot API. Otherwise, the user ID set when instantiating the Client is returned. If no
     * user ID was given to the client, and no email address and password are given, an exception is thrown.
     *
     * @param string $email    (Optional) Defaults to null, required if the user ID was not set via the constructor
     * @param string $password (Optional) Defaults to null, required if the user ID was not set via the constructor, or if $email is set
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
        
        if ($type == self::EO_CLOUD_SHA256_CONTRACT) {
            $type = self::EO_CLOUD_SHA256;
        }

        if ($type == self::EO_CLOUD_SHA256_2_CONTRACT) {
            $type = self::EO_CLOUD_SHA256_2;
        }

        if ($type == self::EO_CLOUD_SCRYPT_CONTRACT) {
            $type = self::EO_CLOUD_SCRYPT;
        }

        if ($type == self::EO_CLOUD_FOLDING_CONTRACT) {
            $type = self::EO_CLOUD_FOLDING;
        }
        
        // get the current mining mode
        $currentMiningMode = $this->getMiningMode($userId);
        
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
        return ($this->getMiningMode($userId) == $type);
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
    public function setAutomaticWithdraw($coinType = self::COIN_BITCOIN, $amount = 1.0, $wallet, $email, $password, $userId = null)
    {
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
    public function withdrawFunds($coinType = self::COIN_BITCOIN, $amount = 1.0, $wallet, $email, $password, $userId = null)
    {
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
    public function convertCoinToCloud($coinType = self::COIN_BITCOIN, $amount = 1.0, $cloudType = self::EO_CLOUD_SHA256, $email, $password, $userId = null)
    {
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

        if ($coinType == self::EO_CLOUD_SHA256_CONTRACT) {
            $coinType = self::EO_CLOUD_SHA256;
        }

        if ($coinType == self::EO_CLOUD_SHA256_2_CONTRACT) {
            $coinType = self::EO_CLOUD_SHA256_2;
        }

        if ($coinType == self::EO_CLOUD_SCRYPT_CONTRACT) {
            $coinType = self::EO_CLOUD_SCRYPT;
        }

        if ($coinType == self::EO_CLOUD_FOLDING_CONTRACT) {
            $coinType = self::EO_CLOUD_FOLDING;
        }

        if ($cloudType == self::EO_CLOUD_SHA256_CONTRACT) {
            $cloudType = self::EO_CLOUD_SHA256;
        }

        if ($cloudType == self::EO_CLOUD_SHA256_2_CONTRACT) {
            $cloudType = self::EO_CLOUD_SHA256_2;
        }

        if ($cloudType == self::EO_CLOUD_SCRYPT_CONTRACT) {
            $cloudType = self::EO_CLOUD_SCRYPT;
        }

        if ($cloudType == self::EO_CLOUD_FOLDING_CONTRACT) {
            $cloudType = self::EO_CLOUD_FOLDING;
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
     * $isValid = Client::isValidEobotInternalType(Client::EO_CLOUD_SHA256);
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
        $retValue = new Browser();
        $retValue->getClient()->setTimeout(30);
        $retValue->getClient()->setVerifyPeer($this->validateSsl);

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
            'User-Agent' => 'RickDenHaan-Eobot/1.4.7 (+http://github.com/rickdenhaan/eobot-php)',
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
