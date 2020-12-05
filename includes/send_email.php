<?php

session_start();

require_once("db.php");
require_once("../functions/mailer.php");

if(!isset($_SESSION['seller_user_name'])){
	echo "<script> window.open('../login.php','_self'); </script>";
}

$seller_user_name = $_SESSION['seller_user_name'];
$get_seller = $db->select("sellers",array("seller_user_name" => $seller_user_name));
$row_seller = $get_seller->fetch();
$seller_email = $row_seller->seller_email;
$seller_verification = $row_seller->seller_verification;

$get_general_settings = $db->select("general_settings");   
$row_general_settings = $get_general_settings->fetch();
$site_name = $row_general_settings->site_name;
$site_email_address = $row_general_settings->site_email_address;

$data = [];
$data['template'] = "email_confirmation";
$data['to'] = $seller_email;
$data['subject'] = "$site_name : Activate Your New Account!";
$data['seller_user_name'] = $seller_user_name;
$data['verification_link'] = "$site_url/includes/verify_email?code=$seller_verification";

if(send_mail($data)){
	echo "Ok";
}

?>