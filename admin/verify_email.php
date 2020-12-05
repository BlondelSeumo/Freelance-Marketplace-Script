<?php 

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

if(isset($_GET['verify_email'])){

$seller_id = $input->get('verify_email');

$update_seller = $db->update("sellers",array("seller_verification" => 'ok'),array("seller_id" => $seller_id));

if($update_seller){

echo "<script>alert_success('Seller email has been verified successfully.','index?view_sellers');</script>";
	
}
	
}

?>

<?php } ?>