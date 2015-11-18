<?php
use RickDenHaan\Eobot\Client;

/**
 * Generates a mock response for the supplied parameters
 *
 */
class MockEobotResponder
{
    /**
     * The date/time at which this data was retrieved, will be used in the response headers
     *
     * @type string
     */
    private static $valueDate = '2015-09-10 21:19:33';

    /**
     * All coin values
     *
     * @type float[]
     */
    private static $coinValues = array(
        Client::COIN_BITCOIN                   => 239.51,
        Client::COIN_BITSHARES                 => 0.00444021,
        Client::COIN_BLACKCOIN                 => 0.02531201,
        Client::COIN_BYTECOIN              => 0.00005984,
        Client::COIN_COUNTERPARTY          => 0.92357389,
        Client::COIN_CURECOIN              => 0.01073379,
        // Client::COIN_DASH                      => 2.40377661, // DASH is used to simulate an invalid API response
        Client::COIN_DOGECOIN              => 0.00012923,
        Client::COIN_ETHERIUM              => 1.15773517,
        Client::COIN_GRIDCOIN              => 0.0013353,
        Client::COIN_LITECOIN              => 2.9510757,
        Client::COIN_LUMENS                => 0.00220185,
        Client::COIN_MAIDSAFECOIN          => 0.02252202,
        Client::COIN_MONERO                => 0.52178501,
        Client::COIN_NAMECOIN              => 0.41365257,
        Client::COIN_NXT                   => 0.00852383,
        Client::COIN_PEERCOIN              => 0.39906844,
        Client::COIN_REDDCOIN              => 0.00001207,
        Client::COIN_RIPPLE                => 0.00766181,
        Client::COIN_STORJCOIN_X           => 0.01743132,
        // Client::CURRENCY_AUSTRALIAN_DOLLAR     => 1.29, // AUD is used to simulate an invalid API response
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
        Client::CURRENCY_POLISH_ZLOTY      => 3.82,
        Client::CURRENCY_ROMANIAN_NEW_LEU  => 4.1,
        Client::CURRENCY_RUSSIAN_RUBLE     => 59.36,
        Client::CURRENCY_SERBIAN_DINAR     => 110.89,
        Client::CURRENCY_SWISS_FRANC       => 0.99,
        Client::CURRENCY_UKRAINIAN_HRYVNIA => 23.63,
        Client::CURRENCY_US_DOLLAR         => 1.0,
        Client::EO_CLOUD_FOLDING           => 0.05,
        Client::EO_CLOUD_SHA256_2          => 0.55,
        Client::EO_CLOUD_SHA256_3          => 0.005,
        Client::RENTAL_FOLDING             => 0.00013699,
        Client::RENTAL_SHA256              => 0.00101718,
    );

    /**
     * Balances for the various coins by user ID
     *
     * @type float[][]
     */
    private static $coinBalances = array(
        1234  => array(
            'Total'                            => 0.32751004,
            Client::COIN_BITCOIN               => 0.00040978,
            Client::COIN_BITSHARES             => 0.0141392,
            Client::COIN_BLACKCOIN             => 0.08188563,
            Client::COIN_BYTECOIN              => 0.01537264,
            Client::COIN_COUNTERPARTY          => 0.00636984,
            Client::COIN_CURECOIN              => 0.05292104,
            // Client::COIN_DASH                  => 0.01324434, // DASH is used to simulate an invalid API response
            Client::COIN_DOGECOIN              => 23.78557417,
            Client::COIN_ETHERIUM              => 15.2361832,
            Client::COIN_GRIDCOIN              => 0.15432515,
            Client::COIN_LITECOIN              => 0.03013698,
            Client::COIN_LUMENS                => 0.06467642,
            Client::COIN_MAIDSAFECOIN          => 0.02748126,
            Client::COIN_MONERO                => 0.03641862,
            Client::COIN_NAMECOIN              => 0.00188207,
            Client::COIN_NXT                   => 0.10494402,
            Client::COIN_PEERCOIN              => 0.00502554,
            Client::COIN_REDDCOIN              => 0.02830923,
            Client::COIN_RIPPLE                => 0.03115914,
            Client::COIN_STORJCOIN_X           => 0.02639744,
            Client::EO_CLOUD_SHA256_2_CONTRACT => 15.42138465,
            Client::EO_CLOUD_SHA256_3_CONTRACT => 20.00019989,
            Client::EO_CLOUD_FOLDING           => 2.16726154,
        ),
        12345 => array(
            'Total'                            => 0.32751004,
            Client::COIN_BITCOIN               => 0.00020978,
            Client::COIN_BITSHARES             => 0.0141392,
            Client::COIN_BLACKCOIN             => 0.08188563,
            Client::COIN_BYTECOIN              => 0.01537264,
            Client::COIN_COUNTERPARTY          => 0.00636984,
            Client::COIN_CURECOIN              => 0.05292104,
            // Client::COIN_DASH                  => 0.01324434, // DASH is used to simulate an invalid API response
            Client::COIN_DOGECOIN              => 23.78557417,
            Client::COIN_ETHERIUM              => 15.2361832,
            Client::COIN_GRIDCOIN              => 0.15432515,
            Client::COIN_LITECOIN              => 0.03013698,
            Client::COIN_LUMENS                => 0.06467642,
            Client::COIN_MAIDSAFECOIN          => 0.02748126,
            Client::COIN_MONERO                => 0.72296727,
            Client::COIN_NAMECOIN              => 0.00188207,
            Client::COIN_NXT                   => 0.10494402,
            Client::COIN_PEERCOIN              => 0.00502554,
            Client::COIN_REDDCOIN              => 0.02830923,
            Client::COIN_RIPPLE                => 0.03115914,
            Client::COIN_STORJCOIN_X           => 0.02639744,
            Client::EO_CLOUD_SHA256_2_CONTRACT => 15.42136465,
            Client::EO_CLOUD_SHA256_3_CONTRACT => 20.00017989,
            Client::EO_CLOUD_FOLDING_CONTRACT  => 2.16724154,
        ),
    );

