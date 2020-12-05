<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

?>

<?php

if(isset($_GET['decline_proposal_referral'])){
	
$referral_id = $input->get('decline_proposal_referral');

$get_referrals = $db->select("proposal_referrals",array("referral_id" => $referral_id));

$row_referrals = $get_referrals->fetch();

$seller_id = $row_referrals->seller_id;

$referrer_id = $row_referrals->referrer_id;


$sel_referrer = $db->select("sellers",array("seller_id" => $referrer_id));

$referrer_user_name = $sel_referrer->fetch()->seller_user_name;


$update_referral = $db->update("proposal_referrals",array("status" => 'declined'),array("referral_id" => $referral_id));

if($update_referral){

$insert_log = $db->insert_log($admin_id,"proposal_referral",$referral_id,"declined");

echo "<script>alert('Referral has been declined successfully. No commision has been rewarded to $referrer_user_name.');</script>";
	
echo "<script>window.open('index?view_proposal_referrals','_self');</script>";
	
}


	
}

?>

<?php } ?>