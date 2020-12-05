<?php

$order_id = $input->get('order_id');
$get_orders = $db->query("select * from orders where (seller_id=$login_seller_id or buyer_id=$login_seller_id) AND order_id=:o_id",array("o_id"=>$order_id));
$row_orders = $get_orders->fetch();
$order_id = $row_orders->order_id;
$order_number = $row_orders->order_number;
if($videoPlugin == 1){
	$order_minutes = $row_orders->order_minutes;
}
$proposal_id = $row_orders->proposal_id;
$seller_id = $row_orders->seller_id;
$buyer_id = $row_orders->buyer_id;
$order_price = $row_orders->order_price;
$order_qty = $row_orders->order_qty;
$order_date = $row_orders->order_date;
$order_revisions = $row_orders->order_revisions;
$order_revisions_used = $row_orders->order_revisions_used;
$order_duration = $row_orders->order_duration;
$order_time = $row_orders->order_time;
$order_fee = $row_orders->order_fee;
$order_desc = $row_orders->order_description;
$order_status = $row_orders->order_status;
$total = $order_price+$order_fee;


//// Get Order Tip  ////
$get_tip = $db->select("order_tips",array("order_id" => $order_id));
$row_tip = $get_tip->fetch();
$count_tip = $get_tip->rowCount();
if($count_tip > 0){
	$tip_amount = $row_tip->amount;
	$tip_message = $row_tip->message;
	$tip_date = $row_tip->date;
}

//// Select Order Proposal Details ///
$select_proposal = $db->select("proposals",array("proposal_id" => $proposal_id));
$row_proposal = $select_proposal->fetch();
$proposal_title = $row_proposal->proposal_title;
$proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposal->proposal_img1);
$proposal_url = $row_proposal->proposal_url;
$buyer_instruction = $row_proposal->buyer_instruction;
$get_buyer_instructions = $db->query("select buyer_instruction from proposals where proposal_id='$proposal_id'");
$count_buyer_instruction = $get_buyer_instructions->rowCount();
if($count_buyer_instruction == 0){
	$update_order = $db->update("orders",array("order_status"=>"progress"),array("order_id"=>$order_id));
}

$proposal_cat_id = $row_proposal->proposal_cat_id;

/// Get Category Details
$get_cat = $db->select("categories",['cat_id'=>$proposal_cat_id]);
$row_cat = $get_cat->fetch();
$enable_watermark = $row_cat->enable_watermark;

if($videoPlugin == 1){
	
	$c_video = $row_cat->video;

	$reminder_emails = $row_cat->reminder_emails;
	$missed_session_emails = $row_cat->missed_session_emails;
	$warning_message = $row_cat->warning_message;

}

// select login user details
$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_level = $row_login_seller->seller_level;

/// Select Order Seller Details ///
$select_seller = $db->select("sellers",array("seller_id" => $seller_id));
$row_seller = $select_seller->fetch();
$seller_user_name = $row_seller->seller_user_name;
$seller_email = $row_seller->seller_email;
$seller_phone = $row_seller->seller_phone;
$order_seller_rating = $row_seller->seller_rating;
$order_seller_level = $row_seller->seller_level;
if($order_seller_rating > "100"){
	$update_seller_rating = $db->update("sellers",array("seller_rating" => 100),array("seller_id" => $seller_id));
}

// select seller_payment_settings 
$seller_payment_settings = $db->select("seller_payment_settings",["level_id"=>$order_seller_level])->fetch();
$seller_comission_percentage = $seller_payment_settings->commission_percentage;
// make function getPercentOfNumber()
if(!function_exists("getPercentOfNumber")){
	function getPercentOfNumber($amount, $percentage){
		$calculate_percentage = ($percentage / 100 ) * $amount ;
		return $amount-$calculate_percentage;
	}
}
// getting seller price
$seller_price = getPercentOfNumber($order_price, $seller_comission_percentage);

//// Select Order Buyer Details ///
$select_buyer = $db->select("sellers",array("seller_id" => $buyer_id));
$row_buyer = $select_buyer->fetch();
$buyer_user_name = $row_buyer->seller_user_name;
$buyer_email = $row_buyer->seller_email;
$buyer_phone = $row_buyer->seller_phone;
$n_date = date("F d, Y");