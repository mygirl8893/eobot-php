Eobot PHP client
================

[![Build Status](https://travis-ci.org/rickdenhaan/eobot-php.png?branch=master)](https://travis-ci.org/rickdenhaan/eobot-php)
[![Coverage Status](https://coveralls.io/repos/rickdenhaan/eobot-php/badge.png?branch=master)](https://coveralls.io/r/rickdenhaan/eobot-php)

This simple PHP client communicates with the Eobot Cloud Bitcoin mining service to manage your mining. For more information about Eobot, see https://www.eobot.com


Usage
-----

```php
use Capirussa\Eobot;

try {
    $client = Eobot\Client($userId);
    $currentlyMining = $client->getMiningMode();
    $client->setMiningMode(Client::COIN_LITECOIN, $userName, $password);
} catch (\Exception $exception) {
    // something went wrong, fix it and try again!
}
```

If you find any bugs, please raise an issue on Github.

Happy coding!