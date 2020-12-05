<?php
// Processing Fee
function get_percentage_amount($amount, $percentage){
	$calculate_percentage = ($percentage / 100 ) * $amount;
	return $calculate_percentage;
}

function processing_fee($amount){
	global $db;
	$get_payment_settings = $db->select("payment_settings");
	$row_payment_settings = $get_payment_settings->fetch();
	$processing_feeType = $row_payment_settings->processing_feeType;
	$processing_fee = $row_payment_settings->processing_fee;
	if($processing_feeType=="fixed") {
		return $processing_fee;
	}elseif($processing_feeType=="percentage"){
		return get_percentage_amount($amount,$processing_fee);
	}
}