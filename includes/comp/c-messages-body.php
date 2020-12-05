<?php
@session_start();
if(isset($_POST['seller_id'])){
	require_once("../db.php");
	$seller_id = $input->post('seller_id');
}

$data = array();
$count_all_inbox_sellers = $db->query("select * from inbox_sellers where (receiver_id=:r_id or sender_id=:s_id) AND NOT message_status='empty'",array("r_id"=>$seller_id,"s_id"=>$seller_id))->rowCount();
$data['lang']['inbox'] = $lang['popup']['inbox'];
$data['lang']['view_inbox'] = $lang['popup']['view_inbox'];
$data['lang']['no_inbox'] = $lang['popup']['no_inbox'];
$data['count_all_inbox_sellers'] = $count_all_inbox_sellers;
$data['see_all'] = $lang['see_all'];
$data['messages'] = array();
$i = 0;

$inboxQuery = "select * from inbox_sellers where (receiver_id=:r_id or sender_id=:s_id) AND NOT message_status='empty' order by time DESC LIMIT 0,4";
$select_inbox_sellers = $db->query($inboxQuery,array("r_id"=>$seller_id,"s_id"=>$seller_id));
while($row_inbox_sellers = $select_inbox_sellers->fetch()){
	$i++;
	$sender_id = $row_inbox_sellers->sender_id;
	$receiver_id = $row_inbox_sellers->receiver_id;
	$message_id = $row_inbox_sellers->message_id;

	/// Select Sender Information
	if($seller_id == $sender_id){ $sender_id = $receiver_id; }else{ $sender_id = $sender_id; }
	$select_hide_seller_messages = $db->select("hide_seller_messages",array("hider_id"=>$seller_id,"hide_seller_id"=>$sender_id));
	$count_hide_seller_messages = $select_hide_seller_messages->rowCount();
	if($count_hide_seller_messages == 0){

		$data['messages'][$i]['message_group_id'] = $row_inbox_sellers->message_group_id;
		$select_sender = $db->select("sellers",array("seller_id" => $sender_id));
		$row_sender = $select_sender->fetch();
		$data['messages'][$i]['sender_user_name'] = $row_sender->seller_user_name;
		$data['messages'][$i]['sender_image'] = getImageUrl2("sellers","seller_image",$row_sender->seller_image);
		
		if(empty($data['messages'][$i]['sender_image'])){ 
			$data['messages'][$i]['sender_image'] = "empty-image.png"; 
		}
			
		$select_inbox_message = $db->select("inbox_messages",array("message_id" => $message_id));
		$row_inbox_message = $select_inbox_message->fetch();
		$message_desc = strip_tags($row_inbox_message->message_desc);
		$data['messages'][$i]['date'] = $row_inbox_message->message_date;
		$data['messages'][$i]['status'] = $row_inbox_message->message_status;

		if($message_desc == ""){
		$message_desc = "Sent you an offer";
		}
		$data['messages'][$i]['desc'] = $message_desc;

		if($data['messages'][$i]['status'] == 'unread'){ 
			if($seller_id == $receiver_id){
			 $data['messages'][$i]['class'] = "header-message-div-unread";
			}else{
			 $data['messages'][$i]['class'] = "header-message-div";
			}
		}else{
			$data['messages'][$i]['class'] = "header-message-div"; 
		} 

	}else{
		$data['count_all_inbox_sellers'] = intval($data['count_all_inbox_sellers'])-1; 
	}

}
echo json_encode($data);