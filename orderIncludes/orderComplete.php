<?php

// select login user details
$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_level = $row_login_seller->seller_level;
// select payment_settings
$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$days_before_withdraw = $row_payment_settings->days_before_withdraw;

/// Select Order Seller Details ///
$select_seller = $db->select("sellers",array("seller_id" => $seller_id));
$row_seller = $select_seller->fetch();
$order_seller_level = $row_seller->seller_level;

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
	
// update seller
$recent_delivery_date = date("F d, Y");
$update_seller = $db->update("sellers",array("seller_recent_delivery"=>$recent_delivery_date),array("seller_id"=>$seller_id));

// update order
$update_order = $db->update("orders",array("order_status"=>"completed","order_active"=>"no"),array("order_id"=>$order_id));

// update messages
$update_messages = $db->update("order_conversations",array("status"=>"message"),array("order_id"=>$order_id,"status"=>"delivered"));

// Insert notification
$date = date("F d, Y");
$insert_notification = $db->insert("notifications",array("receiver_id"=>$seller_id,"sender_id"=>$buyer_id,"order_id"=>$order_id,"reason"=>"order_completed","date"=>$date,"status"=>"unread"));

/// sendPushMessage Starts
$notification_id = $db->lastInsertId();
sendPushMessage($notification_id);
/// sendPushMessage Ends

// update seller account
$update_seller_account = $db->query("update seller_accounts set pending_clearance=pending_clearance+:p_plus,month_earnings=month_earnings+:m_plus where seller_id='$seller_id'",array("p_plus"=>$seller_price,"m_plus"=>$seller_price));

// insert seller revenue
$revenue_date = date("F d, Y", strtotime(" + $days_before_withdraw days"));
$end_date = date("F d, Y H:i:s", strtotime(" + $days_before_withdraw days"));

$insert_revenue = $db->insert("revenues",array("seller_id" => $seller_id,"order_id" => $order_id,"reason" => "order","amount" => $seller_price,"date" => $revenue_date,"end_date" => $end_date,"status" => "pending"));

// select payment method using order_id from purchases table
$select = $db->select("purchases",array("order_id" => $order_id));
$row = $select->fetch();
$payment_method = $row->method;
$processing_fee = 0;

// get admin profit - Insert sale
$adminProfit = ($seller_comission_percentage / 100) * $order_price;
$sale = array("buyer_id" => $buyer_id,"work_id" => $order_id,"payment_method" => $payment_method,"amount" => $order_price,"profit"=> $adminProfit,"processing_fee"=>$processing_fee,"action"=>"order_completed","date"=>date("Y-m-d"));
insertSale($sale);

// redirect the buyer to order_details
echo "<script>window.open('order_details?order_id=$order_id','_self')</script>";