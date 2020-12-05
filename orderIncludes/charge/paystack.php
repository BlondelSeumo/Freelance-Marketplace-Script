<?php

session_start();
require_once("../../includes/db.php");
require_once("$dir/functions/payment.php");
require_once("$dir/functions/processing_fee.php");
if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('$site_url/login','_self')</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

if(isset($_POST['paystack'])){

	$order_id = $_SESSION['tipOrderId'];
	$amount = $_SESSION['tipAmount'];
	
	$processing_fee = processing_fee($amount);
	
	$payment = new Payment();

	$reference_no = mt_rand();

	$data = [];
	$data['type'] = "orderTip";
	$data['content_id'] = $_SESSION['tipOrderId'];
	$data['reference_no'] = $reference_no;
	$data['price'] = $amount;
	$data['qty'] = 1;
	$data['sub_total'] = $amount;
	$data['total'] = $amount+$processing_fee;

	$data['redirect_url'] = "$site_url/orderIncludes/charge/order/paystack?reference_no=$reference_no";
	$payment->paystack($data);

}else{
	echo "<script>window.open('index','_self')</script>";
}

?>