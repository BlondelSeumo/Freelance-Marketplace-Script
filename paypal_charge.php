<?php

session_start();
include("includes/db.php");
include("functions/payment.php");
require_once("functions/functions.php");
if(!isset($_SESSION['seller_user_name'])){
	echo"<script>window.open('login.php','_self');</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

if(isset($_POST['paypal'])){

	$type = $input->post('type');

	if($type == "proposal"){
		include("paypal/proposal_charge.php");
	}elseif($type == "cart") {
		include("paypal/cart_charge.php");
	}elseif($type == "message_offer") {
		include("paypal/message_offer_charge.php");
	}elseif($type == "message_offer") {
		include("paypal/message_offer_charge.php");
	}elseif($type == "request_offer") {
		include("paypal/request_offer_charge.php");
	}elseif($type == "featured_listing") {
		include("paypal/featured_listing_charge.php");
	}elseif($type == "orderTip") {
		include("paypal/tip_charge.php");
	}elseif($type == "orderExtendTime") {
		include("plugins/videoPlugin/extendTime/charge/paypalCharge.php");
	}
   
   $payment = new Payment();
	$payment->paypal($data,$processing_fee);

}else{
	echo "<script>window.open('index','_self')</script>";
}