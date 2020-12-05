<?php 

session_start();

include("../includes/db.php");
include("../functions/payment.php");
include("../functions/processing_fee.php");

if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('../login','_self');</script>";  
}

if(isset($_POST['paystack'])){

	$select_offers = $db->select("messages_offers",array("offer_id" => $_SESSION['c_message_offer_id']));
	$row_offers = $select_offers->fetch();
	$proposal_id = $row_offers->proposal_id;
	$amount = $row_offers->amount;

	$processing_fee = processing_fee($amount);

	$payment = new Payment();

	$data = [];
	$data['type'] = "message_offer";
	$reference_no = mt_rand();
	$data['reference_no'] = $reference_no;
	$data['content_id'] = $_SESSION['c_message_offer_id'];
	$data['qty'] = 1;
	$data['sub_total'] = $amount;
	$data['total'] = $amount + $processing_fee;

	$data['redirect_url'] = "$site_url/paystack_order?reference_no=$reference_no";
	$payment->paystack($data);

}else{

	echo "<script>window.open('../index','_self');</script>";

}