<?php

session_start();

require_once("../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){
	
echo "<script>window.open('../login','_self')</script>";
	
}

if(isset($_GET['request_id'])){
	
$request_id = $input->get('request_id');

$update_request = $db->update("buyer_requests",array("request_status"=>'pause'),array("request_id"=>$request_id,"seller_id"=>$login_seller_id));
	
if($update_request->rowCount() == 1){
	
echo "<script>alert('One request has been paused.');</script>";
	
echo "<script>window.open('manage_requests','_self')</script>";
	
}else{ echo "<script>window.open('manage_requests','_self');</script>"; }

	
}

?>