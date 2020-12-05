<?php 

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

?>

<?php 

if(isset($_GET['approve_request'])){
	
$request_id = $input->get('approve_request');

$update_request = $db->update("buyer_requests",array("request_status" => 'active'),array("request_id" => $request_id));
	
if($update_request){

$get_requests = $db->select("buyer_requests",array("request_id" => $request_id));
$row_requests = $get_requests->fetch();
$seller_id = $row_requests->seller_id;

$get_seller = $db->select("sellers",array("seller_id" => $seller_id));
$row_seller = $get_seller->fetch();
$seller_phone = $row_seller->seller_phone;

$n_date = date("F d, Y");

if($notifierPlugin == 1){
	$smsText = $lang['notifier_plugin']['request_approved'];
    sendSmsTwilio("",$smsText,$seller_phone);
}

$insert_notification = $db->insert("notifications",array("receiver_id" => $seller_id,"sender_id" => "admin_$admin_id","order_id" => $request_id,"reason" => "approved_request","date" => $n_date,"status" => "unread"));

$insert_log = $db->insert_log($admin_id,"request",$request_id,"approved");

echo "<script>alert('Request approved successfully.');</script>";
	
echo "<script>window.open('index?buyer_requests','_self');</script>";
	
}
	
	
}

?>

<?php } ?>