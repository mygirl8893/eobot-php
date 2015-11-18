<?php
require_once(dirname(__FILE__) . '/../init.php');

use RickDenHaan\Eobot\Client;

class MockEobotClient extends Client
{
    /**
     * Base URL for all calls
     *
     * @type string
     */
    protected $baseUrl = 'eobot://mock/';

    /**
     * Whether to switch user Id (only used by withdrawFunds test to indicate a successful withdraw)
     *
     * @var bool
     */
    protected $shouldSwitchUserId = false;

    /**
     * Used internally to be able to have withdrawFunds test with varying amounts
     *
     * @var bool
     */
    protected $userIdSwitch = false;

    /**
     * Returns a new mock Request object
     *
     * @return MockEobotBrowser
     */
    protected function getRequest()
    {
        return new MockEobotBrowser($this->baseUrl);
    }

    /**
     * Returns the balance for the given coin type
     *
     * @param string $type (Optional) Defaults to Bitcoin
     * @param int $userId (Optional) Defaults to the configured user id
     * @param bool $forceFetch (Optional) Defaults to false
     * @return float|float[]
     */
    public function getBalance($type = null, $userId = null, $forceFetch = false)
    {
        // adjust the user id so we can check for varying balances
        if ($this->shouldSwitchUserId && $userId !== null && $forceFetch) {
            if ($this->userIdSwitch) {
                $userId = '12345';
            }

            $this->userIdSwitch = !$this->userIdSwitch;
        }

        return parent::getBalance($type, $userId, $forceFetch);
    }

    /**
     * This method is used to withdraw funds from Eobot to your own (or someone else's) wallet. Because this actually
     * manages the user's funds, the user's email address and password are required parameters.
     *
     * @see Client::withdrawFunds()
     */
    public function withdrawFunds($coinType = self::COIN_BITCOIN, $amount = 1.0, $wallet, $email, $password, $userId = null)
    {
        if ($coinType == self::COIN_BITCOIN && $amount == 0.002) {
            $this->shouldSwitchUserId = true;
        }

        return parent::withdrawFunds($coinType, $amount, $wallet, $email, $password, $userId);
    }
    
    /**
     * This method is used to purchase Eobot mining power using mined (or deposited) coins. Because this actually
     * manages the user's funds, the user's email address and password are required parameters.
     *
     * @see Client::convertCoinToCloud
     */
    public function convertCoinToCloud(
        $coinType = self::COIN_BITCOIN,
        $amount = 1.0,
        $cloudType = self::EO_CLOUD_SHA256_3,
        $email,
        $password,
        $userId = null
    )
    {
        if (self::isValidRentalType($cloudType) && $amount == 0.00002) {
            $this->shouldSwitchUserId = true;
        } else if ($coinType == self::COIN_BITCOIN && $amount == 0.002) {
            $this->shouldSwitchUserId = true;
        }

        return parent::convertCoinToCloud($coinType, $amount, $cloudType, $email, $password, $userId);
    }
}