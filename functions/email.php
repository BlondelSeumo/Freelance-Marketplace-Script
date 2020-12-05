<?php

require_once("mailer.php");

if(isset($_SESSION['seller_user_name'])){
	$login_seller_user_name = $_SESSION['seller_user_name'];
	$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
	$row_login_seller = $select_login_seller->fetch();
	$login_seller_id = $row_login_seller->seller_id;
}

function userSignupEmail($email){
	global $db;
	global $dir;
	global $site_name;
	global $site_email_address;
	global $site_logo;
	global $site_url;
	global $verification_code;
	global $u_name;

	$data = [];
	$data['template'] = "email_confirmation";
	$data['to'] = $email;
	$data['subject'] = "$site_name : Activate Your New Account!";
	$data['seller_user_name'] = $u_name;
	$data['verification_link'] = "$site_url/includes/verify_email?code=$verification_code";

	send_mail($data);

	$get_admins = $db->select("admins");
	while($row_admins = $get_admins->fetch()){
	
		$admin_id = $row_admins->admin_id;
		$admin_name = $row_admins->admin_name;
		$admin_email = $row_admins->admin_email;

		$data = [];
		$data['template'] = "new_user";
		$data['to'] = $admin_email;
		$data['subject'] = "$site_name : A New User Has Registered.";
		$data['user_name'] = $admin_name;
		$data['seller_user_name'] = $u_name;

		send_mail($data);

	}

}

function userConfirmEmail($email){
	global $db;
	global $dir;
	global $seller_name;
	global $site_name;
	global $site_email_address;
	global $site_logo;
	global $site_url;
	global $verification_code;
	
	$data = [];
	$data['template'] = "email_confirmation";
	$data['to'] = $email;
	$data['subject'] = "$site_name : Activate Your New Account!";
	$data['seller_user_name'] = $seller_name;
	$data['verification_link'] = "$site_url/includes/verify_email?code=$verification_code";

	if(send_mail($data)){
		return true;
	}else{
		return true;
	}

}

function send_proposal_email($seller_user_name,$proposal_title,$cat_title,$status){

	global $db;
	global $dir;
	global $site_name;
	global $site_email_address;
	global $site_logo;
	global $site_url;

	$get_admins = $db->select("admins");
	while($row_admins = $get_admins->fetch()){
	
		$admin_id = $row_admins->admin_id;
		$admin_name = $row_admins->admin_name;
		$admin_email = $row_admins->admin_email;

		$data = [];
		$data['template'] = "new_proposal";
		$data['to'] = $admin_email;
		$data['subject'] = "$site_name - $seller_user_name Has Just Created A New Proposal.";
		$data['user_name'] = $admin_name;
		$data['seller_user_name'] = $seller_user_name;
		$data['proposal_title'] = $proposal_title;
		$data['cat_title'] = $cat_title;
		$data['proposal_status'] = ucfirst($status);

		send_mail($data);

   }

}

function send_cancellation_request($order_id,$order_number,$sender_id,$proposal_id,$seller_id,$buyer_id,$date){
	global $db;
	global $dir;
	global $site_name;
	global $site_email_address;
	global $site_logo;
	global $site_url;

	$select_proposal = $db->select("proposals",array("proposal_id" => $proposal_id));
	$row_proposal = $select_proposal->fetch();
	$proposal_title = $row_proposal->proposal_title;

	$select_buyer = $db->select("sellers",array("seller_id"=>$buyer_id));
	$row_buyer = $select_buyer->fetch();
	$buyer_user_name = $row_buyer->seller_user_name;

	$select_seller = $db->select("sellers",array("seller_id"=>$seller_id));
	$row_seller = $select_seller->fetch();
	$seller_user_name = $row_seller->seller_user_name;

	$select_sender = $db->select("sellers",array("seller_id"=>$sender_id));
	$row_sender = $select_sender->fetch();
	$sender_user_name = $row_sender->seller_user_name;

	$get_admins = $db->select("admins");
	while($row_admins = $get_admins->fetch()){
		$admin_id = $row_admins->admin_id;
		$admin_name = $row_admins->admin_name;
		$admin_email = $row_admins->admin_email;

		$data = [];
		$data['template'] = "cancellation_request";
		$data['to'] = $admin_email;
		$data['subject'] = "$site_name - Cancellation Requested";
		$data['user_name'] = $admin_name;
		$data['order_number'] = $order_number;
		$data['proposal_title'] = $proposal_title;
		$data['sender_user_name'] = $sender_user_name;
		$data['seller_user_name'] = $seller_user_name;
		$data['buyer_user_name'] = $buyer_user_name;
		$data['date'] = $date;

		if(send_mail($data)){
			return true;
		}else{
			return false;
		}
		
	}
}

function send_admin_forgot_password(){
	global $db;
	global $dir;
	global $forgot_email;
	global $select_admin;
	global $site_name;
	global $site_email_address;
	global $site_logo;
	global $site_url;

	$rowAdmin = $select_admin->fetch();
	$admin_user_name = $rowAdmin->admin_user_name;
	$admin_pass = $rowAdmin->admin_pass;

	$data = [];
	$data['template'] = "admin_reset_pass";
	$data['to'] = $forgot_email;
	$data['subject'] = "$site_name - Admin Password Reset";
	$data['user_name'] = $admin_user_name;
	$data['admin_pass'] = $admin_pass;

	if(send_mail($data)){
		return true;
	}else{
		return false;
	}

}


function send_report_email($item_type,$author,$item_link,$date){
	global $login_seller_user_name;
	global $db;
	global $dir;
	global $site_name;
	global $site_email_address;
	global $site_logo;
	global $site_url;

	$get_general_settings = $db->select("general_settings");   
	$row_general_settings = $get_general_settings->fetch();
	$site_email_address = $row_general_settings->site_email_address;
	$UserName = $login_seller_user_name;

	if($item_type = "proposal"){
		$item_link = "$site_url/proposals/$author/$item_link";
	}elseif($item_type == "message") {
		$item_link = "$site_url/conversations/inbox?single_message_id=$item_link";
	}elseif($item_type == "order") {
		$item_link = "$site_url/order_details?order_id=$item_link";
	}

	$get_admins = $db->select("admins");
	while($row_admins = $get_admins->fetch()){
		$admin_id = $row_admins->admin_id;
		$admin_name = $row_admins->admin_name;
		$admin_email = $row_admins->admin_email;
		
		$data = [];
		$data['template'] = "item_report";
		$data['to'] = $admin_email;
		$data['subject'] = "$site_name: Item Reported";
		$data['user_name'] = $admin_name;
		$data['seller_user_name'] = $UserName;
		$data['item_type'] = $item_type;
		$data['author'] = $author;
		$data['date'] = $date;
		$data['item_link'] = $item_link;

		if(send_mail($data)){
			return true;
		}else{
			return false;
		}

	}

}