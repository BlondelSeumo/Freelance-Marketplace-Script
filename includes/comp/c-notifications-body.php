<?php
@session_start();
if(isset($_POST['seller_id'])){
	@require_once("../db.php");
	$seller_id = $input->post('seller_id');
}

$count_all_notifications =  $db->count("notifications",array("receiver_id" => $seller_id));
$data = array();
$data['lang']['notifications'] = $lang['popup']['notifications'];
$data['lang']['view_notifications'] = $lang['popup']['view_notifications'];
$data['lang']['no_notifications'] = $lang['popup']['no_notifications'];
$data['count_all_notifications'] = $count_all_notifications;
$data['see_all'] = $lang['see_all'];
$data['notifications'] = array();

$i = 0;
$select_notofications = $db->query("select * from notifications where receiver_id=:r_id order by 1 DESC LIMIT 0,4",array("r_id"=>$seller_id));
while($row_notifications = $select_notofications->fetch()){
	$i++;
	$data['notifications'][$i]['id'] = $row_notifications->notification_id;
	$sender_id = $row_notifications->sender_id;
	$data['notifications'][$i]['order_id'] = $row_notifications->order_id;
	$reason = $row_notifications->reason;
	$data['notifications'][$i]['date'] = $row_notifications->date;
	$status = $row_notifications->status;

	// Select Sender Details
	$select_sender = $db->select("sellers",array("seller_id" => $sender_id));
	$row_sender = $select_sender->fetch();
	$data['notifications'][$i]['sender_user_name'] = ucfirst(@$row_sender->seller_user_name);
	if(empty($row_sender->seller_image)){ 
		$data['notifications'][$i]['sender_image'] = "$site_url/user_images/empty-image.png"; 
	}else{
		$data['notifications'][$i]['sender_image'] = getImageUrl2("sellers","seller_image",$row_sender->seller_image);
	}

	if(strpos($sender_id,'admin') !== false){
		$admin_id = trim($sender_id, "admin_");
		$data['notifications'][$i]['sender_user_name'] = "Admin";
		$get_admin = $db->select("admins",array("admin_id" => $admin_id));
		@$admin_image = $get_admin->fetch()->admin_image;
		if(empty($admin_image)){
			$data['notifications'][$i]['sender_image'] = "$site_url/admin/admin_images/empty-image.png";
		}else{
		   $data['notifications'][$i]['sender_image'] = getImageUrl("admins",$admin_image);
		}
	}

	$data['notifications'][$i]['message'] = include("notification_reasons.php");
	if($status == 'unread'){ 
		$data['notifications'][$i]['class'] = 'header-message-div-unread'; 
	}else{ 
		$data['notifications'][$i]['class'] = 'header-message-div'; 
	}
}
echo json_encode($data);