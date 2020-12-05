<?php 

session_start();
include("../includes/db.php");
include("../functions/payment.php");
include("../functions/functions.php");

if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('../login.php','_self');</script>";
}

if(isset($_POST['paystack'])){
	$select_offers = $db->select("send_offers",array("offer_id" => $_SESSION['c_offer_id']));
	$row_offers = $select_offers->fetch();
	$proposal_id = $row_offers->proposal_id;
	$amount = $row_offers->amount;
	$processing_fee = processing_fee($amount);

	$data = [];
	$data['type'] = "request_offer";
	$data['content_id'] = $_SESSION['c_offer_id'];
	$reference_no = mt_rand();
	$data['reference_no'] = $reference_no;
	$data['price'] = $amount+$processing_fee;
	$data['sub_total'] = $amount;
	$data['total'] = $amount+$processing_fee;
	
	$data['redirect_url'] = "$site_url/paystack_order?reference_no=$reference_no";

	$payment = new Payment();
	$payment->paystack($data);

}else{
	echo "<script>window.open('../index','_self');</script>";
}