<?php

session_start();

require_once("../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){
	
echo "<script>window.open('../login','_self')</script>";
	
}

$login_seller_user_name = $_SESSION['seller_user_name'];

$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));

$row_login_seller = $select_login_seller->fetch();

$login_seller_id = $row_login_seller->seller_id;


if(isset($_GET['seller_id'])){
	
$get_receiver_id = $input->get('seller_id');
	
if(isset($_GET['offer_id'])){
	
$offer_id = $input->get('offer_id');
	
}else{
	
$offer_id = "0";
	
}
	
if($login_seller_id == $get_receiver_id){
	
echo "<script>window.open('../index.php','_self')</script>";
	
}

$get_inbox_sellers = $db->query("select * from inbox_sellers where sender_id='$login_seller_id' and receiver_id=:r_id or sender_id=:s_id and receiver_id='$login_seller_id'",array("r_id"=>$get_receiver_id,"s_id"=>$get_receiver_id));
	
$count_inbox_sellers = $get_inbox_sellers->rowCount();
	
if($count_inbox_sellers == 0){
	
$message_status = "empty";

$new_message_group_id = mt_rand();

$insert_inbox_seller = $db->insert("inbox_sellers",array("message_group_id" => $new_message_group_id,"offer_id" => $offer_id,"sender_id" => $login_seller_id,"receiver_id" => $get_receiver_id,"message_status" => $message_status));

if($insert_inbox_seller){

echo "<script>window.open('inbox?single_message_id=$new_message_group_id','_self')</script>";

}


}else{

$old_message_group_id = $get_inbox_sellers->fetch()->message_group_id;	

echo "<script>window.open('inbox?single_message_id=$old_message_group_id','_self')</script>";
	
}

	
}else{
	
echo "<script>window.open('../index','_self')</script>";
	
}

?>