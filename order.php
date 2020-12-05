<?php
session_start();

require_once("includes/db.php");
require_once("functions/functions.php");
require_once("functions/mailer.php");
if(!isset($_SESSION['checkout_seller_id']) and !isset($_SESSION['cart_seller_id']) and !isset($_SESSION['message_offer_id']) and !isset($_SESSION['offer_id'])){
	echo "<script>window.open('index','_self');</script>";
}

?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
	<title> <?= $site_name; ?> - Order </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="<?= $site_author; ?>">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
	<link href="styles/bootstrap.css" rel="stylesheet">
	<link href="styles/styles.css" rel="stylesheet">
	<link href="styles/categories_nav_styles.css" rel="stylesheet">
	<link href="font_awesome/css/font-awesome.css" rel="stylesheet">
	<link href="styles/owl.carousel.css" rel="stylesheet">
	<link href="styles/owl.theme.default.css" rel="stylesheet">
   <link href="styles/sweat_alert.css" rel="stylesheet">
	<!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
   <script src="js/ie.js"></script>
	<script type="text/javascript" src="js/sweat_alert.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<style>
		body *, 
		body *:before, 
		body *:after {
			-webkit-box-sizing: border-box !important;
			-ms-box-sizing: border-box !important;
			-moz-box-sizing: border-box !important;
			-o-box-sizing: border-box !important;
			box-sizing: border-box !important;
		}
		.swal2-icon.swal2-success .swal2-success-ring {
			position: absolute;
			top: 0.1em;
			left: 0em;
		}
	</style>
</head>
<body class="is-responsive">
	<img src="images/bg4.jpeg" alt="logout-pic">
</body>
</html>
<?php

require_once("includes/db.php");
if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('login','_self');</script>";
}

if(isset($_SESSION['seller_user_name'])){
	
	$login_seller_user_name = $_SESSION['seller_user_name'];
	$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
	$row_login_seller = $select_login_seller->fetch();
	$login_seller_id = $row_login_seller->seller_id;
	$login_seller_email = $row_login_seller->seller_email;

	$order_auto_complete = $row_general_settings->order_auto_complete;

	require_once("orderIncludes/order/checkoutOrder.php");
	require_once("orderIncludes/order/cartOrder.php");
	require_once("orderIncludes/order/offerOrder.php");
	require_once("orderIncludes/order/messageOfferOrder.php");

}