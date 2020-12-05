<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{


?>

<?php

if(isset($_GET['delete_coupon'])){
	
$delete_id = $input->get('delete_coupon');


$delete_coupon = $db->delete("coupons",array('coupon_id' => $delete_id));

if($delete_coupon){

$insert_log = $db->insert_log($admin_id,"coupon",$delete_id,"deleted");

echo "<script>alert('One Coupon Code Has been Deleted.');</script>";
	
echo "<script>window.open('index?view_coupons','_self');</script>";	
	
}
	
	
}

?>

<?php } ?>