<?php


$config = include('config.php');
require('Poloniex.php');
require('Coinman.php');


// Crates an instance of CoinMan
$cm = new Coinman($config['api_key'], $config['api_secret']);

// We Run CoinMan
$cm->runCoinMan();

// this pages only return loaded json
$load_data = $cm->loadData();

?>
