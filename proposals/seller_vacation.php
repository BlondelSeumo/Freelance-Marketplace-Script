<?php

session_start();

require_once("../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){
	
echo "<script>window.open('../login','_self')</script>";
	
}

if($_POST['turn_on'] == "on"){
	
$seller_id = $input->post('seller_id');

$seller_vacation_reason = $input->post('seller_vacation_reason');

$seller_vacation_message = $input->post('seller_vacation_message');

$turn_on = $input->post('turn_on');
	
$update_seller = $db->update("sellers",array("seller_vacation" => $turn_on,"seller_vacation_reason" => $seller_vacation_reason,"seller_vacation_message" => $seller_vacation_message),array("seller_id" => $seller_id));

}


if($_POST['turn_off'] == "off"){
	
$seller_id = $input->post('seller_id');

$turn_off = $input->post('turn_off');
	
$update_seller = $db->update("sellers",array("seller_vacation" => $turn_off,"seller_vacation_reason" => '',"seller_vacation_message" => ''),array("seller_id" => $seller_id));

}

