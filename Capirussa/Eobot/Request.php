<?php
namespace Capirussa\Eobot;

/**
 * The Request object is used to submit a request to Eobot
 *
 * @package Capirussa\Eobot
 */
class Request
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
     * Indicates that a GET request should be submitted to the API
     */
    const METHOD_GET = 'GET';

    /**
     * Indicates that a POST request should be submitted to the API
     */
    const METHOD_POST = 'POST';

    /**
     * This property contains the base URL for all requests.
     *
     * @type string
     */
    protected $baseUrl = 'https://www.eobot.com/api.aspx';

    /**
     * This property contains the request method to use for this request, must be one of the methods defined in the
     * constants.
     *
     * @type string
     */
    protected $requestMethod = self::METHOD_GET;

    /**
     * This property contains an array of data that should be appended to the URL in its query string.
     *
     * @type mixed[]
     */
    protected $queryParameters = array();

    /**
     * This property contains an array of data that should be posted with the request, if it is a POST request.
     *
     * @type mixed[]
     */
    protected $postParameters = array();

    /**
     * This property is a boolean indicating whether the SSL certificate for the remote server should be validated.
     * Defaults to `true`, I recommend you keep it that way.
     *
     * @type bool
     */
    protected $validateSsl = true;

    /**
     * This property will contain the response to this request after it has been submitted.
     *
     * @type Response
     */
    protected $response;

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
        // if a request method was given, set it
        if ($requestMethod !== null) {
            $this->setRequestMethod($requestMethod);
        }

        $this->validateSsl = $validateSsl;
    }

    /**
     * This method is used to set the request method for this request. It accepts one argument, which must be one of
     * the request methods defined in this class (see `getRequestMethod()`) and returns nothing.
     *
     * <code>
     * $request->setRequestMethod(Request::METHOD_GET);
     * </code>
     *
     * @param string $requestMethod
     * @throws \InvalidArgumentException
     */
    public function setRequestMethod($requestMethod)
    {
        // validate the request method by checking whether it is defined as a constant in this class
        if (!self::isValidRequestMethod($requestMethod)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%1$s: Invalid request method \'%2$s\' given',
                    __METHOD__,
                    $requestMethod
                )
            );
        }

        $this->requestMethod = $requestMethod;
    }

    /**
     * This method is used to set data that should be appended to the URL's query string. The method returns nothing
     * and accepts two arguments:
     *
     * * The parameter that is being set, which must be valid for use as an array index (string or integer)
     * * The value that is being set, which can be anything
     *
     * <code>
     * $request->addQueryParameter('key1', 'value');
     * $request->addQueryParameter('key2', array('value 1', 'value 2'));
     * </code>
     *
     * @param string $parameter
     * @param mixed  $value
     */
    public function addQueryParameter($parameter, $value)
    {
        $this->queryParameters[$parameter] = $value;
    }

    /**
     * This method is used to set data that should be submitted in a POST request. The method returns nothing and
     * accepts two arguments:
     *
     * * The parameter that is being set, which must be valid for use as an array index (string or integer)
     * * The value that is being set, which can be anything
     *
     * <code>
     * $request->addPostParameter('key1', 'value');
     * $request->addPostParameter('key2', array('value 1', 'value 2'));
     * </code>
     *
     * @param string $parameter
     * @param mixed  $value
     */
    public function addPostParameter($parameter, $value)
    {
        $this->postParameters[$parameter] = $value;
    }

    /**
     * This method returns the currently configured request method. It will return one of the `METHOD_*` constants
     * defined in this class.
     *
     * <code>
     * $requestMethod = $request->getRequestMethod();
     * </code>
     *
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * This method submits this request to Eobot and returns a Response object containing the resulting response.
     *
     * <code>
     * $response = $request->send();
     * </code>
     *
     * @return Response
     */
    public function send()
    {
        return $this->doRequest();
    }

    /**
     * This method actually performs the CURL request to the remote server and retrieves the response. May be merged
     * into `send()` at some point in the future.
     *
     * <code>
     * $response = $this->doRequest();
     * </code>
     *
     * @throws \Exception
     * @return Response
     *
     * Unittests should never talk to the live Eobot environment, they use a mock request, so:
     * @codeCoverageIgnore
     */
    protected function doRequest()
    {
        // build the request URL
        $requestUrl = $this->buildRequestUrl();

        // set up the CURL request options
        $curlOptions = array(
            CURLOPT_SSL_VERIFYPEER => $this->validateSsl,
            CURLOPT_SSL_VERIFYHOST => $this->validateSsl ? 2 : 0,
            CURLOPT_FAILONERROR    => false,
            CURLOPT_HEADER         => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => 'Capirussa/1.0 (+http://github.com/rickdenhaan/eobot-php)'
        );

        // if this is a post request, tell CURL that
        if ($this->getRequestMethod() == self::METHOD_POST) {
            $curlOptions[CURLOPT_POST] = true;

            // check whether any post data was set
            if (count($this->getPostParameters()) > 0) {
                $curlOptions[CURLOPT_POSTFIELDS] = $this->getPostParameters();
            }
        }

        // initialize and configure the CURL request
        $curl = curl_init($requestUrl);
        curl_setopt_array(
            $curl,
            $curlOptions
        );

        // execute the CURL request
        $result = curl_exec($curl);

        // check whether the server threw a fit (would have nothing to do with the remote server, because we configured
        // the CURL request not to throw an error if the HTTP request fails)
        $error = curl_error($curl);
        if ($error != '') {
            throw new \Exception($error);
        }

        echo "\n\n\n\n";
        echo $requestUrl;
        echo "\n\n\n\n";
        echo $result;
        echo "\n\n\n\n";

        // close the CURL request
        curl_close($curl);

        // parse the response body and return the Response object
        $this->response = new Response($result);

        return $this->response;
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
        $retValue = $this->getBaseUrl();

        $queryParameters = $this->getQueryParameters();

        if (count($queryParameters) > 0) {
            $retValue .= (strpos($retValue, '?') > 0 ? '&' : '?') . http_build_query($queryParameters);
        }

        return $retValue;
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

    /**
     * This method returns an array of all query parameters that have been added to this request.
     *
     * <code>
     * $queryParameters = $request->getQueryParameters();
     * </code>
     *
     * @return mixed[]
     */
    public function getQueryParameters()
    {
        return $this->queryParameters;
    }

    /**
     * This method returns an array of all post parameters that have been added to this request.
     *
     * <code>
     * $postParameters = $request->getPostParameters();
     * </code>
     *
     * @return mixed[]
     */
    public function getPostParameters()
    {
        return $this->postParameters;
    }

    /**
     * This method returns the last response, in case this request is submitted multiple times or this instance is
     * reused for several requests. It returns either a Response object or null, if the request has not been submitted
     * yet.
     *
     * <code>
     * $response = $request->getLastResponse();
     * </code>
     *
     * @return Response
     */
    public function getLastResponse()
    {
        return $this->response;
    }

    /**
     * This static method validates whether a given value is one of the defined request methods.
     *
     * <code>
     * $isValid = Request::isValidRequestMethod(Request::METHOD_GET);
     * </code>
     *
     * @param string $requestMethod
     * @return bool
     */
    public static function isValidRequestMethod($requestMethod)
    {
        // validate the request method by checking whether it is defined as a constant in this class
        $reflectionClass  = new \ReflectionClass(get_class());
        $definedConstants = $reflectionClass->getConstants();

        $requestMethodIsValid = false;
        foreach ($definedConstants as $constantName => $constantValue) {
            if ($constantValue == $requestMethod && strlen($constantName) > 7 && strtoupper(substr($constantName, 0, 7)) == 'METHOD_') {
                $requestMethodIsValid = true;
                break;
            }
        }

        return $requestMethodIsValid;
    }
}