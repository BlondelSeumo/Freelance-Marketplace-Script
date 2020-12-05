<?php

session_start();
include("includes/db.php");
include("functions/payment.php");
require_once("functions/functions.php");
if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('login','_self');</script>";
}

$reference_no = mt_rand();

$select_proposals = $db->select("proposals",array("proposal_id" => $_SESSION['c_proposal_id']));
$row_proposals = $select_proposals->fetch();
$proposal_url = $row_proposals->proposal_url;
$proposal_seller_id = $row_proposals->proposal_seller_id;

$processing_fee = processing_fee($_SESSION['c_sub_total']);

$data = [];
$data['type'] = 'proposal';

$data['reference_no'] = $reference_no;
$data['content_id'] = $_SESSION['c_proposal_id'];
$data['name'] = $row_proposals->proposal_title;
$data['qty'] = $_SESSION['c_proposal_qty'];
$data['price'] = $_SESSION['c_proposal_price'];
$data['delivery_id'] = $_SESSION['c_proposal_delivery'];
$data['revisions'] = $_SESSION['c_proposal_revisions'];
$data['processing_fee'] = $processing_fee;

$data['sub_total'] = $_SESSION['c_sub_total'];
$data['total'] = $_SESSION['c_sub_total'] + $processing_fee;

if(isset($_SESSION['c_proposal_extras'])){
   $data['extras'] = base64_encode(serialize($_SESSION['c_proposal_extras']));
}

if(isset($_SESSION['c_proposal_minutes'])){
   $data['minutes'] = $_SESSION['c_proposal_minutes'];
}

$data['desc'] = "Proposal Payment";

$data['cancel_url'] = "$site_url/cancel_payment";

$payment = new Payment();
$payment->stripe($data);