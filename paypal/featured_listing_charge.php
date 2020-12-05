<?php 

	$get_payment_settings = $db->select("payment_settings");
	$row_payment_settings = $get_payment_settings->fetch();
	$featured_fee = $row_payment_settings->featured_fee;
	$processing_fee = processing_fee($featured_fee);

	$select_proposals = $db->select("proposals",array("proposal_id" => $_SESSION['f_proposal_id']));
	$row_proposals = $select_proposals->fetch();
	$proposal_title = $row_proposals->proposal_title;

	$data = [];
	$data['type'] = "featured_listing";
	$data['content_id'] = $_SESSION['f_proposal_id'];
	$reference_no = mt_rand();
	$data['reference_no'] = $reference_no;
	$data['name'] = $proposal_title;
	$data['desc'] = "Featured Listing Payment";
	$data['qty'] = 1;
	$data['price'] = $featured_fee;
	$data['sub_total'] = $featured_fee;
	$data['total'] = $featured_fee + $processing_fee;
	
	$data['cancel_url'] = "$site_url/cancel_payment?reference_no=$reference_no";
	$data['redirect_url'] = "$site_url/paypal_order?reference_no=$reference_no";