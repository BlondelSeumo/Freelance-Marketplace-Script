<?php

/// Single Proposal Checkout Order Code Starts ///
if(isset($_SESSION['checkout_seller_id'])){
	$buyer_id = $_SESSION['checkout_seller_id'];
	$proposal_id = $_SESSION['proposal_id'];
	$order_price = $_SESSION['proposal_price'];
	$processing_fee = processing_fee($order_price);
	$proposal_qty = $_SESSION['proposal_qty'];
	$delivery_id = $_SESSION['proposal_delivery'];
	$proposal_revisions = $_SESSION['proposal_revisions'];
	$payment_method = $_SESSION['method'];

	$select_proposal = $db->select("proposals",array("proposal_id" => $proposal_id));
	$row_proposal = $select_proposal->fetch();
	$proposal_title = $row_proposal->proposal_title;
	$proposal_enable_referrals = $row_proposal->proposal_enable_referrals;
	$proposal_referral_money = $row_proposal->proposal_referral_money;
	$proposal_referral_code = $row_proposal->proposal_referral_code;
	$buyer_instruction = $row_proposal->buyer_instruction;
	$proposal_seller_id = $row_proposal->proposal_seller_id;

	$select_delivery_time = $db->select("delivery_times",array('delivery_id' => $delivery_id));
	$row_delivery_time = $select_delivery_time->fetch();
	$delivery_proposal_title = $row_delivery_time->delivery_proposal_title;

	$get_delivery = $db->select("instant_deliveries",['proposal_id'=>$proposal_id]);
	$row_delivery = $get_delivery->fetch();
	$enable_delivery = $row_delivery->enable;
	$delivery_message = $row_delivery->message;
	$delivery_watermark = $row_delivery->watermark;
	$delivery_watermark_file = $row_delivery->watermark_file;
	$delivery_file = $row_delivery->file;
	$isS3 = $row_delivery->isS3;

	$add_days = intval($delivery_proposal_title);
	date_default_timezone_set("UTC");
	$order_date = date("F d, Y");
	$date_time = date("M d, Y H:i:s");
	$order_time = date("M d, Y H:i:s", strtotime($date_time . " + $add_days days"));
	$order_number = mt_rand();

	if(!empty($buyer_instruction)){
		$order_status = "pending";
	}else{
		$order_status = "progress";
	}

	if($enable_delivery == 1){
		$order_status = "delivered";
      $date_time = date("M d, Y H:i:s");
      $complete_time = date("M d, Y H:i:s",strtotime($date_time." + $order_auto_complete days"));
	}

	$order_values = array("order_number" => $order_number,"order_duration" => $delivery_proposal_title,"order_time" => $order_time,"order_date" => $order_date,"order_revisions" => $proposal_revisions,"seller_id" => $proposal_seller_id,"buyer_id" => $buyer_id,"proposal_id" => $proposal_id,"order_price" => $order_price,"order_qty" => $proposal_qty,"order_active" => 'yes',"order_status" => $order_status);

	if($enable_delivery == 1){
		$order_values['complete_time'] = $complete_time;
	}

	if($videoPlugin == 1){
		if(isset($_SESSION['proposal_minutes'])){
			$order_values['order_minutes'] = $_SESSION['proposal_minutes'].":00";
		}
	}

	if($payment_method == "shopping_balance"){
		$adminProfit = 0;
		$order_values['order_fee'] = 0;
		$insert_order = $db->insert("orders",$order_values);
	}else{
		$adminProfit = $processing_fee;
		$order_values['order_fee'] = $processing_fee;
		$insert_order = $db->insert("orders",$order_values);
	}

	$insert_order_id = $db->lastInsertId();

	if(isset($_SESSION['proposal_extras'])){
		foreach($_SESSION['proposal_extras'] as $value){
			$get_extras = $db->select("proposals_extras",array("id" => $value));
			$row_extras = $get_extras->fetch();
			$name = $row_extras->name;
			$price = $row_extras->price;
			$insert_extra = $db->insert("order_extras",array("order_id" => $insert_order_id,"name" => $name,"price"=>$price));
		}
	}

	if($insert_order){
		
		// Insert Sale Here
		$sale = array("buyer_id" => $buyer_id,"work_id" => $insert_order_id,"payment_method" => $payment_method,"amount" => $order_price,"profit"=> $adminProfit,"processing_fee"=>$adminProfit,"action"=>"order","date"=>date("Y-m-d"));
		insertSale($sale);

		if($enable_delivery == 1){
			$last_update_date = date("h:m: F d, Y");
			$insert_delivered_message = $db->insert("order_conversations",array("order_id" => $insert_order_id,"sender_id" => $proposal_seller_id,"message" => $delivery_message,"watermark"=>$delivery_watermark,"watermark_file"=>$delivery_watermark_file,"file" => $delivery_file,"isS3"=>$isS3,"date" => $last_update_date,"status" => "delivered"));
		}

		if($proposal_enable_referrals == "yes"){
			if(isset($_SESSION['r_proposal_id'])){
				if($_SESSION['r_referrer_id'] == $login_seller_id){
					///// empty
				}else{
					if($proposal_id == $_SESSION['r_proposal_id'] & $proposal_referral_code == $_SESSION['r_referral_code']){
						$ip = $_SERVER['REMOTE_ADDR'];
						$r_proposal_id = $_SESSION['r_proposal_id'];
						$r_seller_id = $proposal_seller_id;
						$r_referrer_id = $_SESSION['r_referrer_id'];
						$r_comission = $proposal_referral_money;
						$r_o_comission = ($order_price*$r_comission)/100;
						$comission = round($r_o_comission,1);
						$r_date = date("F d, Y");
						$insert_referral = $db->insert("proposal_referrals",array("proposal_id"=>$r_proposal_id,"order_id"=>$insert_order_id,"seller_id"=>$r_seller_id,"referrer_id"=>$r_referrer_id,"buyer_id"=>$login_seller_id,"comission"=>$comission,"date"=>$r_date,"ip"=>$ip,"status"=>'pending'));
						unset($_SESSION['r_proposal_id']);
						unset($_SESSION['r_referral_code']);
						unset($_SESSION['r_referrer_id']);
					}
				}
			}
		}

		require_once("emails/orderEmail.php");

		$select_my_buyer = $db->select("my_buyers",array("seller_id"=>$proposal_seller_id,"buyer_id"=>$login_seller_id));
		$count_my_buyer = $select_my_buyer->rowCount();
		if($count_my_buyer == 1){
			$update_my_buyer = $db->query("update my_buyers set completed_orders=completed_orders+1,amount_spent=amount_spent+$order_price,last_order_date='$order_date' where seller_id='$proposal_seller_id' AND buyer_id='$login_seller_id'");
		}else{
			$insert_my_buyer = $db->insert("my_buyers",array("seller_id"=>$proposal_seller_id,"buyer_id"=>$login_seller_id,"completed_orders"=>1,"amount_spent"=>$order_price,"last_order_date"=>$order_date));
		}

		$select_my_seller = $db->select("my_sellers",array("buyer_id"=>$login_seller_id,"seller_id"=>$proposal_seller_id));
		$count_my_seller = $select_my_seller->rowCount();
		if($count_my_seller == 1){
			// $insert_seller = $db->update("my_sellers",array("completed_orders"=>'completed_orders+1',"amount_spent"=>'amount_spent+$order_price',"last_order_date"=>$order_date),array("buyer_id"=>$login_seller_id,"seller_id"=>$proposal_seller_id));
			$update_my_seller = $db->query("update my_sellers set completed_orders=completed_orders+1,amount_spent=amount_spent+$order_price,last_order_date='$order_date' where buyer_id='$login_seller_id' AND seller_id='$proposal_seller_id'");
		}else{
			$insert_seller = $db->insert("my_sellers",array("buyer_id"=>$login_seller_id,"seller_id"=>$proposal_seller_id,"completed_orders"=>1,"amount_spent"=>$order_price,"last_order_date"=>$order_date));
		}

		$total_amount = $order_price + $processing_fee;
		
		if($payment_method == "shopping_balance"){
			$insert_purchase = $db->insert("purchases",array("seller_id"=>$login_seller_id,"order_id"=>$insert_order_id,"reason"=>"order","amount"=>$order_price,"date"=>$order_date,"method"=>$payment_method));
		}else{
			$insert_purchase = $db->insert("purchases",array("seller_id"=>$login_seller_id,"order_id"=>$insert_order_id,"reason"=>"order","amount"=>$total_amount,"date"=>$order_date,"method"=>$payment_method));
		}

		$insert_notification = $db->insert("notifications",array("receiver_id"=>$proposal_seller_id,"sender_id"=>$login_seller_id,"order_id"=>$insert_order_id,"reason"=>"order","date"=>$order_date,"status"=>"unread"));

        /// sendPushMessage Starts
        $notification_id = $db->lastInsertId();
        sendPushMessage($notification_id);
        /// sendPushMessage Ends

		unset($_SESSION['checkout_seller_id']);
		unset($_SESSION['proposal_id']);
		unset($_SESSION['proposal_qty']);
		unset($_SESSION['proposal_price']);
		unset($_SESSION['proposal_revisions']);
		unset($_SESSION['proposal_minutes']);
		unset($_SESSION['method']);

		echo "
		<script>
		swal({
		type: 'success',
		text: 'Processing...... ',
		timer: 5000,
		onOpen: function(){
			swal.showLoading()
		}
		}).then(function(){
		  // Read more about handling dismissals
		  window.open('order_details?order_id=$insert_order_id','_self')
		});
		</script>";
			
	}
}
/// Single Proposal Checkout Order Code Ends ///