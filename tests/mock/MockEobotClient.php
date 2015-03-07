<?php
require_once(dirname(__FILE__) . '/../../../init.php');

use Capirussa\Eobot;

class MockEobotClient extends Eobot\Client
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
     * Withdraws funds to the given wallet
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
    public function withdrawFunds($coinType = self::COIN_BITCOIN, $amount = 1.0, $wallet, $email, $password, $userId = null) {
        if ($coinType == self::COIN_BITCOIN && $amount == 0.002) {
            $this->shouldSwitchUserId = true;
        }

        return parent::withdrawFunds($coinType, $amount, $wallet, $email, $password, $userId);
    }
}