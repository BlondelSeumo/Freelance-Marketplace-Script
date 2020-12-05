<?php 
session_start();
include("../includes/db.php");
include("../functions/payment.php");
require_once("../functions/functions.php");
if(!isset($_SESSION['seller_user_name'])){
echo "<script>window.open('login.php','_self');</script>";
}
if(isset($_POST['dusupay'])){
	$get_payment_settings = $db->select("payment_settings");
	$row_payment_settings = $get_payment_settings->fetch();
	$featured_fee = $row_payment_settings->featured_fee;
	$processing_fee = processing_fee($featured_fee);

	$data = [];
	$data['type'] = "featured_listing";

	if(isset($_POST['account_number'])){
		$account_number = $input->post('account_number');
		$data['account_number'] = $account_number;
	}
	
	if(isset($_POST['voucher'])){
		$voucher = $input->post('voucher');
		$data['voucher'] = $voucher;
	}

	$data['content_id'] = $_SESSION['f_proposal_id'];
	$data['name'] = "Payment For Proposal Featured Listing";
	$data['price'] = $featured_fee;
	$data['amount'] = $featured_fee+$processing_fee;
	$data['redirect_url'] = "$site_url/dusupay_order?proposal_id={$_SESSION['f_proposal_id']}&featured_listing=1&";

	$payment = new Payment();
	$payment->dusupay($data);

}else{
	echo "<script>window.open('../index','_self');</script>";
}