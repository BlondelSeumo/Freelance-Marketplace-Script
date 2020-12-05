<?php

session_start();

require_once("db.php");

if(!isset($_SESSION['seller_user_name'])){
	
	echo "<script> 
	alert('Please login to your account and click on the activation link.');
	window.open('../login','_self'); 
	</script>";
	
}

$seller_user_name = $_SESSION['seller_user_name'];

$get_seller = $db->select("sellers",array("seller_user_name" => $seller_user_name));

$row_seller = $get_seller->fetch();

$seller_id = $row_seller->seller_id;

if(isset($_GET['code'])){
	
	$verification_code = $input->get('code');
	$count_seller = $db->count("sellers",array("seller_verification" => $verification_code));
	
	if($count_seller == 1){
			
		$update_seller = $db->update("sellers",array("seller_verification" => "ok"),array("seller_id" => $seller_id,"seller_verification" => $verification_code));
		
		if($update_seller){
			
			echo "<script>
				alert('{$lang['alert']['verify_email']}');
				window.open('../index','_self');
			</script>";
			
		}
			
	}else{
		
		echo "<script>
			alert('{$lang['alert']['invalid_link']}');
			window.open('../index','_self');
		</script>";
		
	}
	
}




?>