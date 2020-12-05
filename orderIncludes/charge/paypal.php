<?php

session_start();
require_once("../../includes/db.php");
require_once("$dir/functions/payment.php");
require_once("$dir/functions/processing_fee.php");
if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('$site_url/login','_self')</script>";
}

function escape($value){
  return htmlentities($value,ENT_QUOTES,'UTF-8');
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

if(isset($_POST['paypal'])){
	
	$order_id = $_SESSION['tipOrderId'];
	$amount = $_SESSION['tipAmount'];

	// proposal
	$processing_fee = processing_fee($amount);

	$payment = new Payment();
	$data = [];
	$data['type'] = "orderTip";
	$data['content_id'] = $_SESSION['tipOrderId'];
	$reference_no = mt_rand();
	$data['reference_no'] = $reference_no;
	$data['name'] = "Order Tip Payment";
	$data['qty'] = 1;
	$data['price'] = $amount;
	$data['sub_total'] = $amount;
	$data['total'] = $amount+$processing_fee;

	$data['cancel_url'] = "$site_url/cancel_payment?reference_no=$reference_no";
	$data['redirect_url'] = "$site_url/orderIncludes/charge/order/paypal?reference_no=$reference_no";

	$payment->paypal($data,$processing_fee);
	
}else{
	echo "<script>window.open('index','_self')</script>";
}