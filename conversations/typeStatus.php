<?php
require_once("../includes/db.php");
if($_POST['status'] == "typing"){
	$seller_id = $input->post('seller_id');
	$message_group_id = $input->post('message_group_id');
	$time = date("Y-m-d H:i:s");

	$status = $input->post('status');
	$select = $db->select("seller_type_status",array("seller_id"=>$seller_id,"message_group_id"=>$message_group_id));
	$count = $select->rowCount();
	if($count == 0){
		$insert = $db->insert("seller_type_status",array("seller_id"=>$seller_id,"message_group_id"=>$message_group_id,"time"=>$time,"status"=>$status));
	}else{
		$update = $db->update("seller_type_status",array("time"=>$time,"status"=>$status),array("seller_id"=>$seller_id,"message_group_id"=>$message_group_id));
	}
}elseif($_POST['status'] == "untyping"){
	$seller_id = $input->post('seller_id');
	$message_group_id = $input->post('message_group_id');
	$status = $input->post('status');
	$update = $db->update("seller_type_status",array("status"=>$status),array("seller_id"=>$seller_id,"message_group_id"=>$message_group_id));
}