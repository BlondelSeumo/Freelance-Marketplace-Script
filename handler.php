<?php
session_start();
require_once("includes/db.php");

$slug = urlencode($input->get('slug'));

$select_seller = $db->query("select * from sellers where seller_user_name=:u_name AND NOT seller_status='deactivated' AND NOT seller_status='block-ban'",array("u_name"=>$slug));
$count_seller = $select_seller->rowCount();
if($count_seller == 1){
	require_once("user.php");
}else{
	// $page = $db->query("select * from pages where url=:slug ",array("slug"=>$slug));
	// $countPage = $page->rowCount();
	// if($countPage == 1){
	// 	require_once("pages.php");
	// }else{
		echo "<script>window.open('index?not_available','_self');</script>";
	// }
}