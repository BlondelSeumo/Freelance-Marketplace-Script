<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	

if(isset($_GET['approve_referral'])){
	
   $referral_id = $input->get('approve_referral');

   $get_referrals = $db->select("referrals",array("referral_id" => $referral_id));
   $row_referrals = $get_referrals->fetch();
   $seller_id = $row_referrals->seller_id;
   $comission = $row_referrals->comission;

   $select_seller = $db->select("sellers",array("seller_id" => $seller_id));
   $row_seller = $select_seller->fetch();
   $seller_user_name = $row_seller->seller_user_name;

   $update_current_balance = $db->query("update seller_accounts set current_balance=current_balance+:plus where seller_id='$seller_id'",array("plus"=>$comission));

   $update_referral = $db->update("referrals",array("status" => 'approved'),array("referral_id" => $referral_id));

   if($update_referral){
   	
      $insert_log = $db->insert_log($admin_id,"referral",$referral_id,"approved");

      $n_date = date("F d, Y");
      $insert_notification = $db->insert("notifications",array("receiver_id" => $seller_id,"sender_id" => "admin_$admin_id","order_id" => $referral_id,"reason" => "referral_approved","date" => $n_date,"status" => "unread"));

      echo "<script>alert('Referral approved successfully. Commission has be added to $seller_user_name shopping balance.');</script>";
      echo "<script>window.open('index?view_referrals','_self');</script>";
   	
   }

}

?>

<?php } ?>