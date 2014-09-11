<?php
namespace Capirussa\Eobot;

/**
 * Eobot Client is the class that communicates with the Eobot API
 *
 * @package Capirussa\Eobot
 */
class Client
{
    /**
     * The coin abbreviation for Bitcoin
     */
    const COIN_BITCOIN = 'BTC';

    /**
     * The coin abbreviation for BitSharesX
     */
    const COIN_BITSHARESX = 'BTSX';

    /**
     * The coin abbreviation for Litecoin
     */
    const COIN_LITECOIN = 'LTC';

    /**
     * The coin abbreviation for BlackCoin
     */
    const COIN_BLACKCOIN = 'BC';

    /**
     * The coin abbreviation for Namecoin
     */
    const COIN_NAMECOIN = 'NMC';

    /**
     * The coin abbreviation for Dogecoin
     */
    const COIN_DOGECOIN = 'DOGE';

    /**
     * The coin abbreviation for NautilusCoin
     */
    const COIN_NAUTILUSCOIN = 'NAUT';

    /**
     * The coin abbreviation for Darkcoin
     */
    const COIN_DARKCOIN = 'DRK';

    /**
     * The coin abbreviation for Vertcoin
     */
    const COIN_VERTCOIN = 'VTC';

    /**
     * The coin abbreviation for CureCoin
     */
    const COIN_CURECOIN = 'CURE';

    /**
     * The coin abbreviation for Peercoin
     */
    const COIN_PEERCOIN = 'PPC';

    /**
     * The coin abbreviation for NXT
     */
    const COIN_NXT = 'NXT';

    /**
     * The Eobot abbreviation for their Cloud SHA-256 miners
     */
    const EO_CLOUD_SHA256 = 'GHS';

    /**
     * The Eobot abbreviation for their Cloud Scrypt miners
     */
    const EO_CLOUD_SCRYPT = 'SCRYPT';

    /**
     * The currency abbreviation for EUR
     */
    const CURR_EUR = 'EUR';

    /**
     * The currency abbreviation for USD
     */
    const CURR_USD = 'USD';

    /**
     * The currency abbreviation for JPY
     */
    const CURR_JPY = 'JPY';

    /**
     * The currency abbreviation for GBP
     */
    const CURR_GBP = 'GBP';

    /**
     * The currency abbreviation for RUB
     */
    const CURR_RUB = 'RUB';

    /**
     * The currency abbreviation for CNY
     */
    const CURR_CNY = 'CNY';

    /**
     * The currency abbreviation for CAD
     */
    const CURR_CAD = 'CAD';

    /**
     * The currency abbreviation for AUD
     */
    const CURR_AUD = 'AUD';

    /**
     * The currency abbreviation for MXN
     */
    const CURR_MXN = 'MXN';

    /**
     * The currency abbreviation for IDR
     */
    const CURR_IDR = 'IDR';

    /**
     * The currency abbreviation for NOK
     */
    const CURR_NOK = 'NOK';

    /**
     * The currency abbreviation for CZK
     */
    const CURR_CZK = 'CZK';

