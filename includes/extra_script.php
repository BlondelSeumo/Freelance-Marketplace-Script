<?php

@session_start();
require_once("db.php");

$get_featured_proposals = $db->select("featured_proposals");
while($row_featured_proposals = $get_featured_proposals->fetch()){
   $featured_id = $row_featured_proposals->featured_id;	
   $featured_proposal_id = $row_featured_proposals->proposal_id;	
   $end_date = new DateTime($row_featured_proposals->end_date);
   $current_date = new DateTime();

   if($current_date >= $end_date){	
      $update_proposal = $db->update("proposals",array("proposal_featured" => 'no'),array("proposal_id" => $featured_proposal_id));
      $delete_featured_proposal = $db->delete("featured_proposals",array('featured_id' => $featured_id)); 
   }

}


if(isset($_SESSION['seller_user_name'])){
	
   $login_seller_user_name = $_SESSION['seller_user_name'];
   $select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
   $row_login_seller = $select_login_seller->fetch();
   $login_seller_id = $row_login_seller->seller_id;

   $get_revenues = $db->select("revenues",array("seller_id" => $login_seller_id,"status" => "pending"));
   while($row_revenues = $get_revenues->fetch()){
      $revenue_id = $row_revenues->revenue_id;
      $amount = $row_revenues->amount;
      $end_date = new DateTime($row_revenues->end_date);
      $current_date = new DateTime();
      if($current_date >= $end_date){
      	
      $update_seller_account = $db->query("update seller_accounts set pending_clearance=pending_clearance-:minus,current_balance=current_balance+:plus where seller_id='$login_seller_id'",array("minus"=>$amount,"plus"=>$amount));
      $update_revenues = $db->update("revenues",array("status" => 'cleared'),array("revenue_id" => $revenue_id));

      }

   }

}

?>