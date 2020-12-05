<?php

session_start();

require_once("../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){
	
echo "<script>window.open('../login','_self')</script>";
	
}

if(isset($_GET['proposal_id'])){
	
$proposal_id = $input->get('proposal_id');
	
$update_proposal = $db->update("proposals",array("proposal_status"=>'pause'),array("proposal_id"=>$proposal_id,"proposal_seller_id"=>$login_seller_id));

if($update_proposal->rowCount() == 1){
	
echo "<script>alert('Your proposal has been successfully paused!');</script>";
	
echo "<script>window.open('view_proposals.php','_self');</script>";
	
}else{ echo "<script>window.open('view_proposals','_self');</script>"; }

	
}

?>