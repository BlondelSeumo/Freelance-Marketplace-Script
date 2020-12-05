<?php

session_start();
require_once("../includes/db.php");
require_once("../functions/mailer.php");

if(!isset($_SESSION['seller_user_name'])){
	
	echo "<script>window.open('../login','_self')</script>";
	
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

$proposal_id = $input->post('proposal_id');
$request_id = $input->post('request_id');
$description = $input->post('description');
$delivery_time = $input->post('delivery_time');
$amount = $input->post('amount');

$get_requests = $db->select("buyer_requests",array("request_id" => $request_id));
$row_requests = $get_requests->fetch();
$seller_id = $row_requests->seller_id;

$select_buyer = $db->select("sellers",array("seller_id" => $seller_id));
$row_buyer = $select_buyer->fetch();
$buyer_user_name = $row_buyer->seller_user_name;
$buyer_email = $row_buyer->seller_email;

$n_date = date("F d, Y");

$insert_notification = $db->insert("notifications",array("receiver_id"=>$seller_id,"sender_id"=>$login_seller_id,"order_id"=>$request_id,"reason"=>"offer","date"=>$n_date,"status"=>"unread"));

$insert_offer = $db->insert("send_offers",array("request_id"=>$request_id,"sender_id"=>$login_seller_id,"proposal_id"=>$proposal_id,"description"=>$description,"delivery_time"=>$delivery_time,"amount"=>$amount,"status"=>'active'));
$update_seller = $db->query("update sellers set seller_offers=seller_offers-1 where seller_id='$login_seller_id'");

$data = [];
$data['template'] = "offer_received";
$data['to'] = $buyer_email;
$data['subject'] = "$site_name: Offer received for your request";
$data['user_name'] = $buyer_user_name;
$data['seller_user_name'] = $login_seller_user_name;
$data['request_id'] = $request_id;
send_mail($data);

if($update_seller){
	echo "<script>alert('Your offer has been submitted successfully.')</script>";
	echo "<script>window.open('$site_url/requests/buyer_requests','_self')</script>";
}

?>