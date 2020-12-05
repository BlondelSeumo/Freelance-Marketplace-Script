<?php

@session_start();
require_once("db.php");
if ($input->post('seller_id')) {
	$seller_id = $input->post('seller_id');
} else {
	$seller_id = 1;
}
$data = array();
$i = 0;

$select_inbox_sellers = $db->query("select * from inbox_sellers where receiver_id=:r_id AND popup='1' order by 1 DESC LIMIT 0,4",array("r_id"=>$seller_id));
while($row_inbox_sellers = $select_inbox_sellers->fetch()){
	$i++;
	$message_group_id = $row_inbox_sellers->message_group_id;
	$update_inbox_sellers = $db->query("update inbox_sellers set popup='0' where receiver_id=:r_id AND message_status='unread' AND message_group_id='$message_group_id'",array("r_id"=>$seller_id));
	
	/// $update_inbox_messages = $db->query("update inbox_messages set message_status='read' where message_receiver=:r_id AND message_status='unread' AND message_group_id='$message_group_id'",array("r_id"=>$seller_id));

	$data[$i]['message_group_id'] = $message_group_id;
	$sender_id = $row_inbox_sellers->sender_id;
	$receiver_id = $row_inbox_sellers->receiver_id;
	$message_id = $row_inbox_sellers->message_id;

	/// Select Sender Information
	if($seller_id == $sender_id){
		$sender_id = $receiver_id;
	}else{
		$sender_id = $sender_id;
	}

	$select_sender = $db->select("sellers",array("seller_id" => $sender_id));
	$row_sender = $select_sender->fetch();
	$data[$i]['sender_user_name'] = $row_sender->seller_user_name;
	$data[$i]['sender_image'] = getImageUrl2("sellers","seller_image",$row_sender->seller_image);

	if(empty($data[$i]['sender_image'])){ $data[$i]['sender_image'] = "empty-image.png"; }
	
	$select_inbox_message = $db->select("inbox_messages",array("message_id" => $message_id));
	$row_inbox_message = $select_inbox_message->fetch();
	$data[$i]['desc'] = substr(strip_tags($row_inbox_message->message_desc),0,250);
	$data[$i]['date'] = $row_inbox_message->message_date;
	$data[$i]['offer_id'] = $row_inbox_message->message_offer_id;
	$data[$i]['message_status'] = $row_inbox_message->message_status;
	
	if(empty($data[$i]['desc']) and !empty($data[$i]['offer_id'])){
		$data[$i]['sender_user_name'] = "Offer waiting!";
		$data[$i]['desc'] = "You have a new offer in your inbox.";
	}

}

echo json_encode($data);