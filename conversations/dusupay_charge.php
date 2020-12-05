<?php 
session_start();
include("../includes/db.php");
include("../functions/payment.php");
require_once("../functions/functions.php");
if(!isset($_SESSION['seller_user_name'])){
echo "<script>window.open('../login.php','_self');</script>";
}
if(isset($_POST['dusupay'])){
	$select_offers = $db->select("messages_offers",array("offer_id" => $_SESSION['c_message_offer_id']));
	$row_offers = $select_offers->fetch();
	$proposal_id = $row_offers->proposal_id;
	$amount = $row_offers->amount;
	$processing_fee = processing_fee($amount);
	
	$payment = new Payment();
	$data = [];
	$data['type'] = "message_offer";

	if(isset($_POST['method'])){
		$data['method'] = $input->post('method');
	}

	if(isset($_POST['provider_id'])){
		$data['provider_id'] = $input->post('provider_id');
	}

	if(isset($_POST['account_number'])){
		$account_number = $input->post('account_number');
		$data['account_number'] = $account_number;
	}
	
	if(isset($_POST['voucher'])){
		$voucher = $input->post('voucher');
		$data['voucher'] = $voucher;
	}

	$data['name'] = "Message Offer Payment";
	$data['content_id'] = $_SESSION['c_message_offer_id'];
	$data['price'] = $amount;
	$data['amount'] = $amount + $processing_fee;
	$data['redirect_url'] = "$site_url/dusupay_order?message_offer_id={$_SESSION['c_message_offer_id']}&";
	$payment->dusupay($data);

}else{
	echo "<script>window.open('../index','_self');</script>";
}