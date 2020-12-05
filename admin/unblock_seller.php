<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

?>

<?php

if(isset($_GET['unblock_seller'])){
	
$seller_id = $input->get('unblock_seller');
	
$update_seller = $db->update("sellers",array("seller_status" => 'away'),array("seller_id" => $seller_id));
	
if($update_seller){

$update_proposals = $db->update("proposals",array("proposal_status"=>'active'),array("proposal_seller_id"=>$seller_id,"proposal_status" => 'active'));

$insert_log = $db->insert_log($admin_id,"seller",$seller_id,"unblocked");

echo "<script>alert('This Seller Has Been Unblocked ,He Is Able To Login Into His Account.');</script>";
	
echo "<script>window.open('index?view_sellers','_self');</script>";

	
}
	
}

?>

<?php } ?>