    /**
     * The currency abbreviation for PLN
     */
    const CURR_PLN = 'PLN';

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
     * Contains the Response object for the last submitted request.
     *
     * @type \Capirussa\Http\Response
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
     * has two optional arguments:
     *
     * * The cryptocurrency to retrieve the value for, which defaults to Bitcoin
     * * The real-world currency to retrieve the value in, which defaults to US Dollars
     *
     * <code>
     * $client = new Client();
     * $liteCoinValueInEuros = $client->getCoinValue(Client::COIN_LITECOIN, Client::CURR_EUR);
     * </code>
     *
     * @param string $coin     (Optional) Defaults to Bitcoin
     * @param string $currency (Optional) Defaults to US Dollars
     * @throws \InvalidArgumentException|\LogicException
     * @return float
     */
    public function getCoinValue($coin = self::COIN_BITCOIN, $currency = self::CURR_USD)
    {
        if (!self::isValidCoin($coin)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid coin type given',
                    __METHOD__
                )
            );
        }

        if (!self::isValidCurrency($currency)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid currency type given',
                    __METHOD__
                )
            );
        }

        // check whether we have the coin value in USD cached
        if (!isset($this->coinValues[$coin])) {
            // retrieve the coin's value in USD
            $request = $this->getRequest();
            $request->addQueryParameter(Request::QUERY_COIN, $coin);
            $this->response = $request->send();

            $coinValueInUsd = trim($this->response->getRawBody());

            if (!preg_match('/^[0-9.]+$/', $coinValueInUsd)) {
                throw new \LogicException(
                    sprintf(
                        '%1$s: Invalid API response received, given response is not a valid currency value',
                        __METHOD__
                    )
                );
            }

            $this->coinValues[$coin] = floatval($coinValueInUsd);
        }

        $retValue = $this->coinValues[$coin];

        // check whether we were asked to retrieve a different currency
        if ($currency != self::CURR_USD) {
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
     * $exchangeRate = $client->getExchangeRate(Client::CURR_JPY);
     * </code>
     *
     * @param string $currency
     * @throws \InvalidArgumentException|\LogicException
     * @return float
     */
    public function getExchangeRate($currency = self::CURR_EUR)
    {
        $retValue = 1.0;

        if ($currency != self::CURR_USD) {
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
                $request = $this->getRequest();
                $request->addQueryParameter(Request::QUERY_COIN, $currency);
                $this->response = $request->send();

                $exchangeRate = trim($this->response->getRawBody());

                if (!preg_match('/^[0-9.]+$/', $exchangeRate)) {
                    throw new \LogicException(
                        sprintf(
                            '%1$s: Invalid API response received, given response is not a valid exchange rate',
                            __METHOD__
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
     * This method retrieves the current balance of a specific type for the current or given user. This method has two
     * optional arguments:
     *
     * * The type of balance to fetch, which can be a coin type, Eobot type or currency type. Defaults to null, which fetches everything
     * * The Eobot user id to fetch the balances for, which is optional if it was passed to the constructor
     *
     * <code>
     * $client = new Client(1234);
     * $btcBalance = $client->getBalance(Client::COIN_BITCOIN);
     *
     * $client = new Client();
     * $ltcBalance = $client->getBalance(Client::COIN_LITECOIN, 1234);
     * </code>
     *
     * @param string $type   (Optional) Defaults to null, which returns everything
     * @param int    $userId (Optional) Defaults to null
     * @throws \InvalidArgumentException|\LogicException
     * @return float|float[]
     */
    public function getBalance($type = null, $userId = null)
    {
        if ($type !== null) {
            if (!self::isValidCoin($type) && !self::isValidCurrency($type)) {
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

        if (!isset($this->balances[$userId])) {
            $this->balances[$userId] = array();

            // retrieve the balances for the given user
            $request = $this->getRequest();
            $request->addQueryParameter(Request::QUERY_TOTAL, $userId);
            $this->response = $request->send();

            $balances = trim($this->response->getRawBody());

            if (!strstr($balances, ';') || !strstr($balances, ':')) {
                throw new \LogicException(
                    sprintf(
                        '%1$s: Invalid API response received, given response does not contain balances',
                        __METHOD__
                    )
                );
            }

            $balances = explode(';', $balances);
            foreach ($balances as $balance) {
                $balance = explode(':', trim($balance));

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
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid balance type given, it is not in the balance sheet returned by the API',
                    __METHOD__
                )
            );
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
        $request = $this->getRequest();
        $request->addQueryParameter(Request::QUERY_IDMINING, $userId);
        $this->response = $request->send();

        $retValue = trim($this->response->getRawBody());

        if (!self::isValidCoin($retValue)) {
            throw new \LogicException(
                sprintf(
                    '%1$s: Invalid API result received, given response is not a valid coin type',
                    __METHOD__
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
        $request = $this->getRequest();
        $request->addQueryParameter(Request::QUERY_IDSPEED, $userId);
        $this->response = $request->send();

        $speeds = trim($this->response->getRawBody());

        if (!strstr($speeds, ';') || !strstr($speeds, ':')) {
            throw new \LogicException(
                sprintf(
                    '%1$s: Invalid API response received, given response does not contain mining speeds',
                    __METHOD__
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
     * This method is used to set the mining mode for the given user. Because this is a change in settings, the user's
     * email address and password are required parameters.
     *
     * @param string $type   (Optional) Defaults to Bitcoin
     * @param string $email
     * @param string $password
     * @param string $userId (Optional) Defaults to null, only required if not set via the contructor
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function setMiningMode($type = self::COIN_BITCOIN, $email, $password, $userId = null)
    {
        if (!self::isValidCoin($type)) {
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

        // switch the mining mode
        $request = $this->getRequest();
        $request->addQueryParameter(Request::QUERY_ID, $userId);
        $request->addQueryParameter(Request::QUERY_EMAIL, $email);
        $request->addQueryParameter(Request::QUERY_PASSWORD, $password);
        $request->addQueryParameter(Request::QUERY_MINING, $type);
        $this->response = $request->send();

        $result = trim($this->response->getRawBody());

        return ($result == '');
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
            if ((substr($constantName, 0, 5) == 'COIN_' || substr($constantName, 0, 3) == 'EO_') && $constantValue == $coin) {
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
     * $isValid = Client::isValidCurrency(Client::CURR_EUR);
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
            if (substr($constantName, 0, 5) == 'CURR_' && $constantValue == $currency) {
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
     * This method is used internally to retrieve a Request object. It accepts one parameter, the request method to
     * use. When building the request, it also passes on whether or not to validate the remote SSL certificate.
     *
     * <code>
     * $request = $this->getRequest(Request::METHOD_GET);
     * </code>
     *
     * @param string $requestMethod (Optional) Defaults to Request::METHOD_GET
     * @return Request
     *
     * Unittests overwrite this method to retrieve a mock request, so
     * @codeCoverageIgnore
     */
    protected function getRequest($requestMethod = Request::METHOD_GET)
    {
        return new Request($requestMethod, $this->validateSsl);
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
     * @return \Capirussa\Http\Response|null
     */
    public function getLastResponse()
    {
        return $this->response;
    }
}
