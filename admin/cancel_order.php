<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{


if(isset($_GET['cancel_order'])){
	
	$order_id = $input->get('cancel_order');

	$update_order = $db->update("orders",array("order_active"=>'no',"order_status"=>'cancelled'),array("order_id"=>$order_id));

	if($update_order){
		
		$get_order = $db->select("orders",array("order_id" => $order_id));
		$row_order = $get_order->fetch();
		$seller_id = $row_order->seller_id;
		$buyer_id = $row_order->buyer_id;
		$order_price = $row_order->order_price;
		$order_number = $row_order->order_number;
			
		$message = "Order Cancelled By Customer Support";

		date_default_timezone_set($site_timezone);

		$date = date("h:i: M d, Y");
		$purchase_date = date("F d, Y");
		$n_date = date("F d, Y");

		$update_messages = $db->update("order_conversations",array("status"=>"message"),array("order_id"=>$order_id,"status"=>"revision"));

		$update_messages = $db->update("order_conversations",array("status"=>"message"),array("order_id"=>$order_id,"status"=>"cancellation_request"));

		$insert_purchase = $db->insert("purchases",array("seller_id" => $buyer_id,"order_id" => $order_id,"amount" => $order_price,"date" => $purchase_date,"method" => "order_cancellation"));

		$insert_order_conversation = $db->insert("order_conversations",array("order_id" => $order_id,"sender_id" => $buyer_id,"message" => $message,"date" => $date,"status" => "cancelled_by_customer_support"));

		$insert_notification = $db->insert("notifications",array("receiver_id" => $seller_id,"sender_id" => $buyer_id,"order_id" => $order_id,"reason" => "cancelled_by_customer_support","date" => $n_date,"status" => "unread"));

		$update_balance = $db->query("update seller_accounts set used_purchases=used_purchases-:minus,current_balance=current_balance+:plus where seller_id='$buyer_id'",array("minus"=>$order_price,"plus"=>$order_price));

		if($update_balance){

			$insert_log = $db->insert_log($admin_id,"order",$order_id,"cancelled");
			echo "<script>alert('Order #$order_number Has been Cancelled, Order Amount Successfully Return To Buyer Shopping Balance.');</script>";
			echo "<script>window.open('index?view_orders','_self');</script>";
			
		}

	}
	
}

?>

<?php } ?>