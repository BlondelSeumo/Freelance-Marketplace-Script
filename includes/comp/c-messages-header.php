<?php

if(isset($_POST['seller_id'])){
	require_once("../db.php");
	$seller_id = $input->post('seller_id');
}

$count_unread_inbox_messages = $db->query("select * from inbox_messages where message_receiver=:r_id AND message_status='unread'",array("r_id"=>$seller_id))->rowCount();
if($count_unread_inbox_messages > 0 ){ 
	echo $count_unread_inbox_messages;
}