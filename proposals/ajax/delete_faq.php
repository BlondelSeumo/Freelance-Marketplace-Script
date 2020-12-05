<?php

session_start();

require_once("../../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){

echo "<script>window.open('../login','_self')</script>";

}

$data = $input->post();

unset($data['change_status']);

$delete_extra = $db->delete("proposals_faq",$data);

if(isset($_POST["change_status"])){
	$status = $_POST['change_status'];
	$proposal_id = $_POST['proposal_id'];
	if($status == 'true' && isset( $_POST['proposal_id'] )){
		$update_status = $db->update("proposals", array('proposal_status' => 'pending') ,array("proposal_id"=>$proposal_id));
	}
}
