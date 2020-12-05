<?php
session_start();
require_once("../../includes/db.php");
if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('$site_url/login','_self')</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name"=>$login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

if(isset($_POST['orderTip'])){

	$order_id = $_SESSION['tipOrderId'];
	$amount = $_SESSION['tipAmount'];

	// update seller balance
	$update_balance = $db->query("update seller_accounts set used_purchases=used_purchases+:plus,current_balance=current_balance-:minus where seller_id='$login_seller_id'",array("plus"=>$amount,"minus"=>$amount));
   
	if($update_balance){
		$_SESSION['method'] = "shopping_balance";
		require_once("orderTip.php");
	}

}