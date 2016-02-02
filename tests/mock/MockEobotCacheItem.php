<?php
require_once(dirname(__FILE__) . '/../init.php');

/**
 * Cache wrapper
 *
 * @codeCoverageIgnore
 */
class MockEobotCacheItem implements \Psr\Cache\CacheItemInterface
{
    private $key;
    private $value;

    public $expires;
    public $new;

    public function __construct($key)
    {
        $this->key     = $key;
        $this->value   = null;
        $this->expires = new DateTime();
        $this->new     = true;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isHit()
    {
        return !$this->new;
    }

    /**
     * @param mixed $value
     * @return static
     */
    public function set($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param \DateTime $expiration
     * @return static
     */
    public function expiresAt($expiration)
    {
        if ($expiration === null) {
            $this->expires = new DateTime('2999-01-01 00:00:00');
        } else {
            $this->expires = $expiration;
        }

        return $this;
    }

    /**
     * @param int|\DateInterval $time
     * @return static
     */
    public function expiresAfter($time)
    {
        if ($time === null) {
            $this->expires = new DateTime('2999-01-01 00:00:00');
        } else if ($time instanceof \DateInterval) {
            $this->expires = new DateTime();
            $this->expires->add($time);
        } else if ($time > 0) {
            $this->expires = new DateTime();
            $this->expires->modify(sprintf('+%d seconds', $time));
        } else {
            $this->expires = new DateTime();
        }

        return $this;
    }
}
