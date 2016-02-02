<?php
require_once(dirname(__FILE__) . '/../init.php');

/**
 * Caches in memory
 *
 * @codeCoverageIgnore
 */
class MockEobotCachePool implements \Psr\Cache\CacheItemPoolInterface
{
    /**
     * @type MockEobotCacheItem[]
     */
    private $cache = array();

    /**
     * @param string $key
     * @throws \Psr\Cache\InvalidArgumentException
     * @return MockEobotCacheItem
     */
    public function getItem($key)
    {
        $retValue = null;

        if (isset($this->cache[$key])) {
            $retValue = $this->cache[$key];

            if ($retValue->expires === null || $retValue->expires <= new DateTime()) {
                $retValue = null;
            }
        }

        if ($retValue === null) {
            $retValue = new MockEobotCacheItem($key);
        }

        return $retValue;
    }

    /**
     * @param array $keys
     * @throws \Psr\Cache\InvalidArgumentException
     * @return array|\Traversable
     */
    public function getItems(array $keys = array())
    {
        $retValue = array();

        foreach ($keys as $key) {
            if (!is_string($key)) {
                throw new \InvalidArgumentException('Invalid key: ' . $key);
            }

            $retValue[$key] = $this->getItem($key);
        }

        return $retValue;
    }

    /**
     * @param string $key
     * @throws \Psr\Cache\InvalidArgumentException
     * @return bool
     */
    public function hasItem($key)
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException('Invalid key: ' . $key);
        }

        return isset($this->cache[$key]);
    }

    /**
     * @return bool
     */
    public function clear()
    {
        $this->cache = array();

        return true;
    }

    /**
     * @param string $key
     * @throws \Psr\Cache\InvalidArgumentException
     * @return bool
     */
    public function deleteItem($key)
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException('Invalid key: ' . $key);
        }

        if (isset($this->cache[$key])) {
            unset($this->cache[$key]);

            return true;
        }

        return false;
    }

    /**
     * @param array $keys
     * @throws \Psr\Cache\InvalidArgumentException
     * @return bool
     */
    public function deleteItems(array $keys)
    {
        $retValue = true;

        foreach ($keys as $key) {
            if (!$this->deleteItem($key)) {
                $retValue = false;
            }
        }

        return $retValue;
    }

    /**
     * @param MockEobotCacheItem|\Psr\Cache\CacheItemInterface $item
     * @return bool
     */
    public function save(\Psr\Cache\CacheItemInterface $item)
    {
        $this->cache[$item->getKey()] = $item;
        $item->new                    = false;

        return true;
    }

    /**
     * @param \Psr\Cache\CacheItemInterface $item
     * @return bool
     */
    public function saveDeferred(\Psr\Cache\CacheItemInterface $item)
    {
        $this->cache[$item->getKey()] = $item;

        return true;
    }

    /**
     * @return bool
     */
    public function commit()
    {
        foreach ($this->cache as $item) {
            $item->new = false;
        }

        return true;
    }
}
