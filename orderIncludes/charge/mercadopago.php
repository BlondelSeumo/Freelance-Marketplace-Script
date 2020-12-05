<?php
session_start();
require_once("../../includes/db.php");
require_once("$dir/functions/payment.php");
require_once("$dir/functions/processing_fee.php");
if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('login.php','_self');</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

if(isset($_POST['mercadopago'])){

	$order_id = $_SESSION['tipOrderId'];
	$amount = $_SESSION['tipAmount'];
	
	$processing_fee = processing_fee($amount);

	$reference_no = mt_rand();

	$data = [];
	$data['type'] = "orderTip";
	$data['content_id'] = $_SESSION['tipOrderId'];
	$data['reference_no'] = $reference_no;
	$data['title'] = 'Order Tip Payment';
	$data['price'] = $amount+$processing_fee;
	$data['qty'] = 1;
	$data['sub_total'] = $amount;
	$data['total'] = $amount+$processing_fee;

   $lastId = $db->query("SHOW TABLE STATUS LIKE 'temp_orders'")->fetch(PDO::FETCH_ASSOC)['Auto_increment'];

   $data['cancel_url'] = "$site_url/cancel_payment?id=$lastId";
	$data['redirect_url'] = "$site_url/orderIncludes/charge/order/mercadopago?reference_no=$reference_no";

	$payment = new Payment();
	$payment->mercadopago($data);

}else{
	echo "<script>window.open('index','_self')</script>";
}
