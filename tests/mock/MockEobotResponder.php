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
        Client::COIN_BITCOIN                   => 427.86,
        Client::COIN_BITSHARES                 => 0.00464736,
        Client::COIN_BLACKCOIN                 => 0.02895027,
        Client::COIN_BYTECOIN                  => 0.00004436,
        Client::COIN_CURECOIN                  => 0.02148741,
        // Client::COIN_DASH                      => 6.43222146, // DASH is used to simulate an invalid API response
        Client::COIN_DOGECOIN                  => 0.00022725,
        Client::COIN_ETHERIUM                  => 7.24943019,
        Client::COIN_FACTOM                    => 1.12261534,
        Client::COIN_GRIDCOIN                  => 0.00937663,
        Client::COIN_LITECOIN                  => 3.29834657,
        Client::COIN_LUMENS                    => 0.00184290,
        Client::COIN_MAIDSAFECOIN              => 0.07044485,
        Client::COIN_MONERO                    => 0.90132942,
        Client::COIN_NAMECOIN                  => 0.43390515,
        Client::COIN_NEM                       => 0.00137615,
        Client::COIN_PEERCOIN                  => 0.42862533,
        Client::COIN_REDDCOIN                  => 0.00003867,
        Client::COIN_RIPPLE                    => 0.00593452,
        Client::COIN_VOXELS                    => 0.18672231,
        // Client::CURRENCY_AUSTRALIAN_DOLLAR     => 1.30081315, // AUD is used to simulate an invalid API response
        Client::CURRENCY_BRITISH_POUND         => 0.70072178,
        Client::CURRENCY_CANADIAN_DOLLAR       => 1.27535,
        Client::CURRENCY_CHINESE_YUAN_RENMINBI => 6.46505,
        Client::CURRENCY_CZECH_KORUNA          => 23.725,
        Client::CURRENCY_DANISH_KRONE          => 6.53415,
        Client::CURRENCY_EURO                  => 0.87804023,
        Client::CURRENCY_HONG_KONG_DOLLAR      => 7.75441,
        Client::CURRENCY_INDIAN_RUPEE          => 66.325,
        Client::CURRENCY_INDONESIAN_RUPIAH     => 13077.5,
        Client::CURRENCY_ISRAELI_SHEKEL        => 3.76665,
        Client::CURRENCY_JAPANESE_YEN          => 108.5605,
        Client::CURRENCY_MALAYSIAN_RINGGIT     => 3.85545,
        Client::CURRENCY_MEXICAN_PESO          => 17.445,
        Client::CURRENCY_NORWEGIAN_KRONE       => 8.1684,
        Client::CURRENCY_POLISH_ZLOTY          => 3.7668,
        Client::CURRENCY_ROMANIAN_NEW_LEU      => 3.92665,
        Client::CURRENCY_RUSSIAN_RUBLE         => 65.2655,
        Client::CURRENCY_SERBIAN_DINAR         => 108.075,
        Client::CURRENCY_SWISS_FRANC           => 0.955435,
        Client::CURRENCY_UKRAINIAN_HRYVNIA     => 25.445,
        Client::CURRENCY_US_DOLLAR             => 1.0,
        Client::EO_CLOUD_FOLDING               => 0.05,
        Client::EO_CLOUD_SETI                  => 0.8,
        Client::EO_CLOUD_SHA256_2              => 0.15,
        Client::EO_CLOUD_SHA256_3              => 0.22,
        Client::RENTAL_FOLDING                 => 0.00014,
        Client::RENTAL_SCRYPT                  => 0.02143,
        Client::RENTAL_SHA256_3                => 0.00076,
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
            Client::COIN_CURECOIN              => 0.05292104,
            // Client::COIN_DASH                  => 0.01324434, // DASH is used to simulate an invalid API response
            Client::COIN_FACTOM                => 0.02639744,
            Client::COIN_DOGECOIN              => 23.78557417,
            Client::COIN_ETHERIUM              => 15.2361832,
            Client::COIN_GRIDCOIN              => 0.15432515,
            Client::COIN_LITECOIN              => 0.03013698,
            Client::COIN_LUMENS                => 0.06467642,
            Client::COIN_MAIDSAFECOIN          => 0.02748126,
            Client::COIN_MONERO                => 0.03641862,
            Client::COIN_NAMECOIN              => 0.00188207,
            Client::COIN_NEM                   => 0.00636984,
            Client::COIN_PEERCOIN              => 0.00502554,
            Client::COIN_REDDCOIN              => 0.02830923,
            Client::COIN_RIPPLE                => 0.03115914,
            Client::COIN_VOXELS                => 0.18739263,
            Client::EO_CLOUD_SHA256_2_CONTRACT => 15.42138465,
            Client::EO_CLOUD_SHA256_3_CONTRACT => 20.00019989,
            Client::EO_CLOUD_FOLDING           => 2.16726154,
            Client::EO_CLOUD_SETI              => 4.73192563,
        ),
        12345 => array(
            'Total'                            => 0.32751004,
            Client::COIN_BITCOIN               => 0.00020978,
            Client::COIN_BITSHARES             => 0.0141392,
            Client::COIN_BLACKCOIN             => 0.08188563,
            Client::COIN_BYTECOIN              => 0.01537264,
            Client::COIN_CURECOIN              => 0.05292104,
            // Client::COIN_DASH                  => 0.01324434, // DASH is used to simulate an invalid API response
            Client::COIN_DOGECOIN              => 23.78557417,
            Client::COIN_FACTOM                => 0.02639744,
            Client::COIN_ETHERIUM              => 15.2361832,
            Client::COIN_GRIDCOIN              => 0.15432515,
            Client::COIN_LITECOIN              => 0.03013698,
            Client::COIN_LUMENS                => 0.06467642,
            Client::COIN_MAIDSAFECOIN          => 0.02748126,
            Client::COIN_MONERO                => 0.72296727,
            Client::COIN_NAMECOIN              => 0.00188207,
            Client::COIN_NEM                   => 0.00636984,
            Client::COIN_PEERCOIN              => 0.00502554,
            Client::COIN_REDDCOIN              => 0.02830923,
            Client::COIN_RIPPLE                => 0.03115914,
            Client::COIN_VOXELS                => 0.18739263,
            Client::EO_CLOUD_SHA256_2_CONTRACT => 15.42136465,
            Client::EO_CLOUD_SHA256_3_CONTRACT => 20.00017989,
            Client::EO_CLOUD_FOLDING_CONTRACT  => 2.16724154,
            Client::EO_CLOUD_SETI_CONTRACT     => 4.73190563,
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
            Client::COIN_CURECOIN     => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_DASH         => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_DOGECOIN     => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_ETHERIUM     => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_FACTOM       => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_GRIDCOIN     => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_LITECOIN     => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_LUMENS       => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_MAIDSAFECOIN => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_MONERO       => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_NAMECOIN     => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_NEM          => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_PEERCOIN     => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_REDDCOIN     => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_RIPPLE       => '1234567890abcdefghijklmnopqrstuvwx',
            Client::COIN_VOXELS       => '1234567890abcdefghijklmnopqrstuvwx',
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
        7890 => Client::EO_CLOUD_SETI_CONTRACT,
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
        if (count($parameters) == 2 && isset($parameters['coin']) && isset($parameters['json'])) {
            if (!isset(self::$coinValues[$parameters['coin']])) {
                $responseText = 'invalid';
            } else {
                $responseText = sprintf('{"%s":"%s"}', $parameters['coin'],
                    number_format(self::$coinValues[$parameters['coin']], 8, '.', ''));
            }
        }

        // generate a mock response for a request for all supported coins
        if (count($parameters) == 2 && isset($parameters['supportedcoins']) && $parameters['supportedcoins'] == 'true' && isset($parameters['json'])) {
            $responseParts = array();

            foreach (self::$coinValues as $coin => $value) {
                if (Client::isValidCoin($coin)) {
                    $responseParts[] = sprintf(
                        '"%s":{"Image":"%s","BigImage":"%s","Price":%s}',
                        $coin,
                        'https://www.eobot.com/' . strtolower($coin) . '.png',
                        'https://www.eobot.com/' . strtolower($coin) . 'big.png',
                        number_format($value, 8, '.', '')
                    );
                }
            }

            $responseText = sprintf('{%s}', implode(',', $responseParts));
        }

        // generate a mock response for a request for all supported currencies
        if (count($parameters) == 2 && isset($parameters['supportedfiat']) && $parameters['supportedfiat'] == 'true' && isset($parameters['json'])) {
            $responseParts = array();

            foreach (self::$coinValues as $coin => $value) {
                if (Client::isValidCurrency($coin) && $coin != Client::CURRENCY_CANADIAN_DOLLAR) {
                    $responseParts[] = sprintf(
                        '"%s":{"Price":%s}',
                        $coin,
                        number_format($value, 8, '.', '')
                    );
                }
            }

            $responseText = sprintf('{%s}', implode(',', $responseParts));
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
        if (count($parameters) == 3 && isset($parameters['id']) && isset($parameters['deposit']) && isset($parameters['json'])) {
            if (isset(self::$depositWallets[$parameters['id']]) && isset(self::$depositWallets[$parameters['id']][$parameters['deposit']])) {
                $responseText = sprintf('{"%s":"%s"}', $parameters['deposit'],
                    self::$depositWallets[$parameters['id']][$parameters['deposit']]);
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
        if (count($parameters) == 2 && isset($parameters['idestimates']) && isset($parameters['json'])) {
            if (isset(self::$estimatedIncomes[$parameters['idestimates']])) {
                $responseParts = array();

                foreach (self::$estimatedIncomes[$parameters['idestimates']] as $type => $estimate) {
                    $estimate = number_format($estimate, 16, '.', '');
                    $estimate = preg_replace('/0*$/', '', $estimate);
                    $estimate = preg_replace('/\.$/', '.0', $estimate);

                    $responseParts[] = sprintf('"%s":%s', $type, $estimate);
                }

                $responseText = sprintf('{%s}', implode(',', $responseParts));
            }
        }

        // generate a mock response for what a user is currently mining
        if (count($parameters) == 2 && isset($parameters['idmining']) && isset($parameters['json'])) {
            if (isset(self::$miningModes[$parameters['idmining']])) {
                $responseText = sprintf('{"mining":"%s"}', self::$miningModes[$parameters['idmining']]);
            }
        }

        // generate a mock response for current mining speed requests
        if (count($parameters) == 2 && isset($parameters['idspeed']) && isset($parameters['json'])) {
            if (isset(self::$miningSpeeds[$parameters['idspeed']])) {
                $responseParts = array();

                foreach (self::$miningSpeeds[$parameters['idspeed']] as $type => $speed) {
                    $responseParts[] = sprintf('"%s":"%s"', $type, number_format($speed, 8, '.', ''));
                }

                $responseText = sprintf('{%s}', implode(',', $responseParts));
            }
        }

        // generate a mock response for balance requests
        if (count($parameters) == 2 && isset($parameters['total']) && isset($parameters['json'])) {
            if (!isset(self::$coinBalances[$parameters['total']])) {
                $responseText = 'invalid';
            } else {
                $responseParts = array();

                foreach (self::$coinBalances[$parameters['total']] as $coin => $balance) {
                    $responseParts[] = sprintf('"%s":"%s"', $coin, $balance);
                }

                $responseText = sprintf('{%s}', implode(',', $responseParts));
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