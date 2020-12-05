<?php
@session_start();
require_once("db.php");
$seller_id = $input->post('seller_id');
$enable_sound = $input->post('enable_sound');
$data = array();
$i = 0;
$select_notofications = $db->query("select * from notifications where receiver_id=:r_id AND bell='active' AND status='unread' order by 1 DESC LIMIT 0,1",array("r_id"=>$seller_id));
while($row_notifications = $select_notofications->fetch()){
	$i++;
	$notification_id = $row_notifications->notification_id;
	$data[$i]['notification_id'] = $row_notifications->notification_id;
	$data[$i]['sender_id'] = $row_notifications->sender_id;
	$data[$i]['order_id'] = $row_notifications->order_id;
	$reason = $row_notifications->reason;
	$data[$i]['reason'] = $row_notifications->reason;
	$data[$i]['date'] = $row_notifications->date;
	$data[$i]['status'] = $row_notifications->status;
	// Select Sender Details
	$select_sender = $db->select("sellers",array("seller_id" => $data[$i]['sender_id']));
	$row_sender = $select_sender->fetch();
	$sender_user_name = @$row_sender->seller_user_name;
	if(empty($row_sender->seller_image)){ 
		$sender_image = "$site_url/user_images/empty-image.png"; 
	}else{
		$sender_image = getImageUrl2("sellers","seller_image",$row_sender->seller_image);
	}

	if(strpos($data[$i]['sender_id'],'admin') !== false){
		$admin_id = trim($data[$i]['sender_id'], "admin_");
		$get_admin = $db->select("admins",array("admin_id" => $admin_id));
		@$admin_image = $get_admin->fetch()->admin_image;
		if(empty($admin_image)){
			$admin_image = "$site_url/admin/admin_images/empty-image.png";
		}else{
			$sender_image = getImageUrl("admins",$admin_image);
		}
		$sender_user_name = "Admin";
	}
	$data[$i]['sender_user_name'] = $sender_user_name;
	$data[$i]['sender_image'] = $sender_image;
	$data[$i]['message'] = include("comp/notification_reasons.php");
	
	$count = $db->query("select * from notifications where receiver_id=:r_id AND bell='active' AND status='unread' AND NOT notification_id='$notification_id'",array("r_id"=>$seller_id))->rowCount();
	if($count > 0){
	$data[$i]['more'] = "
    <div class='clearfix'></div>
      <a href='$site_url/notifications'><span class='badge badge-success float-right'>$count More</span></a>
    <div class='clearfix'></div>
	";
	}else{
		$data[$i]['more'] = "";
	}

}

$update = $db->update("notifications",["bell" => 'over'],["receiver_id" => $seller_id,"status" => 'unread',"bell" => 'active']);

echo json_encode($data); 