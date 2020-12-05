<?php

session_start();
require_once("includes/db.php");

$api_key = $row_api->api_key;
$main_currency = $row_api->main_currency;
$server = $row_api->server;

function getRate($amount,$to_currency){
   global $api_key;
   global $main_currency;
   global $server;

   $apikey = $api_key;

   $from_Currency = urlencode($main_currency);
   $to_Currency = urlencode($to_currency);
   $query =  "{$from_Currency}_{$to_Currency}";

   // change to the free URL if you're using the free version
   $url = "$server/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}";
   // echo $url;
   @$json = file_get_contents("$url");
   $obj = json_decode($json, true);

   $val = floatval($obj["$query"]);

   $total = $val * $amount;
   return number_format($total, 2, '.', '');
}

if(isset($_GET['id'])){

	if($_SERVER["HTTP_REFERER"]){
		$id = $input->get('id');
      if(empty($id)){
         unset($_SESSION['siteCurrency']);
      }else{

         $get_currency = $db->select("site_currencies",['id'=>$id]);
         $row = $get_currency->fetch();
         $code = $row->code;

         $_SESSION['siteCurrency'] = $id;
         $_SESSION['conversionRate'] = getRate(1,$code);

      }
      echo "<script>window.open('{$_SERVER["HTTP_REFERER"]}','_self');</script>";
	}

}