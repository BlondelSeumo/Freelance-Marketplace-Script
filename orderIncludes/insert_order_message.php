<?php

@session_start();
require_once("../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('../login','_self')</script>";
}

if($notifierPlugin == 1){ 
	require_once("$dir/plugins/notifierPlugin/functions.php");
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$order_id = $input->post('order_id');

$get_orders = $db->select("orders",array("order_id" => $order_id));
$row_orders = $get_orders->fetch();
$seller_id = $row_orders->seller_id;
$buyer_id = $row_orders->buyer_id;
$order_number = $row_orders->order_number;
$order_status = $row_orders->order_status;
$order_duration = intval($row_orders->order_duration);

date_default_timezone_set("UTC");
$date_time = date("M d, Y H:i:s");
$order_time = date("M d, Y H:i:s", strtotime($date_time . " + $order_duration days"));
$message = $input->post('message');
@$file = $_FILES["file"]["name"];
@$file_tmp = $_FILES["file"]["tmp_name"];
date_default_timezone_set($site_timezone);
$last_update_date = date("h:i: F d, Y");

$count_order_conversations = $db->count("order_conversations",array("order_id" => $order_id,"sender_id" => $buyer_id));

if($buyer_id == $login_seller_id AND $order_status == "pending"){
	
	if($count_order_conversations == 0){
	    
		$update_order = $db->update("orders",array("order_status" => "progress","order_time" => $order_time),array("order_id" => $order_id));

		echo "<script>window.open('order_details?order_id=$order_id','_self')</script>";

	}

}
	
$allowed = array('jpeg','jpg','gif','png','tif','avi','mpeg','mpg','mov','rm','3gp','flv','mp4','zip','rar','mp3','wav','docx','csv','xls','xlsx','pptx','pdf','txt','psd','xd','txt');

$file_extension = pathinfo($file, PATHINFO_EXTENSION);

if(!in_array($file_extension,$allowed) & !empty($file)){
	
	echo "<script>alert('{$lang['alert']['extension_not_supported']}')</script>";
	
}else{

if(!empty($file)){

	$file = pathinfo($file, PATHINFO_FILENAME);
	$file = $file."_".time().".$file_extension";
	uploadToS3("order_files/$file",$file_tmp);

}else{ 
	$file = ""; 
}

if($seller_id == $login_seller_id){ 
	$receiver_id = $buyer_id; 
}else{ 
	$receiver_id = $seller_id; 
}

$select_receiver = $db->select("sellers",array("seller_id" => $seller_id));
$row_receiver = $select_receiver->fetch();
$receiver_phone = @$row_receiver->seller_phone;

$insert_order_conversation = $db->insert("order_conversations",array("order_id" => $order_id,"sender_id" => $login_seller_id,"message" => $message,"file" => $file,"date" => $last_update_date,"isS3"=>$enable_s3,"status" => "message"));

if($insert_order_conversation){

	if($notifierPlugin == 1){

		$smsText = str_replace('{seller_user_name}',$login_seller_user_name,$lang['notifier_plugin']['order_message']);
		$smsText = str_replace('{order_number}',$order_number,$smsText);
		sendSmsTwilio("",$smsText,$receiver_phone);

	}

	$insert_notification = $db->insert("notifications",array("receiver_id" => $receiver_id,"sender_id" => $login_seller_id,"order_id" => $order_id,"reason" => "order_message","date" => $last_update_date,"status" => "unread"));

    /// sendPushMessage Starts
    $notification_id = $db->lastInsertId();
    sendPushMessage($notification_id);
    /// sendPushMessage Ends

	// New Spam Words Code Starts
	$n_date = date("F d, Y");
	$get_words = $db->select("spam_words");
	while($row_words = $get_words->fetch()){
		$name = $row_words->word;
		if(preg_match("/\b($name)\b/i", $message)){
			if($db->insert("admin_notifications",array("seller_id" => $login_seller_id,"content_id" => $order_id,"reason" => "order_spam","date" => $n_date,"status" => "unread"))) {
				break;
			}
		}
	}
	// New Spam Words Code Ends
	
}

}