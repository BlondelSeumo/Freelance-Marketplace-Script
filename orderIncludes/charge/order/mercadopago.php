<?php

session_start();
include("../../../includes/db.php");
include("$dir/functions/payment.php");
if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('$site_url/login','_self')</script>";
}

if(isset($_GET['reference_no'])){
	$payment = new Payment();
	if($payment->mercadopago_execute()){
      $_SESSION['method'] = "mercadopago";
		require_once("../orderTip.php");
	}
}