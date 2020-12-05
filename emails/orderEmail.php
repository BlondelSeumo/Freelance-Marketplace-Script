<?php
if(!isset($delivery_proposal_title)){
	$delivery_proposal_title = $delivery_time;
}

$select_proposal_seller = $db->select("sellers",array("seller_id"=>$proposal_seller_id));
$row_proposal_seller = $select_proposal_seller->fetch();
$proposal_seller_user_name = $row_proposal_seller->seller_user_name;
$proposal_seller_email = $row_proposal_seller->seller_email;
$proposal_seller_phone = $row_proposal_seller->seller_phone;

$data = [];
$data['template'] = "order_email";
$data['to'] = $proposal_seller_email;
$data['subject'] = "Congrats! You just received an order from $login_seller_user_name";
$data['user_name'] = $proposal_seller_user_name;
$data['buyer_user_name'] = $login_seller_user_name;
$data['proposal_title'] = $proposal_title;
$data['qty'] = $proposal_qty;
$data['duration'] = $delivery_proposal_title;
$data['amount'] = $order_price;
$data['order_id'] = $insert_order_id;
send_mail($data);

$data = [];
$data['template'] = "order_receipt";
$data['to'] = $login_seller_email;
$data['subject'] = "$site_name: Thank you for ordering";
$data['user_name'] = $login_seller_user_name;
$data['seller_user_name'] = $proposal_seller_user_name;
$data['proposal_title'] = $proposal_title;
$data['qty'] = $proposal_qty;
$data['duration'] = $delivery_proposal_title;
$data['amount'] = $order_price;
$data['order_id'] = $insert_order_id;
send_mail($data);

if($notifierPlugin == 1){

	$smsText = str_replace('{seller_user_name}',$login_seller_user_name,$lang['notifier_plugin']['new_order']);
	sendSmsTwilio("",$smsText,$proposal_seller_phone);

}