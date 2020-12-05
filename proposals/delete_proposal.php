<?php
session_start();
require_once("../includes/db.php");
if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('../login','_self')</script>";
}
if(isset($_GET['proposal_id'])){
	$status = $_POST['change_status'];
	$proposal_id = $_POST['proposal_id'];
	if($status == 'true'){
		$update_status = $db->update("proposals", array('proposal_status' => 'pending') ,array("proposal_id"=>$proposal_id));
	}

	$delete_id = $input->get('proposal_id');
	$login_seller_user_name = $_SESSION['seller_user_name'];
	$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
	$row_login_seller = $select_login_seller->fetch();
	$login_seller_id = $row_login_seller->seller_id;

	$update_proposal = $db->update("proposals",array("proposal_status"=>'deleted'),array('proposal_id'=>$delete_id,"proposal_seller_id"=>$login_seller_id));

	@$deleteTopRated = $db->delete("top_proposals",array("proposal_id"=>$delete_id));

  if($update_proposal){
		echo "<script>alert('One proposal has been deleted.');</script>";
		echo "<script>window.open('view_proposals.php','_self');</script>";
	}else{
		echo "<script>window.open('../index','_self');</script>";
	}

}
?>