<?php
	
	$order_id = $_SESSION['tipOrderId'];
	$amount = $_SESSION['tipAmount'];

	// proposal
	$processing_fee = processing_fee($amount);

	$data = [];
	$data['type'] = "orderTip";
	$data['content_id'] = $_SESSION['tipOrderId'];
	$reference_no = mt_rand();
	$data['reference_no'] = $reference_no;
	$data['name'] = "Order Tip Payment";
	$data['qty'] = 1;
	$data['price'] = $amount;
	$data['sub_total'] = $amount;
	$data['total'] = $amount+$processing_fee;

	$data['cancel_url'] = "$site_url/cancel_payment?reference_no=$reference_no";
	$data['redirect_url'] = "$site_url/orderIncludes/charge/order/paypal?reference_no=$reference_no";	