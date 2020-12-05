<?php 

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

?>

<?php

if(isset($_GET['ban_seller'])){

	$seller_id = $input->get('ban_seller');

	$update_seller = $db->update("sellers",array("seller_status" => 'block-ban'),array("seller_id" => $seller_id));

	if($update_seller){

		$update_proposals = $db->update("proposals",array("proposal_status" => 'pause'),array("proposal_seller_id" => $seller_id,"proposal_status"=>'active'));
		$insert_log = $db->insert_log($admin_id,"seller",$seller_id,"block-ban");

		$get_seller = $db->select("sellers",array("seller_id" => $seller_id));
		$row_seller = $get_seller->fetch();
		$seller_user_name = $row_seller->seller_user_name;
		$seller_email = $row_seller->seller_email;

		$data = [];
		$data['template'] = "admin_ban_seller";
		$data['to'] = $seller_email;
		$data['subject'] = "$site_name: You Are Banned";
		$data['user_name'] = $seller_user_name;
		send_mail($data);
		
		echo "<script>alert('Seller has been blocked/banned successfully.');</script>";
		echo "<script>window.open('index?view_sellers','_self');</script>";

	}

	
}

?>

<?php } ?>