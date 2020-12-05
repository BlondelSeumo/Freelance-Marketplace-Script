<?php

session_start();
include("../../../includes/db.php");
include("$dir/functions/payment.php");;
if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('$site_url/login','_self')</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

if(!isset($_GET['reference_no'])){
   echo "<script> window.open('$site_url/index','_self'); </script>";
}

$reference_no = $input->get('reference_no');

$get_order = $db->select("dusupay_orders",['reference_no'=>$reference_no]);
$row_order = $get_order->fetch();
$count_order = $get_order->rowCount();

if($count_order == 0){
   echo "<script> window.open('$site_url/index','_self'); </script>";
}

$reference_no = $row_order->reference_no;
$buyer_id = $row_order->buyer_id;
$content_id = $row_order->content_id;
$qty = $row_order->qty;
$price = $row_order->price;
$delivery_id = $row_order->delivery_id;
$revisions = $row_order->revisions;
$minutes = $row_order->minutes;
$extras = $row_order->extras;
$currency = $row_order->currency;
$type = $row_order->type;
$message = $row_order->message;
$status = $row_order->status;

if($buyer_id != $login_seller_id){
   echo "<script> window.open('$site_url/index','_self'); </script>";
}

if($status != "COMPLETED"){
   echo "<script> window.open('$site_url/index','_self'); </script>";
}

if(isset($_GET['orderTip']) & $type == "orderTip" & $status == "COMPLETED"){

   $_SESSION['tipOrderId'] = $content_id;
   $_SESSION['tipAmount'] = $price;
   $_SESSION['tipMessage'] = $message;

   $_SESSION['method'] = "dusupay";

   $delete = $db->delete("dusupay_orders",array('reference_no'=>$reference_no));

	require_once("../orderTip.php");

}