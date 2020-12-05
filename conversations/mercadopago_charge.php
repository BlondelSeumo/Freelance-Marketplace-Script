<?php 

session_start();
include("../includes/db.php");
include("../functions/payment.php");
include("../functions/processing_fee.php");

if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('../login.php','_self');</script>";
}

if(isset($_POST['mercadopago'])){

	$select_offers = $db->select("messages_offers",array("offer_id" => $_SESSION['c_message_offer_id']));
	$row_offers = $select_offers->fetch();
	$proposal_id = $row_offers->proposal_id;
	$amount = $row_offers->amount;
	$processing_fee = processing_fee($amount);

	$select_proposals = $db->select("proposals",array("proposal_id" => $proposal_id));
	$row_proposals = $select_proposals->fetch();
	$proposal_title = $row_proposals->proposal_title;

	$payment = new Payment();
	$data = [];
	$data['type'] = "message_offer";
	$reference_no = mt_rand();
	$data['reference_no'] = $reference_no;
	$data['content_id'] = $_SESSION['c_message_offer_id'];
	$data['title'] = $proposal_title;
	$data['qty'] = 1;
	$data['price'] = $amount+$processing_fee;
	$data['sub_total'] = $amount;
	$data['total'] = $amount+$processing_fee;

   $lastId = $db->query("SHOW TABLE STATUS LIKE 'temp_orders'")->fetch(PDO::FETCH_ASSOC)['Auto_increment'];

   $data['cancel_url'] = "$site_url/cancel_payment?id=$lastId";
	$data['redirect_url'] = "$site_url/mercadopago_order?reference_no=$reference_no";
	$payment->mercadopago($data,$processing_fee);

}else{
	echo "<script>window.open('../index','_self');</script>";
}