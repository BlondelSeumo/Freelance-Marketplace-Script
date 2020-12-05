<?php

session_start();
require_once("includes/db.php");

if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('login','_self');</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

if(!isset($_GET['reference_no'])){
   echo "<script> window.open('index','_self'); </script>";
}

$reference_no = $input->get('reference_no');

$get_order = $db->select("dusupay_orders",['reference_no'=>$reference_no]);
$row_order = $get_order->fetch();
$count_order = $get_order->rowCount();

if($count_order == 0){
	echo "<script> window.open('index','_self'); </script>";
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
$status = $row_order->status;

if($buyer_id != $login_seller_id){
	echo "<script> window.open('index','_self'); </script>";
}

if($status != "COMPLETED"){
	echo "<script> window.open('index','_self'); </script>";
}

if($type == "proposal" & $status == "COMPLETED"){

	$_SESSION['checkout_seller_id'] = $buyer_id;
	$_SESSION['proposal_id'] = $content_id;
	$_SESSION['proposal_qty'] = $qty;
	$_SESSION['proposal_price'] = $price;
	$_SESSION['proposal_delivery'] = $delivery_id;
	$_SESSION['proposal_revisions'] = $revisions;

	if(!empty($extras)){
    	$_SESSION['proposal_extras'] = unserialize(base64_decode($extras));
	}

	if(!empty($minutes)){
		$_SESSION['proposal_minutes'] = $minutes;
	}

	$_SESSION['method'] = "mobile_money";

	$delete = $db->delete("dusupay_orders",array('reference_no'=>$reference_no));

	echo "<script>window.open('order','_self');</script>";

}


if($type == "cart" & $status == "COMPLETED"){
			
	$_SESSION['cart_seller_id'] = $buyer_id;
	$_SESSION['reference_no'] = $content_id;
	$_SESSION['method'] = "mobile_money";

	$delete = $db->delete("dusupay_orders",array('reference_no'=>$reference_no));

	echo "<script>window.open('order','_self');</script>";

}


if($type == "featured_listing" & $status == "COMPLETED"){
	
	$_SESSION['proposal_id'] = $content_id;
	$_SESSION['method'] = "mobile_money";

	echo "<script>window.open('$site_url/proposals/featured_proposal','_self')</script>";

}


if($type == "request_offer" & $status == "COMPLETED"){

	$_SESSION['offer_id'] = $content_id;
	$_SESSION['offer_buyer_id'] = $buyer_id;
	$_SESSION['method'] = "mobile_money";

	$delete = $db->delete("dusupay_orders",array('reference_no'=>$reference_no));

	echo "<script>window.open('order','_self');</script>";

}


if($type == "message_offer" & $status == "COMPLETED"){
			
	$_SESSION['message_offer_id'] = $content_id;
	$_SESSION['message_offer_buyer_id'] = $buyer_id;
	$_SESSION['method'] = "mobile_money";

	$delete = $db->delete("dusupay_orders",array('reference_no'=>$reference_no));

	echo "<script>window.open('order','_self');</script>";

}

if($status == "CANCELLED" or $status == "FAILED"){

	echo "<script>window.open('index','_self');</script>";	

}