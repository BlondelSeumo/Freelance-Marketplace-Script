<?php 
session_start();
include("../includes/db.php");
include("../functions/payment.php");
include("../functions/processing_fee.php");
if(!isset($_SESSION['seller_user_name'])){
echo "<script>window.open('../login.php','_self');</script>";
}

if(isset($_POST['coinpayments'])){

	$get_payment_settings = $db->select("payment_settings");
	$row_payment_settings = $get_payment_settings->fetch();
	$featured_fee = $row_payment_settings->featured_fee;
	
	$processing_fee = processing_fee($featured_fee);

	$select_proposals = $db->select("proposals",array("proposal_id" => $_SESSION['f_proposal_id']));
	$row_proposals = $select_proposals->fetch();
	$proposal_title = $row_proposals->proposal_title;
	
	$payment = new Payment();
	$data = [];
	$data['type'] = "featured_listing";
	$data['content_id'] = $_SESSION['f_proposal_id'];
	$reference_no = mt_rand();
	$data['reference_no'] = $reference_no;
	$data['name'] = $proposal_title;
	$data['qty'] = 1;
	$data['sub_total'] = $featured_fee;
	$data['total'] = $featured_fee + $processing_fee;
	$data['cancel_url'] = "$site_url/cancel_payment?reference_no=$reference_no";

	$payment->coinpayments($data,$processing_fee);

}else{
	echo "<script>window.open('../ndex','_self');</script>";
}