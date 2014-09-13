<?php
namespace Capirussa\Eobot;

use Capirussa\Http;

/**
 * The Request object is used to submit a request to Eobot
 *
 * @package Capirussa\Eobot
 */
class Request extends Http\Request
{
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
     * This property contains the base URL for all requests.
     *
     * @type string
     */
    protected $baseUrl = 'https://www.eobot.com/api.aspx';

    /**
     * The constructor can be used to quickly instantiate a Request with a request method. In some circumstances it
     * may be necessary to disable SSL verification on the response. Usually when your server is not properly
     * configured, but this can also happen if Eobot ever forgets to renew their SSL certificates and is working with
     * old ones. If you ever need to (**which is not recommended!**), you can use the second argument of the
     * constructor to disable SSL verification, or you can configure this via the Client. The constructor accepts
     * two optional arguments:
     *
     * * The request method, which must be one of the methods defined in this class (see `getRequestMethod()`), defaults to `Request::METHOD_GET`
     * * A boolean flag which indicates whether or not to validate the Eobot SSL certificate, defaults to `true`
     *
     * <code>
     * $request = new Request();
     * $request = new Request(Request::METHOD_POST, false);
     * </code>
     *
     * @param string $requestMethod (Optional) Defaults to self::METHOD_GET
     * @param bool   $validateSsl   (Optional) Defaults to true, only set to false for debugging!
     */
    public function __construct($requestMethod = self::METHOD_GET, $validateSsl = true)
    {
        parent::__construct(null, $requestMethod, $validateSsl);
    }

    /**
     * This method is used internally to build the full request URL by combining the base URL with the suffix for the
     * current sign, and applying any URL or query parameters.
     *
     * <code>
     * $this->setSign(self::SIGN_LOGIN);
     * $fullUrl = $this->buildRequestUrl();
     * </code>
     *
     * @return string
     */
    protected function buildRequestUrl()
    {
        $this->setRequestUrl($this->getBaseUrl());

        return parent::buildRequestUrl();
    }

    /**
     * This method is used internally to retrieve the base URL.
     *
     * <code>
     * $baseUrl = $this->getBaseUrl();
     * </code>
     *
     * @return string
     */
    protected function getBaseUrl()
    {
        return $this->baseUrl;
    }
}