<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
  
echo "<script>window.open('login.php','_self');</script>";
 
exit();

}

if(isset($_GET['approve_payout'])){

$id = $input->get('approve_payout');
$update = $db->update("payouts",["status"=>'completed'],["id"=>$id]);

if($update){

	$get = $db->select("payouts",array('id'=>$id));
	$row = $get->fetch();
	$seller_id = $row->seller_id;
	$method = $row->method;
	$amount = $row->amount;
	
	$get_seller = $db->select("sellers",array("seller_id" => $seller_id));
	$row_seller = $get_seller->fetch();
	$seller_phone = $row_seller->seller_phone;

	$date = date("F d, Y");

	$insert_notification = $db->insert("notifications",array("receiver_id" => $seller_id,"sender_id" => "admin_$admin_id","order_id" => $id,"reason" => "withdrawal_approved","date" => $date,"status" => "unread"));

	/// sendPushMessage Starts
	$notification_id = $db->lastInsertId();
	sendPushMessage($notification_id);
	/// sendPushMessage Ends

	if($notifierPlugin == 1){
		$smsText = $lang['notifier_plugin']['payout_approved'];
		sendSmsTwilio("",$smsText,$seller_phone);
	}

	echo "
		<script>
			alert('One Payout Request Has Been Approved.');
			window.open('index?payouts&status=completed','_self');
		</script>
	";

}

?>

<?php } ?>