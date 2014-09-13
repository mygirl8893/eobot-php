<?php

require_once(dirname(__FILE__) . '/vendor/autoload.php');

spl_autoload_register();

use \Capirussa\Eobot\Client;

$eo = new Client(49480);
//$eo->setAutomaticWithdraw(Client::COIN_BITCOIN, 1000000, '14thgkWD3LeMEr9rznNAdEhM4h3NWPquQc', 'eobot@rickdenhaan.nl', '8LPMxwQGiUf7jY{gWGG');
//$eo->setAutomaticWithdraw(Client::COIN_BITCOIN, 0.0000000000000000001, '14thgkWD3LeMEr9rznNAdEhM4h3NWPquQc', 'eobot@rickdenhaan.nl', '8LPMxwQGiUf7jY{gWGG');

var_dump($eo->getLastResponse()->getRawHeaders());
var_dump($eo->getLastResponse()->getRawBody());