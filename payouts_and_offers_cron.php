<?php
require_once("includes/db.php");

$select = $db->select("seller_payment_settings");
while($row = $select->fetch()){
   $level_id = $row->level_id;
   $payout_day = $row->payout_day;
   $current_day = date("d");
   if($current_day == $payout_day){
      $update_sellers = $db->query("update sellers set seller_payouts=0 where seller_level='$level_id'");
   }
}

$update_seller = $db->update("sellers",array("seller_offers" => 10));

