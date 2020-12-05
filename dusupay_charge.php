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

if(isset($_POST['dusupay'])){

	$payment = new Payment();

	$data = [];
	
	$data['type'] = "proposal";

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

	$data['name'] = "Proposal Payment";
	$data['content_id'] = $_SESSION['c_proposal_id'];
	$data['qty'] = $_SESSION['c_proposal_qty'];
	$data['price'] = $_SESSION['c_sub_total'];
	$data['amount'] = $_SESSION['c_sub_total']+$processing_fee;
	$data['delivery_id'] = $_SESSION['c_proposal_delivery'];
	$data['revisions'] = $_SESSION['c_proposal_revisions'];
	
	if(isset($_SESSION['c_proposal_extras'])){
		$data['extras'] = base64_encode(serialize($_SESSION['c_proposal_extras']));
	}

	if(isset($_SESSION['c_proposal_minutes'])){
		$data['minutes'] = $_SESSION['c_proposal_minutes'];
	}

	// $data['redirect_url'] = "$site_url/dusupay_order?checkout_seller_id=$login_seller_id&proposal_id={$_SESSION['c_proposal_id']}&proposal_qty={$_SESSION['c_proposal_qty']}&proposal_price={$_SESSION['c_sub_total']}&proposal_delivery={$_SESSION['c_proposal_delivery']}&proposal_revisions={$_SESSION['c_proposal_revisions']}&$extras&$minutes&";

	$payment->dusupay($data);

}else{
	echo "<script>window.open('index','_self')</script>";
}