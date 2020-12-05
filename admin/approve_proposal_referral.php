<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	
?>

<?php

if(isset($_GET['approve_proposal_referral'])){
	
   $referral_id = $input->get('approve_proposal_referral');


   $get_referrals = $db->select("proposal_referrals",array("referral_id" => $referral_id));
   $row_referrals = $get_referrals->fetch();
   $seller_id = $row_referrals->seller_id;
   $referrer_id = $row_referrals->referrer_id;
   $comission = $row_referrals->comission;


   $sel_referrer = $db->select("sellers",array("seller_id" => $referrer_id));
   $referrer_user_name = $sel_referrer->fetch()->seller_user_name;


   $update_seller_balance = $db->query("update seller_accounts set current_balance=current_balance-:minus where seller_id='$seller_id'",array("minus"=>$comission));

   $update_referrer_balance = $db->query("update seller_accounts set current_balance=current_balance+:plus where seller_id='$referrer_id'",array("plus"=>$comission));

   $update_referral = $db->update("proposal_referrals",array("status"=>'approved'),array("referral_id"=>$referral_id));

   if($update_referral){

      $n_date = date("F d, Y");
      $insert_notification = $db->insert("notifications",array("receiver_id" => $referrer_id,"sender_id" => "admin_$admin_id","order_id" => $referral_id,"reason" => "proposal_referral_approved","date" => $n_date,"status" => "unread"));

      $insert_log = $db->insert_log($admin_id,"proposal_referral",$referral_id,"approved");

      echo "<script>alert('Referral approved successfully. Commission has be added to $referrer_user_name shopping balance.');</script>";
      echo "<script>window.open('index?view_proposal_referrals','_self');</script>";

   }

	
}

?>

<?php } ?>