    /**
     * Lists estimated mining incomes by user ID
     *
     * @type float[][]
     */
    private static $estimatedIncomes = array(
        1234 => array(
            'MiningSHA-256' => 0.0,
            'MiningScrypt'  => 0.0,
            'CloudSHA-256'  => 3.36369763,
            'Cloud2SHA-256' => 8.40883507,
            'CloudScrypt'   => 1.0111580929310733,
        ),
    );

    /**
     * Contains valid username and password settings per user ID
     *
     * @type array[]
     */
    private static $credentials = array(
        1234 => array(
            'email'    => 'test@example.com',
            'password' => 'correctPassword',
        ),
    );

    /**
     * Contains mock wallet addresses by user ID
     *
     * @type string[][]
     */
    private static $depositWallets = array(
        1234 => array(
            Client::COIN_BITCOIN      => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_BITSHARES    => 'n/a', // BTS does not have a deposit address
            Client::COIN_BLACKCOIN    => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_BYTECOIN     => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_COUNTERPARTY => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_CURECOIN     => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_DASH         => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_DOGECOIN     => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_ETHERIUM     => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_GRIDCOIN     => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_LITECOIN     => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_LUMENS       => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_MAIDSAFECOIN => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_MONERO       => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_NAMECOIN     => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_NXT          => 'NXT-1234-5678-90AB-CDEF',
            Client::COIN_PEERCOIN     => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_REDDCOIN     => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_RIPPLE       => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_STORJCOIN_X  => '1234567890abcdefghijklmnopqrstuvwx',
        ),
    );

    /**
     * What's currently being mined by user ID
     *
     * @type string[]
     */
    private static $miningModes = array(
        1234 => Client::COIN_BITCOIN,
        2345 => 'invalid', // simulates an invalid API response
        3456 => Client::EO_CLOUD_SHA256_3_CONTRACT,
        8901 => Client::EO_CLOUD_FOLDING_CONTRACT,
        9012 => Client::EO_CLOUD_SHA256_2_CONTRACT,
    );

    /**
     * Current mining speeds by user ID
     *
     * @type float[][]
     */
    private static $miningSpeeds = array(
        1234 => array(
            'MiningSHA-256' => 0.0,
            'MiningScrypt'  => 0.0,
            'CloudSHA-256'  => 20.0001998933406,
            'Cloud2SHA-256' => 10.5013062743518,
            'CloudScrypt'   => 0.0111580929310733,
        ),
    );

