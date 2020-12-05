<?php
require_once("../includes/db.php");
$seller_id = $input->post('seller_id');
$message_group_id = $input->post('message_group_id');
$select = $db->select("seller_type_status",array("seller_id"=>$seller_id,"message_group_id"=>$message_group_id));
$count = $select->rowCount();
if($count != 0){
	$row = $select->fetch();
	$status = $row->status;
	$time = $row->time;

	$current_timestamp = strtotime(date("Y-m-d H:i:s").'- 5 second');
	$current_timestamp = date('Y-m-d H:i:s', $current_timestamp);

	$select_seller = $db->select("sellers",array("seller_id"=>$seller_id));
	$row_seller = $select_seller->fetch();
	$seller_user_name = $row_seller->seller_user_name;
	if($status == "typing" AND $time > $current_timestamp){
		echo "typing";
	}else{
		echo "untyping"; 
	}
}