<?php

session_start();

require_once("../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){
	
echo "<script>window.open('../login','_self')</script>";
	
}

if(isset($_GET['request_id'])){
	
$request_id = $input->get('request_id');

$delete_request = $db->delete("buyer_requests",array("request_id"=>$request_id,"seller_id"=>$login_seller_id)); 
	
if($delete_request->rowCount() == 1){

$delete_send_offers = $db->delete("send_offers",array('request_id' => $request_id)); 
	
echo "<script>alert('One request has been deleted successfully.');</script>";
	
echo "<script>window.open('manage_requests','_self')</script>";
	
}else{ echo "<script>window.open('manage_requests','_self');</script>"; }


}

?>