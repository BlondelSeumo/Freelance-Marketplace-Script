<?php
session_start();
include("includes/db.php");
include("functions/payment.php");
require_once("functions/processing_fee.php");
if(!isset($_SESSION['seller_user_name'])){
echo "<script>window.open('login.php','_self');</script>";
}
$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

$processing_fee = processing_fee($_SESSION['c_sub_total']);

if(isset($_POST['paystack'])){

	$payment = new Payment();
	$data = [];
	$data['type'] = "proposal";
	$reference_no = mt_rand();
	$data['reference_no'] = $reference_no;
	$data['content_id'] = $_SESSION['c_proposal_id'];
	$data['qty'] = $_SESSION['c_proposal_qty'];
	$data['price'] = $_SESSION['c_proposal_price'];
	$data['delivery_id'] = $_SESSION['c_proposal_delivery'];
	$data['revisions'] = $_SESSION['c_proposal_revisions'];
	$data['sub_total'] = $_SESSION['c_sub_total'];
	$data['total'] = $_SESSION['c_sub_total'] + $processing_fee;

	if(isset($_SESSION['c_proposal_extras'])){
		$data['extras'] = base64_encode(serialize($_SESSION['c_proposal_extras']));
	}

	if(isset($_SESSION['c_proposal_minutes'])){
		$data['minutes'] = $_SESSION['c_proposal_minutes'];
	}

	$data['redirect_url'] = "$site_url/paystack_order?reference_no=$reference_no";
	$payment->paystack($data);

}else{
	echo "<script>window.open('index','_self')</script>";
}

?>