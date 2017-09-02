<?php

/**
 *
 * @package CoinMan
 */

class Coinman
{

  private $db;


  public function __construct($api_key, $api_secret)
  {
    $this->poloniex = new Poloniex($api_key, $api_secret);
    $this->db = new PDO('mysql:host=localhost;dbname=hng', 'hngintern', '@hng.intern1');
  }

  /**
   * Entry Method for running coinman
   * @return void
   */
  public function runCoinMan()
  {
    // two Ajax calls running
    if($_GET['type'] == 'catchData') {
      $this->catchData();
    } else {
      $this->loadData();
    }
  }


  public function catchData() {
    $coins = $this->getPairsFromDb();
    if(empty($coins))
      $coins = $this->poloniex->get_trading_pairs();

    $coins = $this->getOnlyBtc($coins);
    $this->savePairs($coins);

    $cnt_coins = 0;

    foreach ($coins as $key => $coin) {

        $tradeData = $this->poloniex->get_trade_history($coin);
        $sales = $this->countTransaction($tradeData, 'sell');
        $buys = $this->countTransaction($tradeData, 'buy');

        $coinData = [
                    $coin,
                    $buys,
                    $sales,
        ];

        $this->saveCoinData($coinData);
    }

  }

  public function loadData() {
    $exe = $this->db->query("SELECT * FROM trade_history");
    $displayData = $exe->fetchAll();
    if(empty($displayData)) {
      // i've not taken care of this....
    }
    $this->json_response($displayData);
  }

  private function saveCoinData($coinData) {
    $coin = $coindata[0];
    $buys = $coinData[1];
    $sales = $coinData[2];
    $this->db->query("INSERT INTO trade_history VALUES (null, '$coin', '$buys', '$sales' )");
  }

  private function getPairsFromDb() {
    $exe = $this->db->query("SELECT pairs FROM dragPairs LIMIT 1");
    $pairJSON = $exe->fetch()['pairs'];
    return json_decode($pairJSON, true);
  }

  private function savePairs($pairs) {
    $pairs = json_encode($pairs);
    $exe = $this->db->query("UPDATE dragPairs SET pairs = $pairs");
  }


  private function getOnlyBtc($pairs) {
    foreach ($pairs as $key => $value) {
      if(!strpos($value, 'BTC')) {
        unset($pairs[$key]);
      }
    }
    return $pairs;
  }

  private function countTransaction($tradeData, $type) {
    $count = 0;
    if(!empty($tradeData)) {
      foreach ($tradeData as $key => $value) {
        if($value['type'] == $type)
          $count++;
      }
    }
    return $count;
  }

  // Not being used for now
  private function getDifference($pair, $buys, $sales) {
    $previousData = $this->db->query("SELECT * FROM trade_history WHERE pair = '$pair'");
    $previousData = $previousData->fetch();
  }

  /**
   * Sends back json_encoded data to the client
   * @param  mixed $data data to be sent
   * @return void
   */
  public function json_response($data)
  {
    header('Content-type: application/json');
    echo json_encode($data);
  }

}