    /**
     * Returns a simulated HTTP response string for the given parameters
     *
     * @param mixed[] $parameters
     * @return string
     */
    public static function getResponse($parameters)
    {
        $responseText = '';

        // generate a mock response for coin value requests
        if (count($parameters) == 1 && isset($parameters['coin'])) {
            if (!isset(self::$coinValues[$parameters['coin']])) {
                $responseText = 'invalid';
            } else {
                $responseText = number_format(self::$coinValues[$parameters['coin']], 8, '.', '');
            }
        }

        // generate a mock response for a request for all supported coins
        if (count($parameters) == 1 && isset($parameters['supportedcoins']) && $parameters['supportedcoins'] == 'true') {
            $responseParts = array();

            foreach (self::$coinValues as $coin => $value) {
                if (Client::isValidCoin($coin)) {
                    $part = array(
                        $coin,
                        'Image:https://www.eobot.com/' . strtolower($coin) . '.png',
                        'Price:' . number_format($value, 8, '.', ''),
                    );

                    $responseParts[] = implode(',', $part);
                }
            }

            $responseText = implode(';', $responseParts);
        }

        // generate a mock response for user ID lookup requests
        if (count($parameters) == 2 && isset($parameters['email']) && isset($parameters['password'])) {
            foreach (self::$credentials as $userId => $credentials) {
                if ($credentials['email'] == $parameters['email'] && $credentials['password'] == $parameters['password']) {
                    $responseText = $userId;
                    break;
                }
            }
        }

        // generate a mock response for coin conversion requests
        if (count($parameters) == 6 && isset($parameters['id']) && isset($parameters['convertfrom']) && isset($parameters['amount']) && isset($parameters['convertto']) && isset($parameters['email']) && isset($parameters['password'])) {
            // the API always returns an empty response to conversion requests, no matter what the parameters are
        }

        // generate a mock response for deposit wallet address requests
        if (count($parameters) == 2 && isset($parameters['id']) && isset($parameters['deposit'])) {
            if (isset(self::$depositWallets[$parameters['id']]) && isset(self::$depositWallets[$parameters['id']][$parameters['deposit']])) {
                $responseText = self::$depositWallets[$parameters['id']][$parameters['deposit']];
            }
        }

        // generate a mock response for withdrawal requests
        if (count($parameters) == 6 && isset($parameters['id']) && isset($parameters['manualwithdraw']) && isset($parameters['amount']) && isset($parameters['wallet']) && isset($parameters['email']) && isset($parameters['password'])) {
            // the API always returns an empty response to withdrawal requests, no matter what the parameters are
        }

        // generate a mock response for setting automatic withdrawal wallet address requests
        if (count($parameters) == 6 && isset($parameters['id']) && isset($parameters['withdraw']) && isset($parameters['amount']) && isset($parameters['wallet']) && isset($parameters['email']) && isset($parameters['password'])) {
            // the API always returns an empty response to automatic withdrawal requests, no matter what the parameters are
        }

        // generate a mock response for requests to switch mining modes
        if (count($parameters) == 4 && isset($parameters['id']) && isset($parameters['mining']) && isset($parameters['email']) && isset($parameters['password'])) {
            // the API always returns an empty response to mining switch requests, no matter what the parameters are
        }

        // generate a mock response for estimated income requests
        if (count($parameters) == 1 && isset($parameters['idestimates'])) {
            if (isset(self::$estimatedIncomes[$parameters['idestimates']])) {
                $responseParts = array();

                foreach (self::$estimatedIncomes[$parameters['idestimates']] as $type => $estimate) {
                    $estimate = number_format($estimate, 16, '.', '');
                    $estimate = preg_replace('/0*$/', '', $estimate);
                    $estimate = str_pad($estimate, 10, '0', STR_PAD_RIGHT);

                    $responseParts[] = $type . ':' . $estimate;
                }

                $responseText = implode(';', $responseParts) . ';';
            }
        }

        // generate a mock response for what a user is currently mining
        if (count($parameters) == 1 && isset($parameters['idmining'])) {
            if (isset(self::$miningModes[$parameters['idmining']])) {
                $responseText = self::$miningModes[$parameters['idmining']];
            }
        }

        // generate a mock response for current mining speed requests
        if (count($parameters) == 1 && isset($parameters['idspeed'])) {
            if (isset(self::$miningSpeeds[$parameters['idspeed']])) {
                $responseParts = array();

                foreach (self::$miningSpeeds[$parameters['idspeed']] as $type => $speed) {
                    $speed = number_format($speed, 16, '.', '');
                    $speed = preg_replace('/0*$/', '', $speed);
                    $speed = str_pad($speed, 10, '0', STR_PAD_RIGHT);

                    $responseParts[] = $type . ':' . $speed;
                }

                $responseText = implode(';', $responseParts) . ';';
            }
        }

        // generate a mock response for balance requests
        if (count($parameters) == 1 && isset($parameters['total'])) {
            if (!isset(self::$coinBalances[$parameters['total']])) {
                $responseText = 'invalid';
            } else {
                $responseParts = array();

                foreach (self::$coinBalances[$parameters['total']] as $coin => $balance) {
                    $responseParts[] = $coin . ':' . $balance;
                }

                $responseText = implode(';', $responseParts);
            }
        }

        $contentLength = mb_strlen($responseText);

        $responseHeaders = self::getResponseHeaders($contentLength);

        return implode("\r\n", $responseHeaders) . "\r\n\r\n" . $responseText;
    }

    /**
     * Returns the HTTP response headers for the mock response
     *
     * @param int $contentLength
     * @return string[]
     */
    public static function getResponseHeaders($contentLength)
    {
        $dateTime = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            self::$valueDate,
            new DateTimeZone('GMT')
        );

        return array(
            'HTTP/1.1 200 OK',
            'Cache-Control: no-cache, no-store, must-revalidate',
            'Content-Length: ' . $contentLength,
            'Content-Type: application/json; charset=utf-8',
            'Date: ' . $dateTime->format('D, d M y H:i:s T'),
            'Expires: -1',
            'Pragma: no-cache,no-cache',
            'Server: Microsoft-IIS/8.5',
            'Set-Cookie: ASP.NET_SessionId=x02qrpvfobdxnj45xgn05t55; path=/; secure; HttpOnly',
            'Strict-Transport-Security: max-age=31536000',
            'x-frame-options :DENY',
            'X-AspNet-Version: 2.0.50727',
            'X-Powered-By: ASP.NET',
            'X-XSS-Protection: 1; mode-block',
        );
    }
}